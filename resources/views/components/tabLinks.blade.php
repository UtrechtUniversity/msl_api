
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
    
    <div role="tablist" class="tabs tabs-boxed flex flex-row ">

            
        @if (isset($includeIcon))

        
            @foreach ( $routes as $route)

                    <a role="tab" href="{{ $route  }}" class="tab no-underline hover:bg-secondary-100">

                        @if ($includeIcon == 'goBack')
                            <x-ri-arrow-left-line id="" class="goBack-icon"/>
                        @endif
                        
                        {{ array_search($route, $routes) }}
                    
                    </a>

            @endforeach

        @else

            @foreach ( $routes as $route)
                    @if (isset($routeActive) && $routeActive == $route)
                        <a role="tab" href="{{ $route }}" class="tab tab-active no-underline">{{ array_search($route, $routes) }}</a>
                    @else
                        <a role="tab" href="{{ $route  }}" class="tab no-underline hover:bg-secondary-100">{{ array_search($route, $routes) }}</a>
                    @endif
            @endforeach

        @endif


    </div>
</div>