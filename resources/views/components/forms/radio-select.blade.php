{{-- 
    vars

    $ElementsArray => array(string)
    $sectionName => string, describes the name of the elements for interactions

--}}

<div>
    @if (isset($title))
        <label for="{{ $sectionName }}"
            class="block mb-2 
            @if (isset($titleBold) && $titleBold) font-bold @endif
            ">
            {{ $title }}
        </label>
    @endif
    <div
        class="
        form-control flex flex-col sm:flex-row w-full justify-center
        h-full
        p-6
        
        ">
        @foreach ($options as $key => $option)
            <label
                class="
                label cursor-pointer flex flex-col justify-between 
                gap-4 p-2 mx-2 sm:w-28 
                hover-interactive
                rounded-xl
                @if ($errors->has($sectionName)) error-highlight-input @endif
                ">
                <span class="label-text text-center text-wrap" value={{ $key }}>{{ $option }}</span>
                <div>
                    <input type="radio" value={{ $key }} name={{ $sectionName }}
                        class="     radio 
                                checked:bg-secondary-500 hover:bg-secondary-500
                                border
                                border-secondary-500"
                        @if (old($sectionName) == $key && old($sectionName) !== null) checked @endif />
                </div>

            </label>
        @endforeach
    </div>
    @if ($errors->has($sectionName))
        <p class="error-highlight"> {{ $errors->first($sectionName) }} </p>
    @endif
</div>
