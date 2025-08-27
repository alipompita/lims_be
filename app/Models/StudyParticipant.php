<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Testing\Fluent\Concerns\Has;

class StudyParticipant extends Model
{
    use HasFactory;

    protected $primaryKey = 'stid';
    public $incrementing = false; // stid is a string
    protected $keyType = 'string'; // stid is a string

    public function getRouteKeyName()
    {
        return 'stid'; // Use stid as the route key
    }

    protected $fillable = [
        'stid',
        'study_id',
        'initials',
        'sex',
        'dob',

    ];

    protected $casts = [
        'dob' => 'date',
        'study_id' => 'integer',
    ];

    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id');
    }

    public function specimens()
    {
        return $this->hasMany(Specimen::class, 'stid');
    }

    protected static function boot()
    {
        parent::boot();

        // Set created_by when creating
        static::creating(function ($model) {
            if (auth('sanctum')->check()) {
                $model->created_by = auth('sanctum')->id();
            }
        });

        // Set updated_by when updating
        static::updating(function ($model) {
            if (auth('sanctum')->check()) {
                $model->updated_by = auth('sanctum')->id();
            }
        });
    }
}
