@section('title', 'Data access')
<x-layout_main>

    <div class="mainContentDiv">

        {{-- a general no small width view notification --}}
        @include('components.no_mobile_view')

        {{-- top div --}}
        <div class="noMobileView_wideScreenDiv">

 
            {{-- content bottom div --}}
            <div class="listMapDetailDivParent">


                {{-- side bar --}}
                @include('components.search-div-filters',[
                    'filterDataPath' => 'public/interpreted.json',
                    'pbDetail' =>   true
                ])

                {{-- main field --}}
                <div class="listMapDiv">


                    {{-- top search div --}}
                    @include('components.search-div-list',[
                        'searchFor'     => 'data publications',
                        'amountFound'   => 'data publications',
                        'dpDropdown'    => true
                    ])

                    {{-- list view --}}    
                    <div class="listView">

                        {{-- loop list content --}}
                        @foreach ($result->getResults() as $dataPublication)

                            @include('components.list-views.data-publication', [
                                'data' => $dataPublication
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