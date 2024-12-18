{{-- 
    data    = (array)
--}}

<a class="self-center w-9/12 no-underline" href="{{ route('data-publication-detail', ['id' => $data['id']]) }}">

<div class="hover:bg-secondary-100 ">


    <div class="p-4"> 
        
        @if (isset( $data['title']))
            <h4 class="text-left">{{  $data['title'] }}</h4> 
        @endif

        @if (isset($data['msl_authors']))
            <h5 class="text-left font-medium pt-4">
                @foreach ( $data['msl_authors'] as $authorKey => $author )
                    {{ $author["msl_author_name"] }} 
                    {{-- a little divider between names --}}
                    @if (sizeof($data['msl_authors']) -1 != $authorKey )
                        |
                    @endif
                @endforeach
            </h5>
        @endif

        @if (isset($data['msl_publication_year']))
            <p>{{ $data['msl_publication_year'] }}</p>
        @endif


        @if (isset($data['notes']))
            {{-- https://laravel.com/docs/11.x/strings#method-str-limit --}}
            <p class="italic ">{{ Str::limit($data['notes'], 295, preserveWords: true) }}</p>
        @endif

        

    </div>


</div>    

</a>