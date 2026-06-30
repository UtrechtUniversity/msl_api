@section('title', 'Laboratory')
<x-layout_main>
    <div class="tab-links-parent">
        @include('public.components.tab-links', [
            'routes' => [
                'Laboratory' => route('lab-detail', ['id' => $laboratory->ckan_id]),
                'Equipment' => route('lab-detail-equipment', ['id' => $laboratory->ckan_id]),
            ],
            'routeActive' => route('lab-detail', ['id' => $laboratory->ckan_id]),
        ])
    </div>

    <div class="main-content">
        <div class="detail-div content-divide-y">
            <div class="detail-entry-div !flex-col">
                <h2 class="">Laboratory Details</h2>
                <h1 class="text-lg">{{ $laboratory->name }}</h1>
            </div>

            @if ($laboratory->description_html != "")
                <div class="detail-entry-div !flex-col place-items-center">
                    <h3>Description</h3>
                    @include('public.components.tab-list', [
                        'allTabs' => [
                            'Description' => [
                                'content' => $laboratory->description_html,
                                'id' => 'description',
                            ],
                        ],
                        'checkedElementId' => 'description',
                    ])

                </div>
            @else
                <p class="italic text-center">no description found</p>
            @endif

            @if ($laboratory->website != "")
                <br>
                <div class="detail-entry-div flex flex-row">
                    <h4 class="detail-entry-title">Website</h4>

                    <div class="detail-entry-content">
                        @include('public.components.list-views.table-list', [
                            'entries' => [$laboratory->website],
                            'withKeys' => false,
                            'textSize' => 'base',
                        ])
                    </div>
                </div>
            @endif

            @if ($laboratory->fast_domain_name != "")
                <br>
                <div class="detail-entry-div flex flex-row">
                    <h4 class="detail-entry-title">Domain</h4>
                    <div class="detail-entry-content">
                        @include('public.components.list-views.table-list', [
                            'entries' => [$laboratory->fast_domain_name],
                            'withKeys' => false,
                            'textSize' => 'base',
                        ])
                    </div>
                </div>
            @endif

            @if ($laboratory->laboratoryOrganization)
                <br>
                <div class="detail-entry-div flex flex-row">
                    <h4 class="detail-entry-title">Organization name</h4>
                    <div class="detail-entry-content">
                        @include('public.components.list-views.table-list', [
                            'entries' => [$laboratory->laboratoryOrganization->name],
                            'withKeys' => false,
                            'textSize' => 'base',
                        ])
                    </div>
                </div>
            @endif

            <br>
            <div class="detail-entry-div flex flex-row">
                <h4 class="detail-entry-title">Address</h4>
                <div class="detail-entry-content">
                    @include('public.components.list-views.table-list', [
                        'entries' => [
                            $laboratory->address_street_1,
                            $laboratory->address_street_2,
                            $laboratory->address_postalcode,
                            $laboratory->address_city,
                            $laboratory->address_country_name,
                        ],
                        'withKeys' => false,
                        'textSize' => 'base',
                    ])
                </div>
            </div>

            @if ($laboratory->getGeoJsonFeature() != "")
                <br>
                <div class="detail-entry-div flex flex-row">
                    <h4 class="detail-entry-title">Location</h4>
                    <div class="detail-entry-content">
                        <div id="map" style="height: 300px;"></div>

                        <script>
                            window.addEventListener("DOMContentLoaded", () => {

                                function onEachFeature(feature, layer) {
                                    if (feature.properties) {
                                        var popupContent =
                                            `<h5>${feature.properties.title}</h5><p>${feature.properties.msl_organization_name}</p>`;

                                        layer.bindPopup(popupContent);
                                    }
                                }

                                var features = <?php echo $laboratory->getGeoJsonFeature(); ?>;

                                if (features.geometry.coordinates) {
                                    var map = L.map('map').setView([features.geometry.coordinates[1], features.geometry.coordinates[0]],
                                        4);
                                } else {
                                    var map = L.map('map').setView([51.505, -0.09], 4);
                                }

                                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    maxZoom: 19,
                                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                                }).addTo(map);

                                L.geoJSON(features, {
                                    onEachFeature: onEachFeature
                                }).addTo(map);
                            })
                        </script>
                    </div>
                </div>
            @endif

            @if ($labHasMailContact)
                <div class="p-20 w-full flex justify-around">
                    <a
                        href="{{ route('laboratory-contact-person', [
                            'id' => $laboratory->ckan_id,
                        ]) }}">
                        <button class="btn btn-primary btn-lg btn-wide ">Contact Laboratory</button>
                    </a>
                </div>
            @endif
        </div>

</x-layout_main>
