<?php

namespace App\Enums\IdSchemes;

enum OrganizationIdScheme: string
{
    case ROR = 'ror';
    case ISNI = 'isni';

    public static function tryFromScheme(string $value): ?static
    {

        $lowerCaseValue = trim(strtolower($value));
        switch ($lowerCaseValue) {
            case self::ROR->value:
                return self::ROR;
            case self::ISNI->value:
                return self::ISNI;
            default:
                return null;
        }
    }

    public function getUrlPrefix(): string
    {
        return match ($this) {
            self::ROR => 'https://ror.org/',
            self::ISNI => 'https://isni.org/isni',
        };
    }
}
