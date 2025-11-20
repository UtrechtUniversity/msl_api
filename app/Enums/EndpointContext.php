<?php

namespace App\Enums;

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
