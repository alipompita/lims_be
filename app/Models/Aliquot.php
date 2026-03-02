<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aliquot extends Model
{
    use HasFactory;

    protected $fillable = [
        'labno',
        'volume',
        'created_at_site_id',
        'created_by',
        'current_freezer_id',
        'current_rack',
        'current_box',
        'current_position',
        'thaw_count',
        'is_disposed',
        'disposed_by',
        'disposed_at',
        'disposed_at_site_id',
    ];

    protected $casts = [
        'volume' => 'double',
        'thaw_count' => 'integer',
        'is_disposed' => 'boolean',
        'disposed_at' => 'datetime',
        'current_position' => 'string',
        'current_rack' => 'integer',
        'current_box' => 'integer',
    ];

    public function specimen()
    {
        return $this->belongsTo(Specimen::class, 'labno', 'labno');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
