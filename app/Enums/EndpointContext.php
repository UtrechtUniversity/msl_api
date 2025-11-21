<?php

namespace App\Enums;

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
}
