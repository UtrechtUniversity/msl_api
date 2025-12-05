{{-- 
    data    = (array)
    requires $result->getResults(true) entry and input
--}}

<a class="hover-interactive self-center w-9/12 no-underline p-4" href="{{ route('data-publication-detail', ['id' => $dataPublication->name]) }}">
    @if ($dataPublication->title != '')
        <h4 class="text-left">{{  $data->title }}</h4> 
    @else
        <h4 class="text-left italic">- no title found -</h4> 
    @endif

    @if (count($dataPublication->msl_creators) > 0)
        <h5 class="text-left font-medium pt-4">
            @foreach ( $dataPublication->msl_creators as $author )
                {{ $author->getFullName() }} 
                @if(!$loop->last)
                    |
                @endif
            @endforeach
        </h5>
    @else
        <h5 class="text-left font-medium pt-4">- no authors found -</h5>
    @endif

    @if ($dataPublication->msl_publication_year != '')
        <p>{{ $dataPublication->msl_publication_year }}</p>
    @else
        <p>- no publication year found -</p>
    @endif


    @if ($dataPublication->getMainDescription() != '')
        <p class="italic ">{{ Str::limit($dataPublication->getMainDescription(), 295, preserveWords: true) }}</p>
    @else
        <p class="italic ">- no description found -</p>
    @endif
</a>