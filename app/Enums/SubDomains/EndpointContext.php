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
    case GEO_ENERGY;
    case ALL;

    public function getDataPublicationSubdomainValue(): ?string
    {
        return match ($this) {
            self::ROCK_PHYSICS => DataPublicationSubDomain::ROCK_PHYSICS->value,
            self::ANALOGUE => DataPublicationSubDomain::ANALOGUE->value,
            self::MICROSCOPY => DataPublicationSubDomain::MICROSCOPY->value,
            self::PALEO => DataPublicationSubDomain::PALEO->value,
            self::GEO_CHEMISTRY => DataPublicationSubDomain::GEO_CHEMISTRY->value,
            self::GEO_ENERGY => DataPublicationSubDomain::GEO_ENERGY->value,
            self::ALL => null
        };
    }
}
