@section('title', 'Laboratory')
<x-layout_main>
    <div class="tab-links-parent">
        @include('components.tab-links',[
            'routes'        => array(
                    'Laboratory'   => route('lab-detail', ['id' => $data->name]),
                    'Equipment'  => route('lab-detail-equipment', ['id' => $data->name])
            ),
            'routeActive'   => route('lab-detail', ['id' => $data->name])
        ])
    </div>

    <div class="main-content content-divide-y">
        <div class="detail-div content-divide-y">
            <div class="detail-entry-div !flex-col">
                <h2 class="">Laboratory Details</h2>
                <h1 class="text-lg">{{ $data->title }}</h1>                                    
            </div>
            
            @if ($data->msl_description_html != '')
                <div class="detail-entry-div !flex-col place-items-center">  
                    <h3>Description</h3>
                    @include('components.tab-list',[
                            'allTabs' => array(
                                'Description' => [
                                    'content' => $data->msl_description_html,
                                    'id' => 'description'
                                ]
                            ),
                            'checkedElementId' => 'description'
                        ])                
                    
                </div>
            @else
                <p class="italic text-center">no description found</p>
            @endif

            {{-- report dead link? --}}
            @if ($data->msl_website != '')
                <br>
                <div class="detail-entry-div flex flex-row">
                    <h4 class="detail-entry-title">Website</h4>

                    <div class="detail-entry-content">
                        @include('components.list-views.table-list',[
                            'entries' => [
                                $data->msl_website
                            ],
                            'withKeys' => false,
                        ])
                    </div>
                </div>
            @endif

            @if ($data->msl_domain_name != '')
                <br>
                <div class="detail-entry-div flex flex-row">
                    <h4 class="detail-entry-title">Domain</h4>
                    <div class="detail-entry-content">
                        @include('components.list-views.table-list',[
                            'entries' => [
                                $data->msl_domain_name
                            ],
                            'withKeys' => false,
                            'textSize' => 'base'
                        ])
                    </div>
                </div>
            @endif

            @if ($data->msl_organization_name != '')
                <br>
                <div class="detail-entry-div flex flex-row">
                    <h4 class="detail-entry-title">Organization name</h4>
                    <div class="detail-entry-content">
                        @include('components.list-views.table-list',[
                            'entries' => [
                                $data->msl_organization_name
                            ],
                            'withKeys' => false,
                            'textSize' => 'base'
                        ])
                    </div>
                </div>
            @endif

            <br>
            <div class="detail-entry-div flex flex-row">
                <h4 class="detail-entry-title">Address</h4>
                <div class="detail-entry-content">
                    @include('components.list-views.table-list',[
                        'entries' => [
                            $data->msl_address_street_1,
                            $data->msl_address_street_2,
                            $data->msl_address_postalcode,
                            $data->msl_address_city,
                            $data->msl_address_country_name
                        ],
                        'withKeys' => false,
                        'textSize' => 'base'
                    ])
                </div>
            </div>

            @if ($data->msl_location != '')
                <br>
                <div class="detail-entry-div flex flex-row">
                    <h4 class="detail-entry-title">Location</h4>
                    <div class="">
                        <div id="map" style="height: 300px;"></div>

                        <script>
                            function onEachFeature(feature, layer) {
                                if (feature.properties) {                                
                                    var popupContent = `<h5>${feature.properties.title}</h5><p>${feature.properties.msl_organization_name}</p>`;

                                    layer.bindPopup(popupContent);
                                }
                            }

                            var features = <?php echo $data->msl_location; ?>;

                            if(features.geometry.coordinates) {
                                var map = L.map('map').setView([features.geometry.coordinates[1], features.geometry.coordinates[0]], 4);    
                            }
                            else {
                                var map = L.map('map').setView([51.505, -0.09], 4);
                            }                                        
                            
                            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                            }).addTo(map);
                                                                                                                    
                            L.geoJSON(features, {
                                onEachFeature: onEachFeature
                            }).addTo(map);                                                                                                                                                                                
                        </script>
                    </div>
                </div>
            @endif
        
        @if($labHasMailContact)
            <div class="p-20 w-full flex justify-around">
                <a href="{{ route('laboratory-contact-person', [
                    'id'          => $data['name']
                ]) }}">
                    <button class="btn btn-primary btn-lg btn-wide ">Contact Laboratory</button>
                </a>
            </div>
        @endif
       
    </div>

</x-layout_main>