<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
// DB

class ReportsController extends Controller
{
    public function index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'study' => 'nullable|exists:studies,id',
            'specimen_type' => 'nullable|exists:specimen_types,id',
            'rejected' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 201);
        }
        // generate report using custom sql query
        $sql = "SELECT
                    count(*) as samples_collected,
                    sum(case when r.rejected = true then 1 else 0 end) as samples_rejected,
                    sum(case when r.rejected = false or r.rejected is null then 1 else 0 end) as samples_accepted
                from sample_receipts r
                left join specimen_types st on st.id = r.spectype";

        $where_clause = '';

        if ($request->has('start_date') && $request->has('end_date')) {
            $where_clause .= " WHERE r.dateinlab BETWEEN '{$request->start_date}' AND '{$request->end_date}'";
        }

        // filter sites
        $user = $request->user();
        $user_default_site = $user->default_site_id;
        if ($user->role != 'admin') {
            if (strlen($where_clause) > 0) {
                $where_clause .= " AND";
            } else {
                $where_clause .= " WHERE";
            }
            $where_clause .= " r.site_id = {$user_default_site}";
        } else if ($request->has('site_id')) {
            if (strlen($where_clause) > 0) {
                $where_clause .= " AND";
            } else {
                $where_clause .= " WHERE";
            }
            $where_clause .= " r.site_id = {$request->site_id}";
        }

        if ($request->has('study')) {
            if (strlen($where_clause) > 0) {
                $where_clause .= " AND";
            } else {
                $where_clause .= " WHERE";
            }
            $where_clause .= " r.study_id = {$request->study}";
        }

        if (strlen($where_clause) > 0) {
            $sql .= $where_clause;
        }
        $sample_reception = DB::select($sql);

        return response()->json([
            'success' => true,
            "data" => [
                "sample_reception" => $sample_reception,
            ],
        ]);
    }
}
