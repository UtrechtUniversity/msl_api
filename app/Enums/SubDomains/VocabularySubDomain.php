<?php

namespace App\Enums\SubDomains;

/**
 * Names of subdomains used in the Vocabulary Model.
 */
enum VocabularySubDomain: string
{
    case ROCK_PHYSICS = 'rockphysics';
    case ANALOGUE = 'analogue';
    case MICROSCOPY = 'microscopy';
    case PALEO = 'paleomagnetism';
    case GEO_CHEMISTRY = 'geochemistry';
    case GEO_ENERGY = 'testbeds';

    public function dataPublicationSubDomain(): DataPublicationSubDomain
    {
        return match ($this) {
            self::ROCK_PHYSICS => DataPublicationSubDomain::ROCK_PHYSICS,
            self::ANALOGUE => DataPublicationSubDomain::ANALOGUE,
            self::MICROSCOPY => DataPublicationSubDomain::MICROSCOPY,
            self::PALEO => DataPublicationSubDomain::PALEO,
            self::GEO_CHEMISTRY => DataPublicationSubDomain::GEO_CHEMISTRY,
            self::GEO_ENERGY => DataPublicationSubDomain::GEO_ENERGY
        };
    }
}
