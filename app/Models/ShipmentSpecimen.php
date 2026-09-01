<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShipmentSpecimen extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'labno',
        'shipment_purpose',
        'box_number',
        'position',
        'qty',
        'unit',
        'received',
        'condition_received',
        'condition_other',
        'purposes_satisfied'
    ];

    protected $casts = [
        'shipment_purpose' => 'string',
        'box_number' => 'integer',
        'position' => 'string',
        'qty' => 'float',
        'unit' => 'string',
        'received' => 'boolean',
        'condition_received' => 'string',
        'condition_other' => 'string',
        'purposes_satisfied' => 'boolean',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }
}
