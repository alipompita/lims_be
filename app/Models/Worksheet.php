<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Worksheet extends Model
{
    use HasFactory;

    //         $table->string('code')->unique();
    //         $table->char('worksheet_type', 1);
    //         $table->foreignId('test_type_id')->constrained('test_types')->onDelete('cascade');
    //         $table->foreign('worksheet_type')->references('type')->on('worksheet_types')->onDelete('cascade');
    //         $table->timestamps();
    //     });

    protected $fillable = [
        'code',
        'worksheet_type',
        'test_type_id',
    ];

    protected $casts = [
        'worksheet_type' => 'char:1',
    ];
}
