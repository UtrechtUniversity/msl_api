@section('title', 'Laboratory list')
<x-layout_main>

    <div class="tab-links-parent">
        @include('components.tab-links',[
            'categoryName'  => 'Laboratories',
            'routes'        => array(
                    'Map'   => route("labs-map"),
                    'List'  => route("labs-list")
            ),
            'routeActive'   => route("labs-list")
        ])
        @include('components.tab-links',[
            'categoryName'  => 'Equipment',
            'routes'        => array(
                    'Map'   => route("equipment-map"),
                    'List'  => route("equipment-list"),
            ),
        ])
    </div>

    <div class="main-content">
        <div class="sub-content-wide flex place-content-center w-full">
            <div class="drawer lg:drawer-open ">
                <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
                <div class="drawer-content bg-secondary-100 flex relative ">
                    {{-- content here --}}
                    <div class="z-30 p-0 w-10 h-52 fixed inset-y-1/2 left-0 -translate-y-26 bg-secondary-200 lg:hidden  opacity-75 hover:opacity-100">
                        <label for="my-drawer-2" class="btn drawer-button w-full h-full flex flex-col justify-center "
                        >
                        <p 
                        class=""
                        style="writing-mode: sideways-lr;" >
                            click here to see filters
                          </p>
                        </label>
                    </div>
                    <div class="w-full min-h-full bg-primary-100 pl-4">
                        {{-- top search div --}}
                        @include('components.search-div-list',[
                            'searchFor'     => 'lab',
                            'amountFound'   => 'Laboratories'
                        ])

                        {{-- list view --}}    
                        <div class="list-view">
                            @if (count($result->getResults()) > 0)
                                @foreach ($result->getResults() as $laboratory)
                                    @include('components.list-views.lab', [
                                        'data' => $laboratory
                                    ])
                                @endforeach     
                            @else
                            <h4 class="italic py-20">- no laboratories found -</h4>
                            @endif
                                         
                        </div>
                        

                        <div class="list-view hidden">
                            @include('components.pagination')
                        </div>
                        

                    </div>

                </div>
                <div class="drawer-side z-40">
                    <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
                    {{-- side bar --}}

                    <ul class="menu bg-primary-200 min-h-full p-0 w-80 text-primary-900">
                    <!-- Sidebar content here -->
                    @include('components.search-div-filters',[
                        'filterDataPath' => 'public/laboratories.json'
                    ])
                    </ul>
                </div>
            </div>
        </div>
    </div>


</x-layout_main>