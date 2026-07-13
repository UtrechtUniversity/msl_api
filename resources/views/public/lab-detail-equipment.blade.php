@use('Illuminate\Support\Str')

@section('title', 'Laboratory')
<x-layout_main>
    <div class="mainContentDiv ">
        <div class="tab-links-parent">
            @include('public.components.tab-links', [
                'routes' => [
                    'Laboratory' => route('lab-detail', ['id' => $laboratory->ckan_id]),
                    'Equipment' => route('lab-detail-equipment', ['id' => $laboratory->ckan_id]),
                ],
                'routeActive' => route('lab-detail-equipment', ['id' => $laboratory->ckan_id]),
            ])
        </div>
        <div class="main-content">
            <div class="detail-div">

                <div class="detailEntryDiv">
                    <h2 class="">Laboratory Equipment</h2>
                    <h1 class="text-lg">{{ $laboratory->name }}</h1>
                </div>

                <div class="flex flex-wrap justify-center place-content-center gap-10 max-w-2xl py-10">
                    <h5 class="italic">- click on equipment pieces to view details -</h5>
                    @if (count($equipment) > 0)

                        @foreach ($equipment as $equipmentPiece)
                            <details class="collapse collapse-arrow wordCardCollapser bg-primary-100 ">
                                <summary class="collapse-title font-bold hover-interactive">{{ $equipmentPiece->name }}
                                </summary>
                                <div class="collapse-content">
                                    @if (strlen($equipmentPiece->description_html) > 0)
                                        <div class="p-4">
                                            {!! Str::of($equipmentPiece->description_html)->stripTags("<p>") !!}
                                        </div>
                                    @else
                                        <p class="italic text-center pt-10 pb-8">no description found</p>
                                    @endif

                                    <div class="flex flex-col w-full p-2 justify-center items-center">

                                        <div class="w-3/4 max-w-96 flex flex-row">
                                            <p class="w-1/2 place-content-center text-left font-bold">
                                                Category
                                            </p>
                                            <p class="w-1/2 text-left">{{ $equipmentPiece->category_name }}</p>
                                        </div>

                                        <div class="w-3/4 max-w-96 flex flex-row">
                                            <p class="w-1/2 place-content-center text-left font-bold">
                                                Group
                                            </p>
                                            <p class="w-1/2 text-left">{{ $equipmentPiece->group_name }}</p>
                                        </div>

                                        <div class="w-3/4 max-w-96 flex flex-row">
                                            <p class="w-1/2 place-content-center text-left font-bold">
                                                Type
                                            </p>
                                            <p class="w-1/2 text-left">{{ $equipmentPiece->type_name }}</p>
                                        </div>

                                        @if (count($equipmentPiece->laboratoryEquipmentAddons) > 0)
                                            <div class="w-full flex flex-row p-2">
                                                <p class="w-1/2 place-content-center text-left font-bold">
                                                    Addons
                                                </p>
                                            </div>
                                            @foreach ($equipmentPiece->laboratoryEquipmentAddons as $addon)
                                                <div class="bg-base-300 mb-4">
                                                    <div class="w-full flex flex-row p-2">
                                                        <p class="w-1/2 place-content-center text-left font-bold">
                                                            Type
                                                        </p>
                                                        <p class="w-1/2 text-left">
                                                            {{ $addon->type }}</p>
                                                    </div>

                                                    <div class="w-full flex flex-row p-2">
                                                        <p class="w-1/2 place-content-center text-left font-bold">
                                                            Group
                                                        </p>
                                                        <p class="w-1/2 text-left">
                                                            {{ $addon->group }}</p>
                                                    </div>

                                                    <div class="w-full flex flex-row p-2">
                                                        <p class="w-1/2 place-content-center text-left font-bold">
                                                            Description
                                                        </p>
                                                        <p class="w-1/2 text-left">
                                                            {{ $addon->description }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                            </details>
                        @endforeach
                    @else
                        <p>No equipment found for this laboratory</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-layout_main>
