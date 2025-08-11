<?php

namespace App\Casts;

use App\Models\Machine;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MachineNameToIdCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {

        $machines = Cache::get("allMachinesIdsTitles");
        foreach ($machines as $machine) {
            if ($machine['id'] === $value) {
                return $machine['title'];
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

        $machines = Cache::get("allMachinesIdsTitles");
        foreach ($machines as $machine) {
            if ($machine['title'] === $value) {
                return $machine['id'];
            }
        }
        return null;
    }
}
