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
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
     
     <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js" integrity="sha512-OFs3W4DIZ5ZkrDhBFtsCP6JXtMEDGmhl0QPlmWYBJay40TT1n3gt2Xuw8Pf/iezgW9CdabjkNChRqozl/YADmg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.css" integrity="sha512-mQ77VzAakzdpWdgfL/lM1ksNy89uFgibRQANsNneSTMD/bj0Y/8+94XMwYhnbzx8eki2hrbPpDm0vD0CiT2lcg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.css" integrity="sha512-6ZCLMiYwTeli2rVh3XAPxy3YoR5fVxGdH/pz+KMCzRY2M65Emgkw00Yqmhh8qLGeYQ3LbVZGdmOX9KUjSKr0TA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
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
                        	<li class="nav-item dropdown">
                            	<a id="navbarDropdownTools" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Tools
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownTools">
                                    <a class="dropdown-item" href="{{ route('convert-keywords') }}">                                                                              
                                        Convert keyword file
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('convert-excel') }}">                                                                              
                                        Convert excel file
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('filter-tree') }}">                                                                              
                                        Download filter tree export
                                    </a>
                                                                        
                                    <a class="dropdown-item" href="{{ route('uri-labels') }}">                                                                              
                                        Download uri-labels export
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('view-unmatched-keywords') }}">                                                                              
                                        View unmatched keywords
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('abstract-matching') }}">                                                                              
                                        Abstract matching
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('query-generator') }}">                                                                              
                                        Query generator
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('doi-export') }}">                                                                              
                                        DOI export
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('geoview') }}">                                                                              
                                        Geoview
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('geoview-labs') }}">                                                                              
                                        Geoview Labs
                                    </a>
                                </div>
                            </li>
                        
                        	<li class="nav-item">
                                <a class="nav-link" href="{{ route('remove-dataset') }}">{{ __('Remove datasets') }}</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('seeders') }}">{{ __('Seeders') }}</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('importers') }}">{{ __('Importers') }}</a>
                            </li>
                            
                            <li class="nav-item dropdown">
                            	<a id="navbarDropdownActions" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Labs
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownActions">
                                    <a class="dropdown-item" href="{{ route('import-labdata') }}">                                                                              
                                        Import labdata
                                    </a>
                                    <a class="dropdown-item" href="{{ route('view-labdata') }}">                                                                              
                                        View labdata
                                    </a>
                                </div>
                            </li>                           
                            
                            <li class="nav-item dropdown">
                            	<a id="navbarDropdownActions" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    All actions
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownActions">
                                    <a class="dropdown-item" href="{{ route('delete-actions') }}">                                                                              
                                        Deletes
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('imports') }}">                                                                              
                                        Imports
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('source-dataset-identifiers') }}">                                                                              
                                        Source dataset identifiers
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('source-datasets') }}">                                                                              
                                        Source datasets
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('create-actions') }}">                                                                              
                                        Creates
                                    </a>
                                </div>
                            </li>
                            
                            <!-- 
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('queues') }}">{{ __('Queue') }}</a>
                            </li>
                            -->
                            
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
