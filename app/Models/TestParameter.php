<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_type_id',
        'name',
        'description',
        'type',
        'unit',
        'normal_range_min',
        'normal_range_max',
    ];

    protected $casts = [
        'normal_range_min' => 'decimal:10,2',
        'normal_range_max' => 'decimal:10,2',
    ];

    public function testType()
    {
        return $this->belongsTo(TestType::class, 'test_type_id');
    }
}
