<div class="self-center join p-4">


    {{-- 
        display aim
        <  1 ... 19 20 21 22 23 ... 30 >
        < 1 2 3 4 5 6 ... 30 >
        < 1 ... 26 27 28 29 30 >

        take icons from our library
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
        <button class="join-item btn"> <x-ri-arrow-left-s-line class="chevron-icon"/> </button>
    </a>

    {{-- if total count is less than the given range
    plus the last and first page
    then just display all --}}
    @if ($count <= $rangeShown + 2)

        @for ($i = 1; $i < $count + 1; $i++)
            <a href="{{ $paginator->url($i) }}">
                <button 
                class="join-item btn">{{ $i }}</button>
            </a>
        @endfor

    @else

        <a href="{{ $paginator->url(1) }}">
            <button 
            class="join-item btn">{{ 1 }}</button>
        </a>

        {{-- if the range is close the first page dont show "..." otherwise show --}}
        @if ( $currentPage - $lowerRange  <  $lowerRange )
            
            <button class="join-item btn">...</button>
            
        @endif

        {{-- show the range --}}
        @for ($i = $lowerRange; $i < $upperRange + 1; $i++)
            {{-- if the count is not equal or over or under the first and last page then show 
            (because we substract and add to a number over/undercount will be the case)--}}
            @if ( !($i <= 1) && !($i >= $count))
                <a href="{{ $paginator->url($i) }}">
                    <button 
                    class="join-item btn">{{ $i }}</button>
                </a>
            @endif
        @endfor

        {{-- if the range is close to the count dont show the "..." otherwise show --}}
        @if ( $currentPage + $RangeUnilateral  <=  $count - $RangeUnilateral )
            
            <button class="join-item btn">...</button>
            
        @endif

        <a href="{{ $paginator->url($count) }}">
            <button 
            class="join-item btn">{{ $count }}</button>
        </a>

    @endif

    <a href="{{ $paginator->nextPageUrl() }}">
        <button class="join-item btn"> <x-ri-arrow-right-s-line class="chevron-icon"/> </button>
    </a>

</div>