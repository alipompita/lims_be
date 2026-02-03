<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SampleReceipt extends Model
{
    use HasFactory;
    protected $fillable = [
        'study_id',
        'basefol',
        'stid',
        'spectype',
        'specno',
        'datecol',
        'dateinlab',
        'entry_by',
        'rejected',
        'resrej',
        'updated_by',
    ];

    protected $casts = [
        'datecol' => 'date',
        'dateinlab' => 'date',
        'rejected' => 'boolean',
    ];

    public function study()
    {
        return $this->belongsTo(Study::class);
    }

    public function specimenDetails()
    {
        return $this->belongsTo(Specimen::class, 'specno', 'specno');
    }

    public function accForm()
    {
        return $this->belongsTo(StudyAccForm::class, 'basefol', 'id');
    }

    public function specimenType()
    {
        return $this->belongsTo(SpecimenType::class, 'spectype');
    }

    public function entryBy()
    {
        return $this->belongsTo(User::class, 'entry_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeRejected($query)
    {
        return $query->where('rejected', true);
    }

    public function scopeNotRejected($query)
    {
        return $query->where('rejected', false);
    }

    public function markAsRejected($reason)
    {
        $this->rejected = true;
        $this->resrej = $reason;
        $this->save();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth('sanctum')->check()) {
                $model->entry_by = auth('sanctum')->id();
            }
        });

        static::updating(function ($model) {
            if (auth('sanctum')->check()) {
                $model->updated_by = auth('sanctum')->id();
            }
        });
    }
}
