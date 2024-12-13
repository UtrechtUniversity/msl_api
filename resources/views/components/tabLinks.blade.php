
{{-- 
Input

categoryName    = string - title of the tab group
routes          = array ('Name' => "route")
routeActive     = string - current active route

 
--}}


<div class="flex flex-col justify-center items-center px-10 py-5 ">
    @if (isset($categoryName))
        <h5 class="pb-2">{{ $categoryName }}</h5>
    @endif
    
    <div role="tablist" class="tabs tabs-boxed">
        @foreach ( $routes as $route)

                @if (isset($routeActive) && $routeActive == $route)
                    <a role="tab" href="{{ $route }}" class="tab tab-active no-underline">{{ array_search($route, $routes) }}</a>
                @else
                    <a role="tab" href="{{ $route  }}" class="tab no-underline">{{ array_search($route, $routes) }}</a>
                @endif


        @endforeach
    </div>
</div>