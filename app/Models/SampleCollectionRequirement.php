<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SampleCollectionRequirement extends Model
{
    protected $fillable = [
        'study_acc_form_id',
        'spectype',
        'volume_required',
        'volume_unit',
        'recommended_shipping_temperature',
    ];

    protected $casts = [
        'volume_required' => 'float',
        'recommended_shipping_temperature' => 'float',
    ];

    public function studyAccForm()
    {
        return $this->belongsTo(StudyAccForm::class, 'study_acc_form_id');
    }

    public function specimenType()
    {
        return $this->belongsTo(\App\Models\SpecimenType::class, 'spectype');
    }

    public function study()
    {
        return $this->hasOneThrough(
            Study::class,
            StudyAccForm::class,
            'id', // Foreign key on StudyAccForm table...
            'id', // Foreign key on Study table...
            'study_acc_form_id', // Local key on SampleCollectionRequirement table...
            'study_id' // Local key on StudyAccForm table...
        );
    }
}
