<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GeoRule implements ValidationRule
{
    private function getLongBoundariesErrorMessage(string $value)
    {
        return `The longitude must be between -180 and 180, but its value is '$value'`;
    }

    private function getLatBoundariesErrorMessage(string $value)
    {
        return `The latitude must be between -90 and 90, but its value is '$value'`;
    }

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

        if (! is_array($value)) {
            $fail('The :attribute field must be an array.');

            return;
        }

        if (count($value) !== 4) {
            $fail('The :attribute field must contain exactly four values.');

            return;
        }

        // Bounds
        [$firstLong, $firstLat, $secondLong, $secondLat] = $value;
        if (! is_numeric($firstLong) || $firstLong < -180 || $firstLong > 180) {
            $fail($this->getLongBoundariesErrorMessage($firstLong));
        }
        if (! is_numeric($firstLat) || $firstLat < -90 || $firstLat > 90) {
            $fail($this->getLatBoundariesErrorMessage($firstLat));
        }
        if (! is_numeric($secondLong) || $secondLong < -180 || $secondLong > 180) {
            $fail($this->getLongBoundariesErrorMessage($secondLong));
        }
        if (! is_numeric($secondLat) || $secondLat < -90 || $secondLat > 90) {
            $fail($this->getLatBoundariesErrorMessage($secondLat));
        }
    }
}
