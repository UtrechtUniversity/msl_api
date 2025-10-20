@section('title', 'Data access')
<x-layout_main>

    <div class="main-content">
            <div class="sub-content-wide flex place-content-center w-full">
                <div class="drawer lg:drawer-open ">
                    <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
                    <div class="drawer-content bg-secondary-100 flex relative ">
                        {{-- content here --}}
                        <div class="p-0  w-10 h-52 fixed inset-y-1/2 left-0 min-[450px]:left-10 bg-secondary-200 lg:hidden  opacity-75 hover:opacity-100">
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
                                'searchFor'     => 'data publications',
                                'amountFound'   => 'data publications',
                                'dpDropdown'    => true
                            ])

                            {{-- list view --}}    
                            <div class="list-view">

                                {{-- loop list content --}}
                                @foreach ($result->getResults() as $dataPublication)

                                    @include('components.list-views.data-publication', [
                                        'data' => $dataPublication
                                    ])

                                @endforeach                                                
                            </div>
                            

                            <div class="list-view hidden">
                                @include('components.pagination')
                            </div>
                            

                        </div>
    
                    </div>
                    <div class="drawer-side ">
                        <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
                        {{-- side bar --}}
                        {{-- @include('components.search-div-filters',[
                            'filterDataPath' => 'public/interpreted.json',
                            'pbDetail' =>   true
                        ]) --}}
                        <ul class="menu bg-primary-200 min-h-full w-80 p-4 text-primary-900">
                        <!-- Sidebar content here -->
                        <li><a>Sidebar Item 1</a></li>
                        <li><a>Sidebar Item 2</a></li>
                        </ul>
                    </div>
                </div>

            </div>
            {{-- <div class="list-view block md:hidden">
                @include('components.pagination')
            </div> --}}
    </div>

</x-layout_main>