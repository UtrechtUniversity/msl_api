{{-- 
    data    = (array)
--}}

<a class="hover-interactive self-center w-9/12 no-underline p-4" href="{{ route('data-publication-detail', ['id' => $data['id']]) }}">
    @if (isset( $data['title']))
        <h4 class="text-left">{{  $data['title'] }}</h4> 
    @endif

    @if (isset($data['msl_creators']))
        <h5 class="text-left font-medium pt-4">
            @foreach ( $data['msl_creators'] as $authorKey => $author )
                {{ $author["msl_creator_family_name"]}} {{ $author["msl_creator_given_name"] }} 
                {{-- a little divider between names --}}
                    @if (sizeof($data['msl_creators']) -1 != $authorKey )
                        |
                    @endif
            @endforeach
        </h5>
    @endif

    @if (isset($data['msl_publication_year']))
        <p>{{ $data['msl_publication_year'] }}</p>
    @endif


    @if (isset($data['msl_description_abstract_annotated']))
        {{-- https://laravel.com/docs/11.x/strings#method-str-limit --}}
        @foreach (
                [
                "msl_description_abstract_annotated",
                "msl_description_other",
                "msl_description_table_of_contents",
                "msl_description_methods",
                "msl_description_series_information",
                "msl_description_technical_info"]
            as $entry)
            @if ($data[$entry] != '')
                <p class="italic ">{{ Str::limit($data['msl_description_abstract_annotated'], 295, preserveWords: true) }}</p>
                @break
            @endif
        @endforeach
    @endif
</a>