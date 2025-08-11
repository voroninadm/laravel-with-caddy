<?php

namespace App\Casts;

use App\Models\Worker;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WorkerNameToIdCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {

        $workersGroup = Cache::get("allWorkersIdsNames");
        foreach ($workersGroup as $worker) {
            if ($worker['id'] === $value) {
                return $worker['name'];
            }
        }
        return '';
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {

        $workersGroup = Cache::get("allWorkersIdsNames");
        foreach ($workersGroup as $worker) {
            if ($worker['name'] === $value) {
                return $worker['id'];
            }
        }
        return null;
    }
}
