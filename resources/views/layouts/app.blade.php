<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
     
     <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js" integrity="sha512-OFs3W4DIZ5ZkrDhBFtsCP6JXtMEDGmhl0QPlmWYBJay40TT1n3gt2Xuw8Pf/iezgW9CdabjkNChRqozl/YADmg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.css" integrity="sha512-mQ77VzAakzdpWdgfL/lM1ksNy89uFgibRQANsNneSTMD/bj0Y/8+94XMwYhnbzx8eki2hrbPpDm0vD0CiT2lcg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.css" integrity="sha512-6ZCLMiYwTeli2rVh3XAPxy3YoR5fVxGdH/pz+KMCzRY2M65Emgkw00Yqmhh8qLGeYQ3LbVZGdmOX9KUjSKr0TA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-base-100">
    <div id="app">

        <div class="navbar bg-secondary-100 shadow-sm z-10">
            <div class="flex-1">
              <a class="btn btn-ghost text-xl" href="{{ route('home') }}">{{ config('app.name', 'Laravel') }} - Admin</a>
            </div>
            <div class="flex-none">
              <ul class="menu menu-horizontal px-1">

                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li>
                        <details>
                        <summary>Tools</summary>
                        <ul class="bg-base-100 rounded-t-none p-2">
                            <li>
                                <a class="dropdown-item" href="{{ route('convert-keywords') }}">                                                                              
                                    Convert keyword file
                                </a>
                            </li>  

                            <li>
                                <a class="dropdown-item" href="{{ route('convert-excel') }}">                                                                              
                                    Convert excel file
                                </a>
                            </li>  

                            <li>
                                <a class="dropdown-item" href="{{ route('filter-tree') }}">                                                                              
                                    Download filter tree export
                                </a>
                            </li>  

                            <li>                                    
                                <a class="dropdown-item" href="{{ route('uri-labels') }}">                                                                              
                                    Download uri-labels export
                                </a>
                            </li>  

                            <li>
                                <a class="dropdown-item" href="{{ route('view-unmatched-keywords') }}">                                                                              
                                    View unmatched keywords
                                </a>
                            </li>  

                            <li>
                                <a class="dropdown-item" href="{{ route('abstract-matching') }}">                                                                              
                                    Abstract matching
                                </a>
                            </li>  

                            <li>
                                <a class="dropdown-item" href="{{ route('query-generator') }}">                                                                              
                                    Query generator
                                </a>
                            </li>  

                            <li>
                                <a class="dropdown-item" href="{{ route('doi-export') }}">                                                                              
                                    DOI export
                                </a>
                            </li>  

                            <li>
                                <a class="dropdown-item" href="{{ route('geoview') }}">                                                                              
                                    Geoview
                                </a>
                            </li>  

                            <li>
                                <a class="dropdown-item" href="{{ route('geoview-labs') }}">                                                                              
                                    Geoview Labs
                                </a>
                            </li> 
                        </ul>
                        </details>
                    </li>

                    <li>
                        <a class="nav-link" href="{{ route('remove-dataset') }}">{{ __('Remove datasets') }}</a>
                    </li>  

                    <li>
                        <a class="nav-link" href="{{ route('seeders') }}">{{ __('Seeders') }}</a>
                    </li>  

                    <li>
                        <a class="nav-link " href="{{ route('importers') }}">{{ __('Importers') }}</a>    
                    </li>        

                    <li>
                        <details>
                        <summary>Labs</summary>
                        <ul class="bg-base-100 rounded-t-none p-2">
                            <li>
                                <a class="dropdown-item" href="{{ route('import-labdata') }}">                                                                              
                                    Import labdata
                                </a>
                            <li>

                            </li>
                                <a class="dropdown-item" href="{{ route('view-labdata') }}">                                                                              
                                    View labdata
                                </a>
                            </li>
                        </ul>
                        </details>
                    </li>


                    <li>
                        <details>
                        <summary>All actions</summary>
                        <ul class="bg-base-100 rounded-t-none p-2">
                            <li>
                                <a class="dropdown-item" href="{{ route('delete-actions') }}">                                                                              
                                    Deletes
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('imports') }}">                                                                              
                                    Imports
                                </a>
                            </li>
                            
                            <li>
                                <a class="dropdown-item" href="{{ route('source-dataset-identifiers') }}">                                                                              
                                    Source dataset identifiers
                                </a>
                            </li>
                            
                            <li>
                                <a class="dropdown-item" href="{{ route('source-datasets') }}">                                                                              
                                    Source datasets
                                </a>
                            </li>
                            
                            <li>
                                <a class="dropdown-item" href="{{ route('create-actions') }}">                                                                              
                                    Creates
                                </a>
                            </li>
                        </ul>
                        </details>
                    </li>

                    <li>
                        <details>
                        <summary>{{ Auth::user()->name }}</summary>
                        <ul class="bg-base-100 rounded-t-none p-2">
                            <li> <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                        </ul>
                        </details>
                    </li>

                @endguest

              </ul>
            </div>
          </div>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
