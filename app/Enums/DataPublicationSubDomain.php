<?php

namespace App\Enums;

/**
 * Subdomain categories for filtering data-publications in CKAN
 */
enum DataPublicationSubDomain: string
{
    case ROCK_PHYSICS = 'rock and melt physics';
    case ANALOGUE = 'analogue modelling of geologic processes';
    case MICROSCOPY = 'microscopy and tomography';
    case PALEO = 'paleomagnetism';
    case GEO_CHEMISTRY = 'geochemistry';
    case GEO_ENERGY = 'geo-energy test beds';
}
