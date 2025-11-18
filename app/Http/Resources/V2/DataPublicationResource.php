<?php

namespace App\Http\Resources\V2;

use App\Http\Resources\V2\Helpers\Descriptions;
use App\Http\Resources\V2\Elements\AlternateIdentifierResource;
use App\Http\Resources\V2\Elements\ContributorResource;
use App\Http\Resources\V2\Elements\CreatorResource;
use App\Http\Resources\V2\Elements\DateResource;
use App\Http\Resources\V2\Elements\DescriptionResource;
use App\Http\Resources\V2\Elements\FileResource;
use App\Http\Resources\V2\Elements\FundingReferenceResource;
use App\Http\Resources\V2\Elements\RelatedIdentifierResource;
use App\Http\Resources\V2\Elements\RightResource;
use App\Http\Resources\V2\Elements\SubjectResource;
use Illuminate\Http\Resources\Json\JsonResource;

enum VocabularyType: string
{
    case ROCK_PHYSICS = 'rockPhysics';
    case ANALOGUE = 'analogue';
    case GEOLOGICAL_SETTING = 'geologicalSetting';
    case PALEO = 'paleo';
    case GEO_CHEMISTRY = 'geoChemistry';
    case GEO_ENERGY = 'geoEnergy';
}
class DataPublicationResource extends JsonResource
{
    private $context;

    private $uriStartsPerSubject = [];

    public function __construct($resource, $context = '')
    {
        parent::__construct($resource);
        $this->context = $context;

        $this->uriStartsPerSubject = [
            VocabularyType::ROCK_PHYSICS->value => [
                'https://epos-msl.uu.nl/voc/rockphysics/' . config('vocabularies.vocabularies_current_version') . '/measured_property-',
                'https://epos-msl.uu.nl/voc/rockphysics/' . config('vocabularies.vocabularies_current_version') . '/inferred_deformation_behavior-',
            ],
            VocabularyType::ANALOGUE->value => [
                'https://epos-msl.uu.nl/voc/analoguemodelling/' . config('vocabularies.vocabularies_current_version') . '/modeled_structure-',
                'https://epos-msl.uu.nl/voc/analoguemodelling/' . config('vocabularies.vocabularies_current_version') . '/modeled_geomorphological_feature-',
                'https://epos-msl.uu.nl/voc/analoguemodelling/' . config('vocabularies.vocabularies_current_version') . '/measured_property-',
            ],
            VocabularyType::GEOLOGICAL_SETTING->value => [
                'https://epos-msl.uu.nl/voc/geologicalsetting/' . config('vocabularies.vocabularies_current_version') . '/',
            ],
            VocabularyType::PALEO->value => [
                'https://epos-msl.uu.nl/voc/paleomagnetism/' . config('vocabularies.vocabularies_current_version') . '/measured_property-',
                'https://epos-msl.uu.nl/voc/paleomagnetism/' . config('vocabularies.vocabularies_current_version') . '/inferred_behavior-',
            ],
            VocabularyType::GEO_CHEMISTRY->value => [
                'https://epos-msl.uu.nl/voc/geochemistry/' . config('vocabularies.vocabularies_current_version') . '/',
            ],
            VocabularyType::GEO_ENERGY->value => [
                'https://epos-msl.uu.nl/voc/testbeds/' . config('vocabularies.vocabularies_current_version') . '/facility_names-',
                'https://epos-msl.uu.nl/voc/testbeds/' . config('vocabularies.vocabularies_current_version') . '/equipment-',
                'https://epos-msl.uu.nl/voc/testbeds/' . config('vocabularies.vocabularies_current_version') . '/model-',
            ],
        ];
    }

    private function getMaterials()
    {
        $materials = [];
        foreach ($this->msl_enriched_keywords as $value) {
            if (str_starts_with($value->msl_enriched_keyword_vocab_uri, 'https://epos-msl.uu.nl/voc/materials')) {
                $materials[] = $value->msl_enriched_keyword_label;
            }
        }

        return $materials;
    }

    private function getResearchAspectsPerSubject(VocabularyType $subject)
    {
        $keywords = [];
        foreach ($this->msl_enriched_keywords as $enrichedKeyword) {
            foreach ($this->uriStartsPerSubject[$subject->value] as $uriStart) {
                if (str_starts_with($enrichedKeyword->msl_enriched_keyword_uri, $uriStart)) {
                    $keywords[] = $enrichedKeyword->msl_enriched_keyword_label;
                }
            }
        }

        return $keywords;
    }

    private function getResearchAspects()
    {
        $researchAspects = [];
        switch ($this->context) {
            case 'rockPhysics':
                $keywords = [];
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::ROCK_PHYSICS));
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::GEOLOGICAL_SETTING));
                $keywords = array_values(array_unique($keywords));

                $researchAspects = $keywords;
                break;
            case 'analogue':
                $keywords = [];
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::ANALOGUE));
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::GEOLOGICAL_SETTING));
                $keywords = array_values(array_unique($keywords));

                $researchAspects = $keywords;
                break;
            case 'paleo':
                $keywords = [];
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::PALEO));
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::GEOLOGICAL_SETTING));
                $keywords = array_values(array_unique($keywords));

                $researchAspects = $keywords;
                break;
            case 'microscopy':
                $keywords = [];
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::GEOLOGICAL_SETTING));
                $keywords = array_values(array_unique($keywords));

                $researchAspects = $keywords;
                break;
            case 'geochemistry':
                $keywords = [];
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::GEO_CHEMISTRY));
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::GEOLOGICAL_SETTING));
                $keywords = array_values(array_unique($keywords));

                $researchAspects = $keywords;
                break;
            case 'geoenergy':
                $keywords = [];
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::GEO_ENERGY));
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::GEOLOGICAL_SETTING));
                $keywords = array_values(array_unique($keywords));

                $researchAspects = $keywords;
                break;
            case 'all':
                $keywords = [];

                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::ROCK_PHYSICS));
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::ANALOGUE));
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::PALEO));
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::GEO_CHEMISTRY));
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::GEO_ENERGY));
                $keywords = array_merge($keywords, $this->getResearchAspectsPerSubject(VocabularyType::GEOLOGICAL_SETTING));
                $keywords = array_values(array_unique($keywords));

                $researchAspects = $keywords;
                break;
        }

        return $researchAspects;
    }

    private function getDescriptions()
    {
        return new Descriptions(
            abstract: $this->msl_description_abstract,
            methods: $this->msl_description_methods,
            seriesInformation: $this->msl_description_series_information,
            tableOfContents: $this->msl_description_table_of_contents,
            technicalInfo: $this->msl_description_technical_info,
            other: $this->msl_description_other
        );
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'title' => $this->title,
            'doi' => $this->msl_doi,
            'source' => $this->msl_source,
            'portalLink' => config('app.url') . '/data-publication/' . $this->name,
            'name' => $this->name,
            'creators' => CreatorResource::collection($this->msl_creators),
            'descriptions' => new DescriptionResource($this->getDescriptions()),
            'contributors' => ContributorResource::collection($this->msl_contributors),
            'materials' => $this->getMaterials(),
            'researchAspects' => $this->getResearchAspects(),
            'files' => FileResource::collection(array_slice($this->msl_files, 0, 25)),
            'resource_type' => $this->msl_resource_type,
            'resource_type_general' => $this->msl_resource_type_general,
            'publication_year' => $this->msl_publication_year,
            'language' => $this->msl_language,
            'publisher' => $this->msl_publisher,
            'citation' => $this->msl_citation,
            'geojson' => json_decode($this->msl_geojson_featurecollection),
            'surface_area' => $this->msl_surface_area,
            'rightsList' => RightResource::collection($this->msl_rights),
            'alternateIdentifier' => AlternateIdentifierResource::collection($this->msl_alternate_identifiers),
            'fundingReferences' => FundingReferenceResource::collection($this->msl_funding_references),
            'dates' => DateResource::collection($this->msl_dates),
            'sizes' => array_column($this->msl_sizes, 'msl_size'),
            'formats' => array_column($this->msl_formats, 'msl_format'),
            'laboratories' => $this->msl_laboratories,
            'relatedIdentifiers' => RelatedIdentifierResource::collection($this->msl_related_identifiers),
            'subjects' => SubjectResource::collection($this->msl_tags),
            'subdomains' => array_column($this->msl_subdomains, 'msl_subdomain'),
        ];
    }
}
