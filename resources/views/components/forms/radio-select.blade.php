{{-- 
    vars

    $ElementsArray => array(string)
    $sectionName => string, describes the name of the elements for interactions

--}}

<div>
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
        form-control flex flex-col phone:flex-row w-full justify-center

        ">   
        @foreach ($options as $key => $option)
            <label class="
                label cursor-pointer flex flex-col gap-4 p-2 mx-2 phone:w-20 sm:w-28 border sm:border-0 
                @if ($errors->has($sectionName))
                    error-highlight-input
                    rounded-xl
                @endif
                ">
                <span class="label-text text-center" value={{ $key }}>{{ $option }}</span>
                <input type="radio" 
                    value={{ $key }} 
                    name={{ $sectionName }} 
                    class="radio checked:bg-secondary-500" 
                    @if (old($sectionName) == $key && old($sectionName) !== null)
                        checked
                    @endif
                    />
            </label>
        @endforeach
    </div>
    @if ($errors->has($sectionName))
        <p class="error-highlight"> {{ $errors->first($sectionName) }} </p>
    @endif
</div>
