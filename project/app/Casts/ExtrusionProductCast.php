<?php

namespace App\Casts;

use App\Models\Elog\Extrusion;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ExtrusionProductCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $product_type = null;
        if ($value) {
            $product_type = config('constants.elog_extrusion_products')[$value];
        }
        return $product_type;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $product_type = null;
        if ($value) {
            $product_type = array_search($value, config('constants.elog_extrusion_products'));
        }

        return $product_type;
    }
}
