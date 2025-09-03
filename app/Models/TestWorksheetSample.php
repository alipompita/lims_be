<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Event\Code\Test;

class TestWorksheetSample extends Model
{
    protected $fillable = [
        'worksheet_id',
        'labno',
        'test_results_id',
    ];

    public function worksheet()
    {
        return $this->belongsTo(TestWorksheet::class, 'worksheet_id');
    }
}
