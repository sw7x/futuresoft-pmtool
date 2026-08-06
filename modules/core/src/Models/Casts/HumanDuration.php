<?php
namespace Modules\Core\Models\Casts;

use Carbon\CarbonInterval;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class HumanDuration implements CastsAttributes
{
    /**
     * Cast the given value FROM the database (Integer -> Formatted String).
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return null;
        }

        // Change 'minutes' to 'seconds' or 'hours' depending on what your column stores
        return CarbonInterval::minutes($value)
            ->cascade()
            ->forHumans(['join' => true, 'parts' => 3]);
    }

    /**
     * Prepare the given value TO save into the database (if passing minutes back in).
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}