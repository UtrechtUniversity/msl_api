<?php

namespace App\Enums;

/**
 * Subdomain categories for filtering data-publications in CKAN.
 */
enum SubDomain
{
    case ROCK_PHYSICS;
    case ANALOGUE;
    case MICROSCOPY;
    case PALEO;
    case GEO_CHEMISTRY;
    case GEO_ENERGY;

    public function fullName(): string
    {
        return match($this) {
            SubDomain::ROCK_PHYSICS => 'rock and melt physics',
            SubDomain::ANALOGUE => 'analogue modelling of geologic processes',
            SubDomain::MICROSCOPY => 'microscopy',
            SubDomain::PALEO => 'paleomagnetism',
            SubDomain::GEO_CHEMISTRY => 'geochemistry',
            SubDomain::GEO_ENERGY => 'geo-energy test beds',
        };
    }
    public function displayName(): string
    {
        return match($this) {
            SubDomain::ROCK_PHYSICS => 'Rock and melt physics',
            SubDomain::ANALOGUE => 'Analogue modelling of geologic processes',
            SubDomain::MICROSCOPY => 'Microscopy',
            SubDomain::PALEO => 'Paleomagnetism',
            SubDomain::GEO_CHEMISTRY => 'Geochemistry',
            SubDomain::GEO_ENERGY => 'Geo-energy test beds',
        };
    }

    public function shortName(): string
    {
        return match($this) {
            SubDomain::ROCK_PHYSICS => 'Rock',
            SubDomain::ANALOGUE => 'Analogue',
            SubDomain::MICROSCOPY => 'Microscopy',
            SubDomain::PALEO => 'Paleo',
            SubDomain::GEO_CHEMISTRY => 'Geochem',
            SubDomain::GEO_ENERGY => 'Geoenergy',
        };
    }
}


