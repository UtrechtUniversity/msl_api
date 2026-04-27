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
}
