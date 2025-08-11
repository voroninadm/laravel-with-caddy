<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Machine extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function type(): BelongsTo
    {
        return $this->belongsTo(MachinesType::class, 'type_id', 'id');
    }
}
