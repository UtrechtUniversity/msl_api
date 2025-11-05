<navbar>
    <div class="navbar bg-base-200 w-full z-40">
        <div class="navbar-start">

          <a href="{{ route('index') }}" class="btn btn-ghost" 
          >
            <img 
            src= {{ asset('images/logos/MSL-logo-data-catalogue_1.png') }}
            alt="multi-scale-laboratories-logo"
            class="max-h-full m-auto max-w-fit sm:block hidden">

            <img 
            src= {{ asset('images/logos/MSLsidewaysText.png') }}
            alt="multi-scale-laboratories-logo"
            class="max-h-full object-contain sm:hidden block">
          </a>
        </div>



        <div class="navbar-end w-full">

          <div class="dropdown dropdown-hover relative ">
            <div tabindex="0" role="button" class="btn btn-ghost xl:hidden">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h8m-8 6h16" />
              </svg>
              <h5>Menu</h5>

            </div>

            <ul
              tabindex="0"
              class="menu menu-sm dropdown-content bg-base-100 rounded-box mt-3 w-52 p-2 shadow z-50
              absolute top-9 right-0
              ">
              <li><a class="no-underline" href="{{ route('data-access') }}">Data Access</a></li>
              <li><a class="no-underline" href="{{ route('labs-map') }}">Labs</a></li>
              <li><a class="no-underline" href="{{ route('data-repositories') }}">Data Repositories</a></li>          
              <li>
                  <summary class="menu-title">How to contribute</summary>
                  <ul class="bg-base-100 rounded-t-none p-2 ">
                    <li><a class="no-underline" href="{{ route('contribute-researcher') }}">As a researcher</a></li>
                    <li><a class="no-underline" href="{{ route('contribute-repository') }}">As a repository</a></li>
                    <li><a class="no-underline" href="{{ route('contribute-laboratory') }}">As a laboratory</a></li>
                    <li><a class="no-underline" href="{{ route('contribute-project') }}">With a proposal/project</a></li>
                    <summary class="menu-title">Surveys</summary>
                    <li><a class="no-underline" href="{{ route('contribute-select-scenario') }}">Data Tooling - Survey</a></li>
                  </ul>
              </li>
              <li>
                  <summary class="menu-title">Vocabularies</summary>
                  <ul class="bg-base-100 rounded-t-none p-2 z-20">
                    <li><a class="no-underline" href="{{ route('keyword-selector') }}">Keyword selector</a></li>
                  </ul>
              </li>
              <li><a class="no-underline" href="{{ route('about') }}">About MSL</a></li>
              <li><a class="no-underline" href="https://www.epos-eu.org/dataportal" target="_blank">EPOS central data portal</a></li>
            </ul>
          </div>

          <div class="hidden xl:flex z-50">

            <div class="flex flex-1 justify-end px-2">
              <div class="flex items-stretch">
  
                <a class="btn btn-ghost rounded-btn " href="{{ route('data-access') }}">Data Access</a>
                <a class="btn btn-ghost rounded-btn " href="{{ route('labs-map') }}">Labs</a>
                <a class="btn btn-ghost rounded-btn " href="{{ route('data-repositories') }}">Data Repositories</a>
  
                <div class="dropdown dropdown-hover">
                  <div tabindex="0" role="button" class="btn btn-ghost rounded-btn">
                    How to Contribute
                    <x-ri-arrow-down-s-line class="chevron-icon"/>
                  </div>
                  <ul
                    tabindex="0"
                    class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li><a class="no-underline" href="{{ route('contribute-researcher') }}">As a researcher</a></li>
                    <li><a class="no-underline" href="{{ route('contribute-repository') }}">As a repository</a></li>
                    <li><a class="no-underline" href="{{ route('contribute-laboratory') }}">As a laboratory</a></li>
                    <li><a class="no-underline" href="{{ route('contribute-project') }}">With a proposal/project</a></li>

                    <summary class="menu-title">Surveys</summary>
                    <li><a class="no-underline" href="{{ route('contribute-select-scenario') }}">Data Tooling - Survey</a></li>
                  </ul>
                </div>
  
                <div class="dropdown dropdown-hover">
                  <div tabindex="0" role="button" class="btn btn-ghost rounded-btn">
                    Vocabularies
                    <x-ri-arrow-down-s-line class="chevron-icon"/>
                  </div>
                  <ul
                    tabindex="0"
                    class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li><a class="no-underline" href="{{ route('keyword-selector') }}">Keyword selector</a></li>
  
                  </ul>
                </div>
  
                <a class="btn btn-ghost rounded-btn " href="{{ route('about') }}">About MSL</a>
                <a class="btn btn-ghost rounded-btn " href="https://www.epos-eu.org/dataportal" target="_blank">EPOS central data portal</a>
  
  
              </div>
            </div>
          </div>


        </div>
      </div>
</navbar>