<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SampleReceipt;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SampleReceptionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $user_default_site = $user->default_site_id;
        $selected_site = $user_default_site;

        $query = SampleReceipt::with(['study', 'specimenType', 'specimenDetails', 'entryBy', 'updatedBy', 'accForm']);
        // Filter by rejection status
        if ($request->has('rejected')) {
            //    ignore if value is not boolean
            if (in_array($request->input('rejected'), ['0', '1', 0, 1, true, false, 'true', 'false'], true)) {
                $isRejected = $request->boolean('rejected');
                if ($isRejected) {
                    $query->rejected();
                } else {
                    $query->notRejected();
                }
            }
        }
        $isAdmin = null;
        // filter site
        if ($user->get('role') != 'admin') {
            $isAdmin = false;
            $selected_site = $user_default_site;
            $query->where('site_id', $selected_site);
        } else if ($request->has('site_id')) {
            $isAdmin = true;
            $selected_site = $request->input('site_id');
            $query->where('site_id', $selected_site);
        }
        //filter by period
        if ($request->has('period')) {
            $period = $request->input('period');
            if ($period == 'today') {
                $query->whereDate('created_at', now()->toDateString());
            } elseif ($period == 'this_week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($period == 'this_month') {
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            }
        } else if ($request->has('start') & $request->has('end')) {
            $start = $request->input('start');
            $end = $request->input('end');
            $query->whereBetween('created_at', [$start, $end]);
        }
        $sampleReceipts = $query->orderBy('id', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $sampleReceipts,
            'requesting_user' => $user,
            "selected_site" => $selected_site,
            "default_site" => $user_default_site,

        ]);
    }

    public function show($id)
    {
        $sample_receipt = SampleReceipt::with(['study', 'specimenType', 'entryBy', 'updatedBy'])->find($id);

        if (!$sample_receipt) {
            return response()->json([
                'success' => false,
                'message' => 'Sample receipt not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sample_receipt,
        ]);
    }

    public function update(Request $request, $id)
    {
        $sample_receipt = SampleReceipt::find($id);

        if (!$sample_receipt) {
            return response()->json([
                'success' => false,
                'message' => 'Sample receipt not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'study_id' => 'sometimes|required|exists:studies,id',
            'basefol' => 'sometimes|nullable|string|max:50',
            'stid' => 'sometimes|nullable|string|max:7|min:7',
            'spectype' => 'sometimes|required|exists:specimen_types,id',
            'specno' => 'sometimes|required|string|max:7|min:7|unique:sample_receipts,specno,' . $sample_receipt->id,
            'datecol' => 'sometimes|required|date|before_or_equal:today',
            'dateinlab' => 'sometimes|required|date|after_or_equal:datecol|before_or_equal:today',
            'rejected' => 'sometimes|boolean',
            'resrej' => 'sometimes|required_if:rejected,1|string|max:255',
            // 'entry_by' => 'sometimes|nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 201);
        }

        $sample_receipt->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $sample_receipt,
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'study_id' => 'required|exists:studies,id',
            'basefol' => 'nullable|string|max:50',
            'stid' => 'required|string|max:7|min:7',
            'spectype' => 'required|exists:specimen_types,id',
            'specno' => 'required|string|max:7|min:7|unique:sample_receipts,specno',
            'datecol' => 'required|date|before_or_equal:today',
            'dateinlab' => 'required|date|after_or_equal:datecol|before_or_equal:today',
            'rejected' => 'boolean',
            'resrej' => 'required_if:rejected,1|string|max:255',
            // 'entry_by' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 201);
        }



        $user = $request->user();
        $user_default_site = $user->default_site_id;

        $sample_receipt = SampleReceipt::create([
            'study_id' => $request->study_id,
            'basefol' => $request->basefol,
            'stid' => $request->stid,
            'spectype' => $request->spectype,
            'specno' => $request->specno,
            'datecol' => $request->datecol,
            'dateinlab' => $request->dateinlab,
            'rejected' => $request->rejected ?? false,
            'resrej' => $request->resrej ?? null,
            'site_id' => $user_default_site ?? null,
            // 'entry_by' => $request->entry_by,
        ]);

        return response()->json([
            'success' => true,
            'data' => $sample_receipt,
        ], 201);
    }

    public function indexReport(Request $request)
    {


        // generate report using custom sql query
        $whereClauses = [];

        if ($request->has('start_date')) {
            $start = $request->input('start_date');
            $whereClauses[] = "r.created_at >= '$start'";
        }
        if ($request->has('end_date')) {
            $end = $request->input('end_date');
            $whereClauses[] = "r.created_at <= '$end'";
        }
        if ($request->has('study')) {
            $study = $request->input('study');
            $whereClauses[] = "r.study_id = '$study'";
        }
        $sql = "SELECT
                    st.id as spectype_code,
                    st.label as spectype,
                    count(*) as samples_collected,
                    sum(case when r.rejected = true then 1 else 0 end) as samples_rejected,
                    sum(case when r.rejected = false or r.rejected is null then 1 else 0 end) as samples_accepted
                from sample_receipts r
                left join specimen_types st on st.id = r.spectype
                ";

        if (count($whereClauses) > 0) {
            $sql .= "where " . implode(" and ", $whereClauses) . " ";
        }

        $sql .= "group by st.id, st.label
                order by st.id, st.label;";


        $report = DB::select($sql);

        return response()->json([
            'success' => true,
            'sql' => $sql,
            'data' => $report,
        ]);
    }

    public function study_sample_report(Request $request)
    {

        $whereClauses = [];
        if ($request->has('start_date')) {
            $start = $request->input('start_date');
            $whereClauses[] = "r.created_at >= '$start'";
        }
        if ($request->has('end_date')) {
            $end = $request->input('end_date');
            $whereClauses[] = "r.created_at <= '$end'";
        }
        if ($request->has('study')) {
            $study = $request->input('study');
            $whereClauses[] = "r.study_id = '$study'";
        }

        $sql = "SELECT s.code as study, af.form_name as basefol, count(*) as samples_collected,
                    sum(case when r.rejected = true then 1 else 0 end) as samples_rejected,
                    sum(case when r.rejected = false or r.rejected is null then 1 else 0 end) as samples_accepted
                from sample_receipts r
                left join studies s on s.id = r.study_id
                left join study_acc_forms af on af.id = r.basefol 
              ";

        if (count($whereClauses) > 0) {
            $sql .= "where " . implode(" and ", $whereClauses) . " ";
        }

        $sql .= " group by s.code, r.basefol
                order by s.code, r.basefol;";
        $report = DB::select($sql);

        return response()->json([
            'success' => true,
            'data' => $report,
        ]);
    }
}
