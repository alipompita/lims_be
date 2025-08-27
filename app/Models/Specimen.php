<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Specimen extends Model
{
    //
    protected $fillable = [
        'labno',
        'specno',
        'spectype',
        'stid',
        'cno',
        'accForm',
        'repeat_sample',
        'pregnant',
        'curmens',
        'mens2d',
        'basefoll',
        'fast',
        'venepunc',
        'volume',
        'tubes',
        'stooltype',
        'stoolusual',
        'spectime',
        'datecol',
        'timeprod',
        'timeint',
        'iohexol',
        'dateinlab',
        'timeinlab',
        'staffcode',
        'labstaff',
        'checker',
        'rcdr',
        'version',
    ];

    protected $casts = [
        'repeat_sample' => 'boolean',
        'pregnant' => 'boolean',
        'curmens' => 'boolean',
        'mens2d' => 'boolean',
        'fast' => 'boolean',
        'volume' => 'float',
        'stooltype' => 'integer',
        // 'spectime' => 'time',
        'datecol' => 'date',
        // 'timeprod' => 'time',
        // 'timeint' => 'time',
        'iohexol' => 'boolean',
        'dateinlab' => 'date',
        // 'timeinlab' => 'time',
        'staffcode' => 'integer',
    ];

    public function specimenType()
    {
        return $this->belongsTo(SpecimenType::class, 'spectype');
    }

    public function studyParticipant()
    {
        return $this->belongsTo(StudyParticipant::class, 'stid');
    }

    public function labStaff()
    {
        return $this->belongsTo(User::class, 'labstaff');
    }
}
