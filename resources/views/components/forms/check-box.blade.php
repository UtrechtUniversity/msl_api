
<div class="flex flex-col items-center w-full">

    @if ($title != '')
        <label for="{{ $sectionName  }}" 
            class="block mb-2 
            @if (isset($titleBold) && $titleBold)
                font-bold
                text-left
                w-full
            @endif
            ">
            {{ $title }}
        </label>
    @endif

    <div class="flex flex-col w-full
      @if ($errors->has('multiCheckbox'))
              error-highlight bg-error-300 text-error-700 rounded-md
          @endif
      ">
      <div class="flex-col space-y-2 
      place-content-center h-full w-full
        @if ($errors->has($sectionName))
            error-highlight-input
            rounded-xl
        @endif 
      ">
  
      @foreach ( $options as $key => $option)
          <div class="form-control w-full">
              <label class="
                    w-full
                    label p-2 
                    text-secondary-900
                    hover-interactive">
                  <span 
                    class="pr-4 text-sm w-full"

                    value={{ $key }}

                    @if ($sectionName != '')
                        name={{ $sectionName.'[]' }}
                    @endif
                    >
                        {{ $option }}
                    </span>

                  <input type="checkbox"

                  value={{ $key }} 
                  
                  @if ($sectionName != '')
                    name={{ $sectionName.'[]' }}
                  @endif

                  @if ($ids != [])
                    id={{ $ids[$key] }}
                  @endif

                  class="checkbox checkbox-secondary checkbox-md rounded-sm border" 

                  @if (is_array(old( $sectionName )) && in_array($key, old( $sectionName )) )
                      checked="checked"
                  @endif

                  />
                  
              </label>
              @if ($errors->has($sectionName.'[]') && isset($showErrMess) && $showErrMess)
                  <p class="error-highlight"> {{ $errors->first($sectionName.'[]') }} </p>
              @endif
          </div>
      @endforeach
  </div>
  </div>
  @if ($errors->has($sectionName))
  <p class="error-highlight"> {{ $errors->first($sectionName) }} </p>
@endif
</div>


