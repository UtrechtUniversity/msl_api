<?php

namespace App\Enums\Schemes;

enum VocabSchemes: string
{
    case GEOSCIML = 'geosciml';
    case MINDAT = 'mindat';
    case INSPIRE = 'inspire';

    public function getUrlPrefix(bool $isHttpsProtocol = false)
    {
        return match ($this) {
            self::GEOSCIML => 'http://resource.geosciml.org',
            self::MINDAT => 'https://www.mindat.org',
            self::INSPIRE => (! $isHttpsProtocol) ? 'http://inspire.ec.europa.eu' : 'https://inspire.ec.europa.eu',
        };
    }
}
