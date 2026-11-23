<?php

namespace App\Enums\Schemes\IdSchemes;

enum RelatedIdScheme: string
{
    case ARK = 'ark';
    case ARXIV = 'arxiv';
    case BIBCODE = 'bibcode';
    case CSTR = 'cstr';
    case DOI = 'doi';
    case EAN13 = 'ean13';
    case EISSN = 'eissn';
    case HANDLE = 'handle';
    case IGSN = 'igsn';
    case ISBN = 'isbn';
    case ISSN = 'issn';
    case ISTC = 'istc';
    case LISSN = 'lissn';
    case LSID = 'lsid';
    case PMID = 'pmid';
    case PURL = 'purl';
    case RAID = 'raid';
    case RRID = 'rrid';
    case SWHID = 'swhid';
    case UPC = 'upc';
    case URL = 'url';
    case URN = 'urn';
    case W3ID = 'w3id';

    public static function tryFromScheme(string $value): ?static
    {

        $lowerCaseValue = trim(strtolower($value));
        return RelatedIdScheme::tryFrom($lowerCaseValue);
    }

    public function getNormalizedValue(string $value): string
    {
        return match ($this) {
            self::DOI => str_starts_with($value, 'http') ? $value : 'https://doi.org/' . $value,
            default => $value
        };
    }
}
