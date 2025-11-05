<navbar>
    <div class="navbar bg-base-200 w-full  ">
        <div class="navbar-start w-fit">

          <a href="{{ route('index') }}" class="btn btn-ghost " 
          >
            <img 
            src= {{ asset('images/logos/MSL-logo-data-catalogue_1.png') }}
            alt="multi-scale-laboratories-logo"
            class="max-h-full  max-w-fit min-[1100px]:block hidden">

            <img 
            src= {{ asset('images/logos/MSLsidewaysText.png') }}
            alt="multi-scale-laboratories-logo"
            class="max-h-full object-contain min-[1100px]:hidden block">
            
          </a>
        </div>

        @php
            $allLinks = [
              'Data Access' => route('data-access'),
              'Labs' =>  route('labs-map'),
              'Data Repositories' => route('data-repositories'),
              'How to contribute' => [
                'As a Researcher' => route('contribute-researcher'),
                'As a Repository' => route('contribute-repository'),
                'As a Laboratory' => route('contribute-laboratory'),
                'Surveys' => 'divider',
                'Data Tooling - Survey' => route('contribute-select-scenario')
              ],
              'Vocabularies' => [
                'Keyword Selector' => route('keyword-selector')
              ],
              'About MSL' => route('about'),
              'EPOS Portal' => "https://www.epos-eu.org/dataportal"
            ]
        @endphp


        <div class="navbar-end w-full">

          {{-- min nav --}}
          <div class="dropdown dropdown-hover relative px-4">

            <div tabindex="0" role="button" class="btn btn-ghost min-[810px]:hidden">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 mr-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h8m-8 6h16" />
              </svg>
              <h3 class="align-top">Menu</h3>
            </div>

            <ul
              tabindex="0"
              class="menu menu-md dropdown-content bg-base-100 rounded-box mt-3 w-52 px-2 shadow z-10
              absolute top-6 right-0
              ">
              @foreach ( $allLinks as $name => $linkOrArray)
                @if (is_array($linkOrArray))
                  <summary class="menu-title px-4 ">{{ $name }}</summary>
                  <ul class="bg-base-100 rounded-t-none p-2 z-20">
                    @foreach ($linkOrArray as $name => $link)

                      @if ($link == 'divider')
                        <summary class="menu-title">{{ $name }}</summary>
                      @else
                          <li><a class="nav-button-sub" href="{{ $link }}">{{ $name }}</a></li>
                      @endif

                    @endforeach
                  </ul>
                @else
                  <li><a class="nav-button-sub" href="{{ $linkOrArray }}">{{ $name }}</a></li>
                @endif
              @endforeach       
            </ul>

          </div>

          <div class="hidden min-[810px]:flex pl-4">
            <div class="flex justify-end
              divide-x-2 divide-primary-100
              lg:divide-x-0 divide-primary-100
              ">
                @foreach ($allLinks as $name => $linkOrArray)
                  @if(is_array($linkOrArray))
                    <div class="dropdown dropdown-hover">
                      <div tabindex="0" role="button" class="nav-button">
                        {{  $name }}
                        <x-ri-arrow-down-s-line class="chevron-icon"/>
                      </div>
                      <ul
                      tabindex="0"
                      class="menu dropdown-content bg-base-100 rounded-box z-1 w-52 p-2 shadow">
                        @foreach ($linkOrArray as $name => $link)
                            @if ($link == 'divider')
                              <summary class="menu-title">{{ $name }}</summary>
                            @else
                              <li><a class="nav-button-sub" href="{{ $link }}">{{ $name }}</a></li>
                            @endif
                        @endforeach
                    </ul>

                    </div>
                  @else
                    <a class="nav-button" href="{{ $linkOrArray }}">{{ $name }}</a>
                  @endif
                @endforeach
            </div>
          </div>


        </div>
      </div>
</navbar>