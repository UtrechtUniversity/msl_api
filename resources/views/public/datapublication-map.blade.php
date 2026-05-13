@section('title', 'Datapublications map')
<x-layout_main>
    <div class="tab-links-parent">
        @include('public.components.tab-links', [
            'categoryName' => 'Data Publications',
            'routes' => [
                'List' => route('data-access'),
                'Map' => route('data-access-map'),
            ],
            'routeActive' => route('data-access-map'),
        ])
    </div>

    <div class="main-content flex-col h-full">
        <div class="sub-content-wide flex place-content-center w-full h-full">
            <div class="drawer lg:drawer-open w-full h-full">
                <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
                <div class="drawer-content bg-secondary-100 flex h-full ">
                    {{-- content here --}}

                    <div class="w-full h-full flex flex-col bg-primary-100 pl-4">
                        {{-- top search div --}}
                        @include('public.components.datapublication-map.search-div')
                        {{-- list view --}}
                        <div class="list-view">
                            @include('public.components.datapublication-map.map-view')
                        </div>
                    </div>

                </div>
                <div class="drawer-side z-40 h-full">
                    <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
                    {{-- side bar --}}

                    <ul class="menu h-full w-80 p-0  text-primary-900 bg-primary-200 flex flex-col ">
                        <!-- Sidebar content here -->
                        @include('public.components.datapublication-map.sidebar')
                    </ul>
                </div>
            </div>

        </div>
    </div>
    {{--  --}}

</x-layout_main>
