{{-- 
    data    = (array)
    requires $result->getResults(true) entry and input
--}}

<a class="hover-interactive self-center w-9/12 no-underline p-4" href="{{ route('data-publication-detail', ['id' => $data->name]) }}">
    @if ($data->title != '')
        <h4 class="text-left">{{  $data->title }}</h4> 
    @else
        <h4 class="text-left italic">- no title found -</h4> 
    @endif

    @if (sizeof($data->msl_creators) > 0)
        <h5 class="text-left font-medium pt-4">
            @foreach ( $data->msl_creators as $authorKey => $author )
                {{ $author->getFullName() }} 
                    @if (sizeof($data->msl_creators) -1 != $authorKey )
                        |
                    @endif
            @endforeach
        </h5>
    @else
        <h5 class="text-left font-medium pt-4">- no authors found -</h5>
    @endif

    @if ($data->msl_publication_year != '')
        <p>{{ $data->msl_publication_year }}</p>
    @else
        <p>- no publication year found -</p>
    @endif


    @if ($data->getMainDescription() != '')
        <p class="italic ">{{ Str::limit($data->getMainDescription(), 295, preserveWords: true) }}</p>
    @else
        <p class="italic ">- no description found -</p>
    @endif
</a>