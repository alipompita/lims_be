<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudyStorageRequirement extends Model
{
    use HasFactory;
    protected $fillable = ['study_id', 'spectype', 'aliqotes'];
    protected $table = 'study_storage_requirements';

    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id');
    }

    public function spectype()
    {
        return $this->belongsTo(SpecimenType::class, 'spectype');
    }
}
