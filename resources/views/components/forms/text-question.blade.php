{{-- 
vars

$sectionName => string, describes the name of the elements for interactions
$placeholder => string
$title => title for the text-field
--}}

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


    @if (isset($textBlock) && $textBlock)
        <textarea type="{{ $sectionName  }}" id="{{ $sectionName  }}" name="{{ $sectionName  }}" 
        class="
        h-28
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
    
    @if ($errors->has($sectionName))
        <p class="error-highlight"> {{ $errors->first($sectionName) }} </p>
    @endif
</div>