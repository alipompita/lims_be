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
        'normal_range_min' => 'float',
        'normal_range_max' => 'float',
    ];

    public function testType()
    {
        return $this->belongsTo(TestType::class, 'test_type_id');
    }
}
