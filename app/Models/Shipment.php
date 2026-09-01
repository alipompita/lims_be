<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{

    use HasFactory;

    protected $fillable = [
        'from_site_id',
        'to_site_id',
        'shipped_by',
        'date_shipped',
        'date_received',
        'received_by',
    ];

    protected $casts = [
        'date_shipped' => 'date',
        'date_received' => 'date',
    ];

    public function specimen()
    {
        return $this->hasMany(ShipmentSpecimen::class, 'shipment_id');
    }
}
