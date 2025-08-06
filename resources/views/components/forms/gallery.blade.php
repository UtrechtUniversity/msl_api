<div class="w-full">
    @if (isset($title))
        <label for="{{ $sectionName  }}" 
            class="block mb-2 
            @if (isset($titleBold) && $titleBold)
                font-bold
            @endif
            ">
            {{ $title }}
        </label>
    @endif

    <div class="
        flex 
        flex-wrap">
        @foreach ($images as $key => $image)
            <div class="
                w-1/2
                flex flex-col 
                justify-center
                content-between
                justify-between
                p-4
                ">
                    <img 
                        src= {{ asset($image) }}
                        alt=""
                        class="max-w-96 object-contain"/>

                    <p class="h-full">
                       {{ $key + 1 }}: {{ $descriptions[$key] }}
                    </p>
            </div>
        @endforeach

        
    </div>


    {{-- <div class="flex flex-wrap">
        <div class="w-full md:w-1/2 lg:w-1/3 p-4">
            <div class="h-32 md:h-64 flex items-center justify-center">
                Box 1
            </div>
        </div>
    </div> --}}

</div>