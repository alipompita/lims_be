<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study extends Model
{

    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getFullTitleAttribute(): string
    {
        return "{$this->code} - {$this->title}";
    }

    public function participants()
    {
        return $this->hasMany(StudyParticipant::class, 'study_id');
    }

    public function testRequirements()
    {
        return $this->hasMany(StudyTestRequirement::class, 'study_id');
    }

    public function accForms()
    {

        return $this->hasMany(StudyAccForm::class, 'study_id')->with('sampleCollectionRequirements');
    }
}
