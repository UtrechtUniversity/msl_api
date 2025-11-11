@section('title', 'Laboratory')
<x-layout_main>
    <div class="mainContentDiv ">
        <div class="tab-links-parent">
            @include('components.tab-links',[
                'routes'        => array(
                        'Laboratory'   => route('lab-detail', ['id' => $data->name]),
                        'Equipment'  => route('lab-detail-equipment', ['id' => $data->name])
                ),
                'routeActive'   => route('lab-detail-equipment', ['id' => $data->name])
            ])
        </div>
        <div class="main-content">
            <div class="detail-div content-divide-y">

                <div class="detailEntryDiv">
                    <h2 class="">Laboratory Equipment</h2>
                    <h1 class="text-lg">{{ $data->title }}</h1>
                </div>

                <div class="flex flex-wrap justify-center place-content-center gap-10 w-full">
                    @if (count($equipmentData) > 0)
                        @foreach ($equipmentData as $groupName => $group)
                            <h3>{{ $groupName }}</h3>

                            @foreach ($group as $equipment)
                            <details class="collapse collapse-arrow wordCardCollapser bg-primary-200">
                                <summary class="collapse-title font-bold">{{ $equipment['title'] }}</summary>
                                <div class="collapse-content">
                                    @if (strlen($equipment['msl_description_html']) > 0)
                                        <div class="p-4">
                                            {!! $equipment['msl_description_html'] !!}
                                        </div>
                                    @else
                                        <p class="italic text-center pt-10 pb-8">no description found</p>
                                    @endif

                                    <div class="flex flex-col w-full p-2 justify-center items-center">
                            
                                        <div class="w-3/4 max-w-96 flex flex-row">
                                            <p class="w-1/2 place-content-center text-left font-bold">
                                            Category
                                            </p>
                                            <p class="w-1/2 text-left">{{ $equipment['msl_category_name'] }}</p>
                                        </div>

                                        <div class="w-3/4 max-w-96 flex flex-row">
                                            <p class="w-1/2 place-content-center text-left font-bold">
                                            Group
                                            </p>
                                            <p class="w-1/2 text-left">{{ $equipment['msl_group_name'] }}</p>
                                        </div>

                                        <div class="w-3/4 max-w-96 flex flex-row">
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
</x-layout_main>