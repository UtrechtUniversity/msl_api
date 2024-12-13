@section('title', 'Laboratory')
<x-layout_main>
    <div class="mainContentDiv ">

        {{-- a general no small width view notification --}}
        @include('components.no_mobile_view')

        <div class="noMobileView_wideScreenDiv">

            <div class="tabLinksParent">

                @include('components.tabLinks',[
                    'routes'        => array(
                            'Laboratory'    => route('lab-detail', ['id' => $ckanLabName]),
                            'Equipment'     => route('lab-detail-equipment', ['id' => $ckanLabName])
                    ),
                    'routeActive'   => route('lab-detail-equipment', ['id' => $ckanLabName])
                ])
            </div>
                
            <div class="listMapDetailDivParent ">
            
                <div class="detailDiv bg-primary-100">        
                    
                        <div class="detailEntryDiv">
                            <h2 class="">Laboratory Equipment</h2>
                            <h1 class="text-lg">{{ $data2['title'] }}</h1>
                        </div>

                        <div class="flex flex-wrap justify-center place-content-center gap-10 w-full">
                            @if (count($data) > 0)
                                @foreach ($data as $groupName => $group)
                                    <h3>{{ $groupName }}</h3>

                                    @foreach ($group as $equipment)
                                    <details class="collapse collapse-arrow">
                                        <summary class="collapse-title">{{ $equipment['title'] }}</summary>
                                        <div class="collapse-content">
                                            @if (strlen($equipment['msl_description_html']) > 0)
                                                <div class="p-4">
                                                    {!! $equipment['msl_description_html'] !!}
                                                </div>
                                            @else
                                                <p class="italic text-center pt-10 pb-8">no description found</p>
                                            @endif

                                            <div class="w-3/4 place-content-center self-center p-2">
                                    
                                                <div class="w-full flex flex-row p-2">
                                                    <p class="w-1/2 place-content-center text-left font-bold">
                                                    Category
                                                    </p>
                                                    <p class="w-1/2 text-left">{{ $equipment['msl_category_name'] }}</p>
                                                </div>

                                                <div class="w-full flex flex-row p-2">
                                                    <p class="w-1/2 place-content-center text-left font-bold">
                                                    Group
                                                    </p>
                                                    <p class="w-1/2 text-left">{{ $equipment['msl_group_name'] }}</p>
                                                </div>

                                                <div class="w-full flex flex-row p-2">
                                                    <p class="w-1/2 place-content-center text-left font-bold">
                                                    Type
                                                    </p>
                                                    <p class="w-1/2 text-left">{{ $equipment['msl_type_name'] }}</p>
                                                </div>
                                
                                            @if(isset($equipment['msl_equipment_addons']))                                            
                                                <div class="w-full flex flex-row p-2">
                                                    <p class="w-1/2 place-content-center text-left font-bold">
                                                    Addons
                                                    </p>
                                                </div>
                                                @foreach ($equipment['msl_equipment_addons'] as $addon)
                                                    <div class="bg-base-300 mb-4">
                                                    <div class="w-full flex flex-row p-2">
                                                        <p class="w-1/2 place-content-center text-left font-bold">
                                                        Type
                                                        </p>
                                                        <p class="w-1/2 text-left">{{ $addon['msl_equipment_addon_type'] }}</p>
                                                    </div>

                                                    <div class="w-full flex flex-row p-2">
                                                        <p class="w-1/2 place-content-center text-left font-bold">
                                                        Group
                                                        </p>
                                                        <p class="w-1/2 text-left">{{ $addon['msl_equipment_addon_group'] }}</p>
                                                    </div>

                                                    <div class="w-full flex flex-row p-2">
                                                        <p class="w-1/2 place-content-center text-left font-bold">
                                                        Description
                                                        </p>
                                                        <p class="w-1/2 text-left">{{ $addon['msl_equipment_addon_description'] }}</p>
                                                    </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </details>
                                    @endforeach
                                @endforeach
                            @else
                                <p>No equipment found for this laboratory</p>
                            @endif
                        </div>

                    </div>
                    
                </div>
            </div>
        </div>               
    </div>
</x-layout_main>