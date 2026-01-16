
import type { FeatureCollection } from 'geojson'

export type GeoJsonDataPublications = {
    'geojson': FeatureCollection,
    'data_publication': DataPublication
}[]

export type GeoJsonDataPublication = {
    'geojson': FeatureCollection,
    'data_publication': DataPublication
}
export type DataPublication = {
    'title': string,
    'doi': string,
    'source': string,
    'portalLink': string,
    'name': string,
    'creators': [Creator, ...Creator[]],
    'descriptions': { description: string, descriptionType: string }[],
    'contributors': Contributor[],
    'materials': string[],
    'researchAspects': string[],
    'files': File[],
    'resource_type': string,
    'resource_type_general': string,
    'publication_year': string,
    'language': string,
    'publisher': string,
    'citation': string,
    'surface_area': string,
    'rightsList': Rights[],
    'alternateIdentifier': AlternateIdentifier[],
    'fundingReferences': FundingReference[],
    'dates': Date[],
    'sizes': string[],
    'formats': string[],
    'laboratories': string,
    'relatedIdentifiers': RelatedIdentifier[],
    'subjects': Subject[],
    'subdomains': string[],
}

type Creator = {
    "name": string,
    "fullName": string,
    "contributorType": string,
    "nameType": string,
    "givenName": string,
    "familyName": string,
    "nameIdentifiers": {
        "nameIdentifier": string,
        "nameIdentifierScheme": string,
        "nameIdentifierUri": string
    }[],
    "affiliation":
    {
        "name": string,
        "affiliationIdentifier": string,
        "affiliationIdentifierScheme": string,
        "schemeUri": string
    }[]
};

type Contributor = Creator
type File =
    {
        "fileName": string,
        "downloadLink": string,
        "extension": string,
        "isFolder": boolean
    }

type Rights = {
    "rights": string,
    "rightsUri": string,
    "rightsIdentifier": string,
    "rightsIdentifierScheme": string,
    "rightsSchemeUri": string
}
type RelatedIdentifier = {
    "relatedIdentifier": string,
    "relatedIdentifierType": string,
    "relationType": string,
    "relatedMetadataScheme": string,
    "schemeUri": string,
    "schemeType": string,
    "resourceTypeGeneral": string

}

type Subject = {
    "subject": string,
    "schemeUri": string,
    "valueUri": string,
    "subjectScheme": string,
    "classificationCode": string,
    "EPOS_Uris": string[]
}

type Date = {
    "date": string,
    "dateType": string,
    "dateInformation": string
}

type FundingReference = {
    "funderName": string,
    "funderIdentifier": string,
    "funderIdentifierType": string,
    "schemeUri": string,
    "awardNumber": string,
    "awardUri": string,
    "awardTitle": string
}

type AlternateIdentifier = {
    "alternate_identifier": string,
    "alternate_identifier_type": string
}