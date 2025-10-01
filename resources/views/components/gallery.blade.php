<div class="w-full">
    @if (isset($title))
        <p
            class="block mb-2 
            @if (isset($titleBold) && $titleBold)
                font-bold
            @endif
            ">
            {{ $title }}
        </p>
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

                        @if (isset($descriptions[$key]))
                            <p class="h-full">
                                {{ $key + 1 }}: {{ $descriptions[$key] }}
                            </p>
                        @endif

            </div>
        @endforeach

        
    </div>

</div>