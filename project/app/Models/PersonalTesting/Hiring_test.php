<?php

namespace App\Models\PersonalTesting;

use App\Casts\WorkerNameToIdCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Hiring_test extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $table = 'hiring_tests';

    protected $casts = [
        'about' => 'json',
        'raw_test_results' => 'json',
        'recommender_id' => WorkerNameToIdCast::class,
    ];
}
