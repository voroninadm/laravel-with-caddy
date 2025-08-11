<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class InfoUser extends Pivot
{
    use HasFactory;

    protected $table = 'info_user';
    protected $guarded = false;
}
