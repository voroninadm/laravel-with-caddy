<?php

namespace App\Models;

use App\Casts\JsonOrderCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JsonOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'printing' => 'json',
        'lamination' => 'json',
        'welding' => 'json',
        'cutting' => 'json',
        'extrusion' => 'json',
        'recycling' => 'json',
        'spoolcutting' => 'json',
    ];
}
