
{{-- 
Input

categoryName    = string - title of the tab group
routes          = array ('Name' => "route")
routeActive     = string - current active route
includeIcon    = string - to select which icon

 
--}}


<div class="flex flex-col justify-center items-center px-10 py-5 ">

    @if (isset($categoryName))
        <h5 class="pb-2">{{ $categoryName }}</h5>
    @endif
    
    <div role="tablist" class="tabs tabs-box tabs-md flex flex-row bg-primary-200 justify-around">

            @foreach ($routes as $routeKey => $route)
                    @if (isset($routeActive) && $routeActive == $route)
                        <a role="tab" href="{{ $route }}" class="tab tab-active w-full sm:w-20 hover-interactive !text-primary-900">{{ $routeKey }}</a>
                    @else
                        <a role="tab" href="{{ $route  }}" class="tab w-full sm:w-20 hover-interactive !text-primary-800">{{ $routeKey }}</a>
                    @endif
            @endforeach

    </div>
</div>