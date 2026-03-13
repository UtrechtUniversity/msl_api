<div class="flex flex-col items-center w-full">

    @if (isset($title))
        <label for="{{ $sectionName }}"
            class="block mb-2 
            @if (isset($titleBold) && $titleBold) font-bold
                text-left
                w-full @endif
            ">
            {{ $title }}
        </label>
    @endif

    <div
        class="flex flex-col w-96 px-10 py-8
      @if ($errors->has('multiCheckbox')) error-highlight bg-error-300 text-error-700 rounded-md @endif
      ">
        <div
            class="flex-col space-y-2 
      place-content-center h-full
        @if ($errors->has($sectionName)) error-highlight-input
            rounded-xl @endif 
      ">

            @foreach ($options as $key => $option)
                <div class="form-control">
                    <label
                        class="label cursor-pointer 
                            flex
                            w-full
                            flex-row
                            gap-4 
                            p-2
                            justify-between
                            hover-interactive">
                        <span class="label-text text-primary-900 text-left text-wrap" value={{ $key }}
                            name={{ $sectionName . '[]' }}>{{ $option }}</span>
                        <input type="checkbox" value={{ $key }} name={{ $sectionName . '[]' }}
                            class="checkbox 
                        checked:bg-secondary-500 hover:bg-secondary-500
                        border
                        border-secondary-500"
                            @if (is_array(old($sectionName)) && in_array($key, old($sectionName))) checked="checked" @endif />

                    </label>
                    @if ($errors->has($sectionName . '[]') && isset($showErrMess) && $showErrMess)
                        <p class="error-highlight"> {{ $errors->first($sectionName . '[]') }} </p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @if ($errors->has($sectionName))
        <p class="error-highlight"> {{ $errors->first($sectionName) }} </p>
    @endif
</div>
