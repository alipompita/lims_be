<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudyTestRequirement extends Model
{
    //
    use HasFactory;
    protected $fillable = ['study_id', 'test_type', 'spectype'];
    protected $table = 'study_test_requirements';

    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id');
    }

    public function spectype()
    {
        return $this->belongsTo(SpecimenType::class, 'spectype');
    }
}
