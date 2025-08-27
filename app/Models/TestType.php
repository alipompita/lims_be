<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestType extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'name',
        'description',
    ];

    protected $casts = [
        'parameters' => 'array',
    ];

    public function parameters()
    {
        return $this->hasMany(TestParameter::class, 'test_type_id');
    }
}
