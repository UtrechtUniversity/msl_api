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
            <label class="  label cursor-pointer 
                            flex
                            w-full
                            flex-row
                            gap-4 
                            p-2
                            justify-between
                            hover-interactive">
                <span class="label-text text-primary-900 text-center">{{ $element }}</span>
                <input type="checkbox" 
                name="{{ $sectionName }}"
                class="     radio 
                            checked:bg-secondary-500 hover:bg-secondary-500
                            border
                            border-secondary-500
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

