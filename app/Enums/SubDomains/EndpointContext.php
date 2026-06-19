<?php

namespace App\Enums\SubDomains;

/**
 * Endpoint context categories relevant
 * to the 6 domains of facilities and data-publications.
 */
enum EndpointContext
{
    case ROCK_PHYSICS;
    case ANALOGUE;
    case MICROSCOPY;
    case PALEO;
    case GEO_CHEMISTRY;
    case FIELD_SCALE;
    case ALL;

    public function getDataPublicationSubdomainValue(): ?string
    {
        return match ($this) {
            self::ROCK_PHYSICS => DataPublicationSubDomain::ROCK_PHYSICS->value,
            self::ANALOGUE => DataPublicationSubDomain::ANALOGUE->value,
            self::MICROSCOPY => DataPublicationSubDomain::MICROSCOPY->value,
            self::PALEO => DataPublicationSubDomain::PALEO->value,
            self::GEO_CHEMISTRY => DataPublicationSubDomain::GEO_CHEMISTRY->value,
            self::FIELD_SCALE => DataPublicationSubDomain::FIELD_SCALE->value,
            self::ALL => null
        };
    }
}
