<?php

namespace App\Enums;

enum LabDomain: string
{
    case ROCK_PHYSICS = 'Rock and melt physics';
    case ANALOGUE = 'Analogue modelling of geologic processes';
    case MICROSCOPY = 'Microscopy and tomography';
    case PALEO = 'Paleomagnetism';
    case GEO_CHEMISTRY = 'Geochemistry';
    case GEO_ENERGY = 'Geo-energy test beds';
}
