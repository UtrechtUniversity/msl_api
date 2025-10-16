<div class="self-center join p-4 rounded-xl">


    {{-- 
        display aim
        <  1 ... 19 20 21 22 23 ... 30 >
        < 1 2 3 4 5 6 ... 30 >
        < 1 ... 26 27 28 29 30 >
    --}}
    @php
        $RangeUnilateral = 2;

        $rangeShown = $RangeUnilateral*2 + 1;

        $count = $paginator->lastPage();
        $currentPage = $paginator->currentPage();

        $lowerRange= $currentPage - $RangeUnilateral;
        $upperRange= $currentPage + $RangeUnilateral;
    @endphp


    <a href="{{ $paginator->previousPageUrl() }}">
        <button class="pagination-button pagination-button-last-left"> <x-ri-arrow-left-s-line class="chevron-icon"/> </button>
    </a>

    {{-- if total count is less than the given range
    plus the last and first page
    then just display all --}}
    @if ($count <= $rangeShown + 2)

        @for ($i = 1; $i < $count + 1; $i++)

            @if ($i == $currentPage)
                <a href="{{ $paginator->url($i) }}">
                    <button 
                    class="pagination-button pagination-button-active-page">{{ $i }}</button>
                </a>
            @else
                <a href="{{ $paginator->url($i) }}">
                    <button 
                    class="pagination-button">{{ $i }}</button>
                </a>
            @endif

        @endfor

    @else

        @if (1 == $currentPage)
            <a href="{{ $paginator->url(1) }}">
                <button 
                class="pagination-button pagination-button-active-page">{{ 1 }}</button>
            </a>
        @else
            <a href="{{ $paginator->url(1) }}">
                <button 
                class="pagination-button">{{ 1 }}</button>
            </a>
        @endif

        {{-- if the range is close the first page dont show "..." otherwise show --}}
        @if ( $currentPage - $lowerRange  <  $lowerRange )
            
            <button class="pagination-button ">...</button>
            
        @endif

        {{-- show the range --}}
        @for ($i = $lowerRange; $i < $upperRange + 1; $i++)
            {{-- if the count is not equal or over or under the first and last page then show 
            (because we substract and add to a number over/undercount will be the case)--}}
            @if ( !($i <= 1) && !($i >= $count))

                @if ($i == $currentPage)
                    <a href="{{ $paginator->url($i) }}">
                        <button 
                        class="pagination-button pagination-button-active-page">{{ $i }}</button>
                    </a>
                @else
                    <a href="{{ $paginator->url($i) }}">
                        <button 
                        class="pagination-button">{{ $i }}</button>
                    </a>
                @endif

            @endif

        @endfor

        {{-- if the range is close to the count dont show the "..." otherwise show --}}
        @if ( $currentPage + $RangeUnilateral  <=  $count - $RangeUnilateral )
            
            <button class="pagination-button">...</button>
            
        @endif

        @if ($count == $currentPage)
            <a href="{{ $paginator->url($count) }}">
                <button 
                class="pagination-button pagination-button-active-page">{{ $count }}</button>
            </a>
        @else
            <a href="{{ $paginator->url($count) }}">
                <button 
                class="pagination-button">{{ $count }}</button>
            </a>
        @endif


    @endif

    <a href="{{ $paginator->nextPageUrl() }}">
        <button class="pagination-button pagination-button-last-right"> <x-ri-arrow-right-s-line class="chevron-icon"/> </button>
    </a>

</div>