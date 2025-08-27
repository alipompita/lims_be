<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SpecimenType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'code_label',
        'label',
        'description',
        'transport_method',
        'has_aliquot',
        'is_placenta_tissue',
        'total_aliquots',
        'taken_from_blood',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'has_aliquot' => 'boolean',
        'is_placenta_tissue' => 'boolean',
        'total_aliquots' => 'integer',
        'taken_from_blood' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    public function isActive(): bool
    {
        return $this->is_active;
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
