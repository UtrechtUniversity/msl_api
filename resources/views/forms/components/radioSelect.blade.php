{{-- 
    vars

    $ElementsArray => array(string)
    $sectionName => string, describes the name of the elements for interactions

--}}

<div class="form-control flex flex-col phone:flex-row">
    @foreach ($ElementsArray as $choice)
        <label class="label cursor-pointer flex flex-col gap-4 p-2 phone:w-20 sm:w-28 border sm:border-0 ">
            <span class="label-text text-center">{{ $choice }}</span>
            <input type="radio" name={{ $sectionName }} class="radio checked:bg-secondary-500" checked="false" />
        </label>
    @endforeach
</div>

{{-- <div class="w-full">
    
    @if (isset($title) && $title != '')
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
        form-control 
        flex 
        @if ($asCol)
            flex-col
        @else
            flex-row 
            sm:flex-nowrap
            flex-wrap
        @endif
        
        w-full 
        justify-center
        w-full
        ">
        @foreach ($options as $key => $option)
        <div class="
            flex 
            place-content-center
            items-center
            w-full">
            @if ($infoIconsIds != [])
                <x-ri-information-line id="{{ $infoIconsIds[$key] }}" class="info-icon mx-2"/>
            @endif

            <label class="
                label cursor-pointer 
                flex
                w-full
                @if ($asCol)
                    flex-row
                @else
                    sm:flex-col
                    flex-row
                    sm:w-full
                    max-w-96
                @endif 
                
                gap-4 
                p-2
                justify-between
                
                hover-interactive
                @if ($errors->has($sectionName))
                    error-highlight-input
                    rounded-xl
                @endif
                ">
                <span class="label-text text-primary-900 text-center" value={{ $key }}>{{ $option }}</span>
                <input type="radio" 
                    value={{ $key }} 

                    @if ($sectionName != '')
                        name={{ $sectionName.'[]' }}
                    @endif

                    @if ($ids != [])
                        id={{ $ids[$key] }}
                    @endif
                    class="
                    radio 
                    checked:bg-secondary-500 hover:bg-secondary-500
                    border
                    border-secondary-500
                    
                    " 
                    @if (old($sectionName) == $key && old($sectionName) !== null)
                        checked
                    @endif
                    />
            </label>
        </div>


        @endforeach
    </div>
    @if ($errors->has($sectionName))
        <p class="error-highlight"> {{ $errors->first($sectionName) }} </p>
    @endif
</div> --}}