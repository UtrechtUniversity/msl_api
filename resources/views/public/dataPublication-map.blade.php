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

    <div class="main-content">
        <div class="sub-content-wide flex place-content-center w-full">
            <div class="drawer lg:drawer-open ">
                <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
                <div class="drawer-content bg-secondary-100 flex relative ">
                    {{-- content here --}}
                    <div
                        class="z-30 p-0 w-10 h-52 fixed inset-y-1/2 left-0 -translate-y-26 bg-secondary-200 lg:hidden  opacity-75 hover:opacity-100">
                        <label for="my-drawer-2" class="btn drawer-button w-full h-full flex flex-col justify-center ">
                            <p class="" style="writing-mode: sideways-lr;">
                                click here to see filters
                            </p>
                        </label>
                    </div>
                    <div class="w-full min-h-full bg-primary-100 pl-4">
                        {{-- top search div --}}
                        @include('public.components.search-div-list-map')
                        {{-- list view --}}
                        <div class="list-view">
                            @include('public.components.dataPublication-map-view')
                        </div>
                    </div>

                </div>
                <div class="drawer-side z-40">
                    <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
                    {{-- side bar --}}

                    <ul class="menu bg-primary-200 min-h-full p-0 w-80 text-primary-900">
                        <!-- Sidebar content here -->
                        @include('public.components.sidebar-map')
                    </ul>
                </div>
            </div>

        </div>
    </div>

</x-layout_main>

{{-- @include('public.components.dataPublication-map-view') --}}
