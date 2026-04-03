<div>
    <div class="w-full">
        @if (isset($title))
            <label for="{{ $sectionName }}"
                class="block mb-2 
                @if (isset($titleBold) && $titleBold) font-bold @endif
                ">
                {{ $title }}
            </label>
        @endif

        <select name="{{ $sectionName }}" id="{{ $sectionName }}"
            class="select form-field-text focus:select-secondary 
            @if ($errors->has($sectionName)) error-highlight-input bg-error-300 
            @else
                bg-white @endif
            
            ">
            <option disabled @if (old($sectionName, '') === '') selected @endif>{{ $placeholder }}</option>
            @foreach ($options as $key => $option)
                <option value="{{ $key }}" @selected(old($sectionName, '') === (string) $key)>
                    {{ $option }}
                </option>
            @endforeach
        </select>
        @if ($errors->has($sectionName))
            <p class="error-highlight"> {{ $errors->first($sectionName) }} </p>
        @endif


    </div>
</div>
