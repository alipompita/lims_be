<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Testing\Fluent\Concerns\Has;

class StudyAccForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'study_id',
        'form_name',
        'form_description',
        'is_followup',
    ];

    protected $casts = [
        'is_followup' => 'boolean',
    ];

    public function sampleCollectionRequirements()
    {
        return $this->hasMany(SampleCollectionRequirement::class, 'study_acc_form_id');
    }

    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id');
    }
}
