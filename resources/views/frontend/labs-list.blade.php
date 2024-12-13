@section('title', 'Laboratory list')
<x-layout_main>

    <div class="mainContentDiv">

        {{-- a general no small width view notification --}}
        @include('components.no_mobile_view')

        {{-- top div --}}
        <div class="noMobileView_wideScreenDiv">


            <div class="tabLinksParent">
                @include('components.tabLinks',[
                    'categoryName'  => 'Laboratories',
                    'routes'        => array(
                            'Map'   => route("labs-map"),
                            'List'  => route("labs-list")
                    ),
                    'routeActive'   => route("labs-list")
                ])
                @include('components.tabLinks',[
                    'categoryName'  => 'Equipment',
                    'routes'        => array(
                            'Map'   => route("equipment-map"),
                            'List'  => route("equipment-list"),
                    )

                ])
            </div>

            {{-- content bottom div --}}
            <div class="listMapDetailDivParent">


                {{-- side bar --}}
                @include('components.search-div-filters',[
                    'filterDataPath' => 'public/laboratories.json'
                ])

                {{-- main field --}}
                <div class="listMapDiv">


                    {{-- top search div --}}
                    @include('components.search-div-list',[
                        'searchFor'  => 'labs',
                        'amountFound'   => 'labs' 
                    ])

                    {{-- list view --}}    
                    <div class="listView">

                        {{-- loop list content --}}
                        @foreach ($result->getResults() as $laboratory)

                            @include('components.list-views.lab', [
                                'data' => $laboratory,
                            ])
                            
                        @endforeach         
                        
                        
                    </div>
                    
                    {{-- bottom pagination of list --}}
                    @include('components.pagination')

                </div>

            </div>

        </div>

    </div>

</x-layout_main>