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
    case FIELD_SCALE = 'fieldscale';

    public function dataPublicationSubDomain(): DataPublicationSubDomain
    {
        return match ($this) {
            self::ROCK_PHYSICS => DataPublicationSubDomain::ROCK_PHYSICS,
            self::ANALOGUE => DataPublicationSubDomain::ANALOGUE,
            self::MICROSCOPY => DataPublicationSubDomain::MICROSCOPY,
            self::PALEO => DataPublicationSubDomain::PALEO,
            self::GEO_CHEMISTRY => DataPublicationSubDomain::GEO_CHEMISTRY,
            self::FIELD_SCALE => DataPublicationSubDomain::FIELD_SCALE
        };
    }
}
