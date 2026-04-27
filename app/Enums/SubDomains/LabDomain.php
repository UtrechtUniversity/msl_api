<?php

namespace App\Enums\SubDomains;

/**
 * Domain categories for filtering laboratories in CKAN.
 */
enum LabDomain: string
{
    case ROCK_PHYSICS = 'Rock and melt physics';
    case ANALOGUE = 'Analogue modelling of geological processes';
    case MICROSCOPY = 'Microscopy and tomography';
    case PALEO = 'Paleomagnetism';
    case GEO_CHEMISTRY = 'Geochemistry';
    case FIELD_SCALE = 'Field-Scale Laboratories';
}
