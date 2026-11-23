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
            $dataPublicationList[] = str_starts_with($relatedIdValue, 'http')
                ? $relatedIdValue
                : 'https://doi.org/' . $relatedIdValue;
            break;
        case 'URL':
        default:
            $dataPublicationList[] = $relatedIdValue;
            break;
    }
    $dataPublicationList[] = $relatedIdType;

    // if ($relatedidentifier->msl_related_identifier_type == 'DOI') {
    //     $relatedIdentifierValue = $relatedidentifier->msl_related_identifier;
    //     $dataPublicationList[] = str_starts_with($relatedIdentifierValue, 'http')
    //         ? $relatedIdentifierValue
    //         : 'https://doi.org/' . $relatedIdentifierValue;
    // } else {
    //     if ($relatedidentifier->msl_related_identifier_type != '') {
    //         $dataPublicationList[] = $relatedidentifier->msl_related_identifier_type;
    //     }

    //     if ($relatedidentifier->msl_related_identifier_relation_type != '') {
    //         $dataPublicationList[] = $relatedidentifier->msl_related_identifier_relation_type;
    //     }
    // }

@endphp
@include('public.components.list-views.table-list', [
    'entries' => $dataPublicationList,
    'withKeys' => false,
])
