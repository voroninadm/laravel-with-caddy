<?php

namespace App\Casts;

use App\Models\Material;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MaterialNameToIdCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */

    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {

        $materials = Cache::get("allMaterialsIdsNames");
        foreach ($materials as $material) {
            if ($material['id'] === $value) {
                return $material['name'];
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

        $materials = Cache::get("allMaterialsIdsNames");
        foreach ($materials as $material) {
            if ($material['name'] === $value) {
                return $material['id'];
            }
        }
        return null;
    }
}
