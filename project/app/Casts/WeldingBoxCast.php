<?php

namespace App\Casts;

use App\Models\Elog\Welding;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class WeldingBoxCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): string|null
    {
        $box_type = null;
        if($value) {
            $box_type =  config('constants.elog_welding_boxes')[$value];
        }
        return $box_type;
    }

    /**
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return int|null
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): int|null
    {
        $box_type = null;
        if($value) {
            $box_type = array_search($value, config('constants.elog_welding_boxes'));
        }
        return $box_type;
    }
}
