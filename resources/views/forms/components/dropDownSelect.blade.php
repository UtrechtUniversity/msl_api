{{-- 
    vars

    $ElementsArray => array(string)
    $sectionName => string, describes the name of the elements for interactions
    $placeholder => string
    $title => title for the text-field

--}}


<div class="w-full">
    @if (isset($title))
        <label for="{{ $sectionName }}" class="block mb-2 ">{{ $title }}</label>    
    @endif

    <select 
        name="{{ $sectionName }}"
        id="{{ $sectionName }}"
        class="select form-field-text focus:select-secondary 
        {{-- @error('{{ $sectionName }}') error-highlight-input bg-error-300 @else bg-white @enderror --}}
        @if ($errors->has($sectionName))
            error-highlight-input bg-error-300
        @else
            bg-white
        @endif
        
        ">
        <option disabled selected>{{ $placeholder }}</option>
        {{-- from https://laravel.com/docs/11.x/blade#additional-attributes --}}
            @foreach ($ElementsArray as $subject)
                <option value="{{ $subject }}" @selected(old('{{ $sectionName }}') == $subject)>
                    {{ $subject }}
                </option>
            @endforeach
    </select>

    @if ($errors->has($sectionName))        
        <p class="error-highlight"> {{ $errors->first($sectionName) }} </p>
    @endif
</div>
