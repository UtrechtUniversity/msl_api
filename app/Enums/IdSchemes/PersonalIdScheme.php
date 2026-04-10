<?php

namespace App\Enums\IdSchemes;

enum PersonalIdScheme: string
{
    case ORCID = 'orcid';
    case ISNI = 'isni';
    case SCOPUS_ID = 'scopusid';

    public static function tryFromScheme(string $value): ?static
    {

        $lowerCaseValue = trim(strtolower($value));
        switch ($lowerCaseValue) {
            case self::ORCID->value:
                return self::ORCID;
            case self::ISNI->value:
                return self::ISNI;
            case self::SCOPUS_ID->value:
            case 'author identifier (scopus)':
                return self::SCOPUS_ID;
            default:
                return null;
        }
    }

    public function getUrlPrefix(): string
    {
        return match ($this) {
            self::ORCID => 'https://orcid.org/',
            self::ISNI => 'https://isni.org/isni',
            self::SCOPUS_ID => 'https://www.scopus.com/authid/detail.uri?authorId=',
        };
    }
}
