<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GeoRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        if (!is_array($value)) {
            $fail('The :attribute field must be an array.');
            return;
        }

        if (count($value) !== 4) {
            $fail('The :attribute field must contain exactly four values.');
            return;
        }

        // Element-specific checks
        if (!is_numeric($value[0]) || $value[0] < -180 || $value[0] > 180) {
            $fail('The first value (minx) must be between -180 and 180.');
        }
        if (!is_numeric($value[1]) || $value[1] < -90 || $value[1] > 90) {
            $fail('The second value (miny) must be between -90 and 90.');
        }
        if (!is_numeric($value[2]) || $value[2] < -180 || $value[2] > 180) {
            $fail('The third value (maxx) must be between -180 and 180.');
        }
        if (!is_numeric($value[3]) || $value[3] < -90 || $value[3] > 90) {
            $fail('The fourth value (maxy) must be between -90 and 90.');
        }

        // Optional: relational logic
        if ($value[0] >= $value[2] || $value[1] >= $value[3]) {
            $fail('Invalid bounding box: min values must be less than max values.');
        }
    }
}
