<?php

namespace App\Mappers\Datacite;

use App\Exceptions\MappingException;
use App\Mappers\MapperInterface;
use App\Models\Ckan\Affiliation;
use App\Models\Ckan\AlternateIdentifier;
use App\Models\Ckan\Contributor;
use App\Models\Ckan\Creator;
use App\Models\Ckan\DataPublication;
use App\Models\Ckan\Date;
use App\Models\Ckan\FundingReference;
use App\Models\Ckan\NameIdentifier;
use App\Models\Ckan\RelatedIdentifier;
use App\Models\Ckan\Right;
use App\Models\Ckan\Tag;

class Datacite4Mapper implements MapperInterface
{
    public function map(array $metadata, DataPublication $dataPublication): DataPublication
    {
        // map all fields
        // start with the identifier to enable usage in logging/exceptions
        $dataPublication = $this->mapIdentifier($metadata, $dataPublication);
        $dataPublication = $this->mapTitles($metadata, $dataPublication);
        $dataPublication = $this->mapDescriptions($metadata, $dataPublication);
        $dataPublication = $this->mapRights($metadata, $dataPublication);
        $dataPublication = $this->mapPublicationYear($metadata, $dataPublication);
        $dataPublication = $this->mapAlternateIdentifiers($metadata, $dataPublication);
        $dataPublication = $this->mapRelatedIdentifiers($metadata, $dataPublication);
        $dataPublication = $this->mapUrl($metadata, $dataPublication);
        $dataPublication = $this->mapFundingReferences($metadata, $dataPublication);
        $dataPublication = $this->mapLanguages($metadata, $dataPublication);
        $dataPublication = $this->mapDates($metadata, $dataPublication);
        $dataPublication = $this->mapPublishers($metadata, $dataPublication);
        $dataPublication = $this->mapCreators($metadata, $dataPublication);
        $dataPublication = $this->mapVersion($metadata, $dataPublication);
        $dataPublication = $this->mapResourceTypes($metadata, $dataPublication);
        $dataPublication = $this->mapContributors($metadata, $dataPublication);
        $dataPublication = $this->mapSizes($metadata, $dataPublication);
        $dataPublication = $this->mapFormats($metadata, $dataPublication);
        $dataPublication = $this->mapSubjects($metadata, $dataPublication);
        $dataPublication = $this->mapGeolocations($metadata, $dataPublication);

        return $dataPublication;
    }

    /**
     * Takes available entries from 'alternateIdentifiers'
     * and adds those to the dataset
     * this is optional
     */
    public function mapAlternateIdentifiers(array $metadata, DataPublication $dataset): DataPublication
    {
        $altIds = $metadata['data']['attributes']['alternateIdentifiers'];

        if ($altIds > 0) {
            foreach ($altIds as $altIdEntry) {
                $alternateIdentifier = new AlternateIdentifier(
                    (isset($altIdEntry['alternateIdentifier']) ? $altIdEntry['alternateIdentifier'] : ''),
                    (isset($altIdEntry['alternateIdentifierType']) ? $altIdEntry['alternateIdentifierType'] : '')
                );

                $dataset->addAlternateIdentifier($alternateIdentifier);
            }
        }

        return $dataset;
    }

    /**
     * Maps the publicationYear of a datacite entry
     * It is a mandatory entry, failure throws exception
     * 
     */
    public function mapPublicationYear(array $metadata, DataPublication $dataset): DataPublication
    {
        $publicationYear = '';

        if (isset($metadata['data']['attributes']['publicationYear'])) {
            $publicationYear = $metadata['data']['attributes']['publicationYear'];
        } else {
            throw new MappingException($dataset->msl_doi.': publicationYear cannot be mapped: does not exist in entry');
        }

        if (strlen($publicationYear) > 0) {
            $dataset->msl_publication_year = $publicationYear;
        } else {
            throw new MappingException($dataset->msl_doi.': publicationYear string empty');
        }

        return $dataset;
    }

    /**
     * Maps the identifier/doi of a datacite entry
     * It is a mandatory entry, failure throws exception
     */
    public function mapIdentifier(array $metadata, DataPublication $dataset): DataPublication
    {
        $identifier = '';

        if (isset($metadata['data']['attributes']['doi'])) {
            $identifier = $metadata['data']['attributes']['doi'];
        } else {
            throw new MappingException('Identifier/doi cannot be mapped: does not exist in entry');
        }

        if (strlen($identifier) > 0) {
            $dataset->msl_doi = $identifier;
        } else {
            throw new MappingException('Identifier/doi string empty');
        }

        return $dataset;
    }

    /**
     * Maps the rights of a datacite entry
     * It is an optional entry
     */
    public function mapRights(array $metadata, DataPublication $dataset): DataPublication
    {
        $rights = $metadata['data']['attributes']['rightsList'];

        if ($rights > 0) {
            foreach ($rights as $right) {
                $right = new Right(
                    (isset($right['rights']) ? $right['rights'] : ''),
                    (isset($right['rightsUri']) ? $right['rightsUri'] : ''),
                    (isset($right['rightsIdentifier']) ? $right['rightsIdentifier'] : ''),
                    (isset($right['rightsIdentifierScheme']) ? $right['rightsIdentifierScheme'] : ''),
                    (isset($right['schemeUri']) ? $right['schemeUri'] : ''),
                );

                $dataset->addRight($right);
            }
        }

        return $dataset;
    }

    /**
     * chooses one description from the datacite entry according to the following priorities
     * if multiple descriptions are available:
     *
     * 'descriptionType' not set
     * no "lang" property set
     * "lang" property set and empty
     * 'lang' set to 'en'
     * 'lang set to 'en-GB'
     * if none apply take first in list
     * 
     * THis is an optional entry
     *
     * ASSUMPTIONS FOR PRIORITIES ARE COMMENTED IN THE FUNCTION
     */
    public function mapDescriptions(array $metadata, DataPublication $dataset): DataPublication
    {
        $descriptions = $metadata['data']['attributes']['descriptions'];

        $dataset->msl_description_abstract = $this->getDescription('Abstract', $descriptions);
        $dataset->msl_description_methods = $this->getDescription('Methods', $descriptions);
        $dataset->msl_description_series_information = $this->getDescription('SeriesInformation', $descriptions);
        $dataset->msl_description_table_of_contents = $this->getDescription('TableOfContents', $descriptions);
        $dataset->msl_description_technical_info = $this->getDescription('TechnicalInfo', $descriptions);
        $dataset->msl_description_other = $this->getDescription('Other', $descriptions);

        return $dataset;
    }

    private function getDescription(string $descriptionType, array $descriptions): string
    {
        $descriptionString = '';
        $descriptionsCandidates = [];

        /////////////////  additional notes
        // -> description is optional, so it can be empty
        // -> "descriptionType" is always present
        /////////////////

        // filter if descriptions with descriptionType are present and collect candidates
        foreach ($descriptions as $description) {
            if (isset($description['descriptionType']) && $description['descriptionType'] == $descriptionType) {
                array_push($descriptionsCandidates, $description);
            }
        }

        if (count($descriptionsCandidates) > 0) {
            return $this->getEntryFilterByLang($descriptionsCandidates)['description'];
        }

        return $descriptionString;
    }

    /**
     * chooses one title from the datacite entry according to the following priorities
     * if multiple titles are available:
     *
     * 'titleType' not set
     * no "lang" property set
     * "lang" property set and empty
     * 'lang' set to 'en'
     * 'lang set to 'en-GB'
     * if none apply take first in list
     *
     * this entry is mandatory
     * 
     * ASSUMPTIONS FOR PRIORITIES ARE COMMENTED IN THE FUNCTION
     */
    public function mapTitles(array $metadata, DataPublication $dataset): DataPublication
    {
        $titles = $metadata['data']['attributes']['titles'];
        $titleSize = count($titles);

        // there MUST be at least one title, otherwise it is an exeption
        if ($titleSize > 0) {

            $titlesCandidates = [];

            /////////////////  additional notes
            // -> multiple 'title' entries without 'titleType' must have unique languages
            // -> 'lang' can be empty
            /////////////////

            if ($titleSize > 1) {
                // filter the titles for the ones which dont have 'titleType', this is the indicator for main title
                foreach ($titles as $title) {
                    if (! isset($title['titleType']) && isset($title['title'])) {
                        array_push($titlesCandidates, $title);
                    }
                }

                $dataset->title = $this->getEntryFilterByLang($titlesCandidates)['title'];

                return $dataset;

            } else {
                // the only title present
                $dataset->title = $metadata['data']['attributes']['titles'][0]['title'];

                return $dataset;
            }

        } else {
            throw new MappingException($dataset->msl_doi.': No title mapped');
        }

        return $dataset;
    }

    /**
     * This function filters an array based on its "lang" entry
     */
    private function getEntryFilterByLang(array $allCandidates): array
    {
        // check if no "lang" property is set
        // THIS ASSUMES THAT THERE CAN BE ONLY ONE ENTRY WITHOUT TITLE
        foreach ($allCandidates as $candidate) {
            if (! isset($candidate['lang'])) {
                return $candidate;
            }
        }

        // "lang" is set but empty
        // THIS ASSUMES THAT THERE CAN BE ONLY ONE ENTRY WITH AN EMPTY LANG
        foreach ($allCandidates as $candidate) {
            if ($candidate['lang'] == '') {
                return $candidate;
            }
        }

        // THIS ASSUMES THAT THERE CAN BE ONLY ONE ENTRY WITH 'en'
        foreach ($allCandidates as $candidate) {
            if ($candidate['lang'] == 'en') {
                return $candidate;
            }
        }

        // THIS ASSUMES THAT THERE CAN BE ONLY ONE ENTRY WITH 'en-GB'
        foreach ($allCandidates as $candidate) {
            if ($candidate['lang'] == 'en-GB') {
                return $candidate;
            }
        }

        // nothing left. Just take the first one
        return $allCandidates[0];
    }

    /**
     * stores the related identifiers to the dataset
     * 
     * this entry is optional
     */
    public function mapRelatedIdentifiers(array $metadata, DataPublication $dataset): DataPublication
    {
        $relatedIdentifiers = $metadata['data']['attributes']['relatedIdentifiers'];
        if (count($relatedIdentifiers) > 0) {
            foreach ($relatedIdentifiers as $relatedIdentifier) {
                $relatedIdentifier = new RelatedIdentifier(
                    (isset($relatedIdentifier['relatedIdentifier']) ? $relatedIdentifier['relatedIdentifier'] : ''),
                    (isset($relatedIdentifier['relatedIdentifierType']) ? $relatedIdentifier['relatedIdentifierType'] : ''),
                    (isset($relatedIdentifier['relationType']) ? $relatedIdentifier['relationType'] : ''),
                    (isset($relatedIdentifier['relatedMetadataScheme']) ? $relatedIdentifier['relatedMetadataScheme'] : ''),
                    (isset($relatedIdentifier['schemeURI']) ? $relatedIdentifier['schemeURI'] : ''),
                    (isset($relatedIdentifier['schemeType']) ? $relatedIdentifier['schemeType'] : ''),
                    (isset($relatedIdentifier['resourceTypeGeneral']) ? $relatedIdentifier['resourceTypeGeneral'] : ''),
                );

                $dataset->addRelatedIdentifier($relatedIdentifier);
            }
        }

        return $dataset;
    }

    /**
     * stores the url to the dataset
     * this is not part of the data-publication but part of the api response
     */
    public function mapUrl(array $metadata, DataPublication $dataset): DataPublication
    {
        $dataset->msl_source = (isset($metadata['data']['attributes']['url']) ? $metadata['data']['attributes']['url'] : throw new MappingException($dataset->msl_doi.': No url mapped'));

        return $dataset;
    }

    /**
     * maps the available funding references
     * fundername is madatory
     */
    public function mapFundingReferences(array $metadata, DataPublication $dataset): DataPublication
    {
        $fundingReferences = $metadata['data']['attributes']['fundingReferences'];

        if ($fundingReferences > 0) {
            foreach ($fundingReferences as $fundingReference) {
                $fundingReferenceInstance = new FundingReference(
                    (isset($fundingReference['funderName']) ? $fundingReference['funderName'] : ''),
                    (isset($fundingReference['funderIdentifier']) ? $fundingReference['funderIdentifier'] : ''),
                    (isset($fundingReference['funderIdentifierType']) ? $fundingReference['funderIdentifierType'] : ''),
                    (isset($fundingReference['schemeURI']) ? $fundingReference['schemeURI'] : ''),
                    (isset($fundingReference['awardNumber']) ? $fundingReference['awardNumber'] : ''),
                    (isset($fundingReference['awardUri']) ? $fundingReference['awardUri'] : ''),
                    (isset($fundingReference['awardTitle']) ? $fundingReference['awardTitle'] : ''),
                );

                $dataset->addFundingReference($fundingReferenceInstance);
            }
        }

        return $dataset;
    }

    /*
     * stores the language to the dataset
     * this entry is optional
     */
    public function mapLanguages(array $metadata, DataPublication $dataset): DataPublication
    {
        $lang = '';

        if (isset($metadata['data']['attributes']['language'])) {
            $lang = $metadata['data']['attributes']['language'];
        }

        $dataset->msl_language = $lang;

        return $dataset;
    }

    /**
     * stores the dates to the dataset
     * this entry is optional
     */
    public function mapDates(array $metadata, DataPublication $dataset): DataPublication
    {
        $allDates = $metadata['data']['attributes']['dates'];

        if (count($allDates) > 0) {
            foreach ($allDates as $date) {
                $date = new Date(
                    (isset($date['date']) ? $date['date'] : ''),
                    (isset($date['dateType']) ? $date['dateType'] : ''),
                    (isset($date['dateInformation']) ? $date['dateInformation'] : ''),
                );

                $dataset->addDate($date);
            }
        }

        return $dataset;
    }

    /**
     * stores the publishers to the dataset
     * this entry is mandatory
     */
    public function mapPublishers(array $metadata, DataPublication $dataset): DataPublication
    {
        if (isset($metadata['data']['attributes']['publisher'])) {
            $publisherEntry = $metadata['data']['attributes']['publisher'];

            if(isset($publisherEntry['name'])) {
                $dataset->msl_publisher = $publisherEntry['name'];
            }

            if(! is_array($publisherEntry)) {
                $dataset->msl_publisher = $publisherEntry;
            }
        } else {
            throw new MappingException($dataset->msl_doi.': No publisher mapped');
        }

        return $dataset;
    }

    /**
     * stores the creators to the dataset
     * this entry is mandatory
     */
    public function mapCreators(array $metadata, DataPublication $dataset)
    {
        $creators = $metadata['data']['attributes']['creators'];

        if (count($creators) > 0) {
            foreach ($creators as $creator) {
                $creatorInstance = new Creator(
                    (isset($creator['name']) ? $creator['name'] : ''),
                    (isset($creator['givenName']) ? $creator['givenName'] : ''),
                    (isset($creator['familyName']) ? $creator['familyName'] : ''),
                    (isset($creator['nameType']) ? $creator['nameType'] : '')
                );

                if (isset($creator['nameIdentifiers']) && $creator['nameIdentifiers'] > 0) {
                    foreach ($creator['nameIdentifiers'] as $nameIdentifierEntry) {
                        $nameIdentifierInst = new NameIdentifier(
                            (isset($nameIdentifierEntry['nameIdentifier']) ? $nameIdentifierEntry['nameIdentifier'] : ''),
                            (isset($nameIdentifierEntry['nameIdentifierScheme']) ? $nameIdentifierEntry['nameIdentifierScheme'] : ''),
                            (isset($nameIdentifierEntry['schemeUri']) ? $nameIdentifierEntry['schemeUri'] : ''),
                        );

                        $creatorInstance->addNameIdentifier($nameIdentifierInst);
                    }

                }

                if (isset($creator['affiliation']) && $creator['affiliation'] > 0) {
                    foreach ($creator['affiliation'] as $affiliationEntry) {
                        if(isset($affiliationEntry['name'])) {
                            $affiliation = new Affiliation(
                                (isset($affiliationEntry['name']) ? $affiliationEntry['name'] : ''),
                                (isset($affiliationEntry['affiliationIdentifier']) ? $affiliationEntry['affiliationIdentifier'] : ''),
                                (isset($affiliationEntry['affiliationIdentifierScheme']) ? $affiliationEntry['affiliationIdentifierScheme'] : ''),
                                (isset($affiliationEntry['schemeUri']) ? $affiliationEntry['schemeUri'] : ''),
                            );
                        } elseif (! is_array($affiliationEntry)) {
                            $affiliation = new Affiliation($affiliationEntry);
                        }

                        $creatorInstance->addAffiliation($affiliation);
                    }

                }
                $dataset->addCreator($creatorInstance);
            }
        }

        return $dataset;
    }

    /**
     * stores the version to the dataset
     * this entry is optional
     */
    public function mapVersion(array $metadata, DataPublication $dataset): DataPublication
    {
        if (isset($metadata['data']['attributes']['version'])) {
            $dataset->msl_datacite_version = (($metadata['data']['attributes']['version']) != null ? $metadata['data']['attributes']['version'] : '');
        }

        return $dataset;
    }

    /**
     * stores the resource type to the dataset
     * this entry is optional
     */
    public function mapResourceTypes(array $metadata, DataPublication $dataset): DataPublication
    {
        if (isset($metadata['data']['attributes']['types'])) {
            $resourceTypeEntry = $metadata['data']['attributes']['types'];

            $dataset->msl_resource_type = (isset($resourceTypeEntry['resourceType']) ? $resourceTypeEntry['resourceType'] : '');
            $dataset->msl_resource_type_general = (isset($resourceTypeEntry['resourceTypeGeneral']) ? $resourceTypeEntry['resourceTypeGeneral'] : '');
        }

        return $dataset;
    }

    /**
     * stores the contributors to the dataset
     * this entry is optional
     */
    public function mapContributors(array $metadata, DataPublication $dataset): DataPublication
    {
        $allContributors = $metadata['data']['attributes']['contributors'];

        if (count($allContributors) > 0) {
            foreach ($allContributors as $contributor) {
                $creatorInstance = new Contributor(
                    (isset($contributor['name']) ? $contributor['name'] : ''),
                    (isset($contributor['contributorType']) ? $contributor['contributorType'] : ''),
                    (isset($contributor['givenName']) ? $contributor['givenName'] : ''),
                    (isset($contributor['familyName']) ? $contributor['familyName'] : ''),
                    (isset($contributor['nameType']) ? $contributor['nameType'] : '')
                );

                if (isset($contributor['nameIdentifiers']) && $contributor['nameIdentifiers'] > 0) {
                    foreach ($contributor['nameIdentifiers'] as $nameIdentifierEntry) {
                        $nameIdentifierInst = new NameIdentifier(
                            (isset($nameIdentifierEntry['nameIdentifier']) ? $nameIdentifierEntry['nameIdentifier'] : ''),
                            (isset($nameIdentifierEntry['nameIdentifierScheme']) ? $nameIdentifierEntry['nameIdentifierScheme'] : ''),
                            (isset($nameIdentifierEntry['schemeUri']) ? $nameIdentifierEntry['schemeUri'] : ''),
                        );

                        $creatorInstance->addNameIdentifier($nameIdentifierInst);
                    }
                }

                if (isset($contributor['affiliation']) && $contributor['affiliation'] > 0) {
                    foreach ($contributor['affiliation'] as $affiliationEntry) {
                        if(isset($affiliationEntry['name'])) {
                            $affiliation = new Affiliation(
                                (isset($affiliationEntry['name']) ? $affiliationEntry['name'] : ''),
                                (isset($affiliationEntry['affiliationIdentifier']) ? $affiliationEntry['affiliationIdentifier'] : ''),
                                (isset($affiliationEntry['affiliationIdentifierScheme']) ? $affiliationEntry['affiliationIdentifierScheme'] : ''),
                                (isset($affiliationEntry['schemeUri']) ? $affiliationEntry['schemeUri'] : ''),
                            );
                        } elseif (! is_array($affiliationEntry)) {
                            $affiliation = new Affiliation($affiliationEntry);
                        }

                        $creatorInstance->addAffiliation($affiliation);
                    }
                }
                $dataset->addContributor($creatorInstance);
            }
        }

        return $dataset;
    }

    /**
     * stores the size to the dataset
     * can be pages, or bytes
     * this entry is optional
     */
    public function mapSizes(array $metadata, DataPublication $dataset): DataPublication
    {
        $sizes = $metadata['data']['attributes']['sizes'];

        if (count($sizes) > 0) {
            foreach ($sizes as $size) {
                $dataset->addSize($size);
            }
        }

        return $dataset;
    }

    /**
     * stores the format to the dataset
     * can be "application/xml" or "text/plain"
     * this entry is optional
     */
    public function mapFormats(array $metadata, DataPublication $dataset): DataPublication
    {
        $formats = $metadata['data']['attributes']['formats'];

        if (count($formats) > 0) {
            foreach ($formats as $format) {
                $dataset->addFormat($format);
            }
        }

        return $dataset;
    }

    public function mapSubjects(array $metadata, DataPublication $dataset): DataPublication
    {
        $subjects = $metadata['data']['attributes']['subjects'];

        if (count($subjects) > 0) {
            foreach ($subjects as $subject) {
                $tag = new Tag(
                    $subject['subject'],
                    (isset($subject['schemeUri']) ? $subject['schemeUri'] : ''),
                    (isset($subject['valueUri']) ? $subject['valueUri'] : ''),
                    (isset($subject['subjectScheme']) ? $subject['subjectScheme'] : ''),
                    (isset($subject['classificationCode']) ? $subject['classificationCode'] : ''),
                );

                $dataset->addTag($tag);
            }
        }

        return $dataset;
    }

    public function mapGeolocations(array $metadata, DataPublication $dataset): DataPublication
    {
        $geoData = $metadata['data']['attributes']['geoLocations'];

        foreach($geoData as $geoEntry) {
            foreach($geoEntry as $key => $value) {
                if($key === 'geoLocationPlace') {
                    $dataset->addGeolocation($value);
                }
            }
        }

        return $dataset;
    }
}
