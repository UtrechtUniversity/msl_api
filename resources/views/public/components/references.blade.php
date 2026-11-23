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
    $relatedIdType = $relatedidentifier->msl_related_identifier_type;
    $normalizedRelatedIdValue = App\Enums\Schemes\IdSchemes\RelatedIdScheme::tryFromScheme(
        $relatedIdType,
    )->getNormalizedValue($relatedidentifier->msl_related_identifier);

@endphp
@include('public.components.list-views.table-list', [
    'entries' => [
        'Identifier' => $normalizedRelatedIdValue,
        'Identifier Type' => $relatedIdType,
        'Relation Type' => $relatedidentifier->msl_related_identifier_relation_type,
    ],
    'withKeys' => true,
])
