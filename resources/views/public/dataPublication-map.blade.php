@section('title', 'Datapublications map')
<x-layout_main>

    <div class="main-content">
        
        <div class="sub-content-wide flex place-content-center w-full h-full">
            
            @include('public.components.no_mobile_view', [
                'breakpoint' => 'md',
            ])

            <div class="hidden md:block m-4 w-full h-full">
                <div class="relative w-full h-[60vh] min-h-[420px] max-h-[85vh] lg:h-[800px] xl:h-[900px]">
                    @include('public.components.dataPublication-map-view')
                </div>
            </div>

        </div>
    </div>
</x-layout_main>
