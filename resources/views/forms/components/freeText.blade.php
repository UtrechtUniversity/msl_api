{{-- 
vars

$sectionName => string, describes the name of the elements for interactions
$placeholder => string
$title => title for the text-field
--}}
@props(['sectionName'])
<div class="w-full">
    @if (isset($title))
        <label for="{{ $sectionName  }}" class="block mb-2 ">{{ $title }}</label>
    @endif


    @if (isset($textBlock) && $textBlock)
        <textarea type="{{ $sectionName  }}" id="{{ $sectionName  }}" name="{{ $sectionName  }}" 
        class="
        h-64
        form-field-text 
        @if ($errors->has($sectionName))
            error-highlight-input
        @endif" 
        placeholder="{{ $placeholder }}"
        rows="6" 
        >{{ old($sectionName) }}</textarea>
    @else
        <input type="{{ $sectionName  }}" id="{{ $sectionName  }}" name="{{ $sectionName  }}" 
        class="
        form-field-text 
        @if ($errors->has($sectionName))
            error-highlight-input
        @endif" 
        placeholder="{{ $placeholder }}"
        value="{{ old($sectionName) }}"
        >
    @endif

    {{-- @error('{{ $sectionName  }}')
        <p class="error-highlight"> {{ $message }} </p>
    @enderror --}}

    {{-- why like this
    https://github.com/laravel/framework/issues/31975 --}}
    @if ($errors->has($sectionName))
        <p class="error-highlight"> {{ $errors->first($sectionName) }} </p>
    @endif
</div>

