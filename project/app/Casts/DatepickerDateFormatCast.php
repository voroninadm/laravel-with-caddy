<?php

namespace App\Casts;

use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class DatepickerDateFormatCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string|null
    {
        if ($value) {
            $dateTime = new DateTime($value);

            $timezone = config('app.timezone');
            $carbonDateTime = Carbon::instance($dateTime)->setTimezone($timezone);

            return $carbonDateTime->format('Y-m-d H:i:s');
        } else {
            return null;
        }
    }
}
