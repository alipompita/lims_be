<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Worksheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'worksheet_type',
    ];

    protected $casts = [
        'worksheet_type' => 'char:1',
    ];

    public function samples()
    {
        // 
    }
}

class TestWorksheet extends Worksheet
{
    protected $table = 'worksheets';

    protected static function booted()
    {
        static::addGlobalScope('worksheet_type', function ($builder) {
            $builder->where('worksheet_type', 'T');
        });
    }

    protected $fillable = [
        'code',
        'worksheet_type',
        'test_type_id',
    ];

    protected $casts = [
        'worksheet_type' => 'char:1',
    ];

    public function samples()
    {
        return $this->hasMany(TestWorksheetSample::class, 'worksheet_id');
    }
}

class StorageWorksheet extends Worksheet
{
    protected $table = 'worksheets';

    protected static function booted()
    {
        static::addGlobalScope('worksheet_type', function ($builder) {
            $builder->where('worksheet_type', 'S');
        });
    }

    protected $fillable = [
        'code',
        'worksheet_type',
    ];

    public function samples()
    {
        // return $this->hasMany(StorageWorksheetSample::class, 'worksheet_id');
    }

    protected $casts = [
        'worksheet_type' => 'char:1',
    ];
}
