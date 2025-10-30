
{{-- 
    
    $allTabs = [
        description => [
            content => string
            id => string
        ]
    ]
    $checkedElementId = string (the window being highlighted first via ID)
--}}

<div role="tablist" class="
tabs 
tabs-lift
sm:p-4 bg-primary-200 
text-sm
sm:text-base
whitespace-nowrap
">

    @foreach ( $allTabs as $description => $content)
        @if ($content['content']  != '')
            <input type="radio" name="my_tabs_2" role="tab" class="tab hover-interactive p-2 px-4 !text-primary-900" 
            aria-label="{{ $description }}" 
            @if ($content['id'] == $checkedElementId) checked='checked' @endif
            />
            <div role="tabpanel" 
                class="tab-content tabs-div bg-primary-100 
                whitespace-normal">
                <p>{!! $content['content'] !!}</p>
            </div>
        @endif
    @endforeach
</div>