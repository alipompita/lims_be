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
        'posted',
        'created_by',
        'date_shipped',
        'date_received',
        'received_by',
    ];

    protected $casts = [
        'date_shipped' => 'date',
        'date_received' => 'date',
        'posted' => 'boolean',
    ];

    public function specimen()
    {
        return $this->hasMany(ShipmentSpecimen::class, 'shipment_id');
    }

    public function shipped_by()
    {
        return $this->belongsTo(User::class, 'shipped_by');
    }

    public function received_by()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function is_posted()
    {
        return $this->posted;
    }

    public function is_received()
    {
        return $this->received;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth('sanctum')->check()) {
                $model->created_by = auth('sanctum')->id();
            }
        });
    }
}
