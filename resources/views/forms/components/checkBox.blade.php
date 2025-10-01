{{-- 
    vars

    $ElementsArray => array(string)
    $sectionName => string, describes the name of the elements for interactions
    $checked => boolean
    $horizontal => boolean, should multiple elements arranged horizontal or vertical

--}}


<div class="w-full flex-col space-y-2 
    place-content-center h-full">

    @foreach ( $ElementsArray as $element)
        <div class="form-control">
            <label class="cursor-pointer label p-2
             hover:bg-secondary-100 hover:rounded-lg hover:text-secondary-900">
                <span class=" pr-4 text-sm">{{ $element }}</span>
                <input type="checkbox" 
                name="{{ $sectionName }}"
                class="checkbox checkbox-secondary checkbox-md
                        @if ($errors->has($sectionName))
                            error-highlight-input
                        @endif 
                " 
                @if (isset($checked) && $checked || old( $sectionName ) )
                    checked="checked"
                @endif
                />
                
            </label>
            @if ($errors->has($sectionName) && isset($showErrMess) && $showErrMess)
                <p class="error-highlight"> {{ $errors->first($sectionName) }} </p>
            @endif
        </div>
    @endforeach
</div>


