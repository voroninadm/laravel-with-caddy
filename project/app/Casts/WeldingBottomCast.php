<?php

namespace App\Casts;

use App\Models\Elog\Welding;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class WeldingBottomCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $bottom_type = null;
        if ($value) {
            $bottom_type = config('constants.elog_welding_pocket_bottoms')[$value];
        }
        return $bottom_type;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $bottom_type = null;
        if ($value) {
            $bottom_type = array_search($value,  config('constants.elog_welding_pocket_bottoms'));
        }

        return $bottom_type;
    }
}
