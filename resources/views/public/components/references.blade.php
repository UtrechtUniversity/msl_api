{{-- 
Input

 identifier =  {
    schemeUri = string,
    schemeType = string,
    relationType = string,
    relatedIdentifier = string,
    relatedIdentifierType = string,
    relatedMetadataScheme = string
    }
--}}

@php
    $dataPublicationList = [];
    $relatedIdType = $relatedidentifier->msl_related_identifier_type;
    $relatedIdValue = $relatedidentifier->msl_related_identifier;

    switch ($relatedIdType) {
        case 'DOI':
            $updatedRelatedId = str_starts_with($relatedIdValue, 'http')
                ? $relatedIdValue
                : 'https://doi.org/' . $relatedIdValue;
            break;
        case 'URL':
        default:
            $updatedRelatedId = $relatedIdValue;
            break;
    }
    $dataPublicationList[] = $relatedIdType;
    $dataPublicationList[] = $relatedIdType;

@endphp
@include('public.components.list-views.table-list', [
    'entries' => [
        'Identifier' => $updatedRelatedId,
        'Identifier Type' => $relatedIdType,
        'Relation Type' => $relatedidentifier->msl_related_identifier_relation_type,
    ],
    'withKeys' => true,
])
