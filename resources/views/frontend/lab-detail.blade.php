@section('title', 'Laboratory')
<x-layout_main>
    <div class="mainContentDiv">

        {{-- a general no small width view notification --}}
        @include('components.no_mobile_view')

        <div class="noMobileView_wideScreenDiv">

            <div class="tabLinksParent">

                @include('components.tabLinks',[
                    // 'categoryName'  => $data['title'],
                    'routes'        => array(
                            'Laboratory'   => route('lab-detail', ['id' => $data['name']]),
                            'Equipment'  => route('lab-detail-equipment', ['id' => $data['name']])
                    ),
                    'routeActive'   => route('lab-detail', ['id' => $data['name']])
                ])
            </div>

                <div class="listMapDetailDivParent">
                
                    <div class="detailDiv dividers">
                                <div class="detailEntryDiv">
                                    <h2 class="">Laboratory Details</h2>
                                    <h1 class="text-lg">{{ $data['title'] }}</h1>                                    
                                </div>
                                
                                @if (array_key_exists("msl_description_html", $data))
                                    @if (strlen($data['msl_description_html']) > 0)
                                        <div class="detailEntryDiv">                                    
                                                {!! $data['msl_description_html'] !!}
                                        </div>
                                    @else
                                        <p class="italic text-center">no description found</p>
                                    @endif
                                @endif

                                {{-- report dead link? --}}
                                @if (array_key_exists("msl_website", $data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Website</h4>
                                    <div class="detailEntrySub2">
                                        <a href="{{ $data['msl_website'] }}">{{ $data['msl_website'] }}</a>
                                    </div>
                                </div>
                                @endif

                                @if (array_key_exists("msl_domain_name", $data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Domain</h4>
                                    <div class="detailEntrySub2">
                                        <p>{{ $data['msl_domain_name'] }}</p>
                                    </div>
                                </div>
                                @endif

                                @if (array_key_exists("msl_organization_name", $data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Organization name</h4>
                                    <p class="detailEntrySub2">{{ $data['msl_organization_name'] }}</p>
                                </div>
                                @endif

                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Address</h4>                            
                                    <div class="detailEntrySub2">
                                        <p>
                                            @if ($data['msl_address_street_1'] !== "") {{ $data['msl_address_street_1'] }}<br> @endif
                                            @if ($data['msl_address_street_2'] !== "") {{ $data['msl_address_street_2'] }}<br> @endif
                                            @if ($data['msl_address_postalcode'] !== "") {{ $data['msl_address_postalcode'] }}<br> @endif
                                            @if ($data['msl_address_city'] !== "") {{ $data['msl_address_city'] }}<br> @endif
                                            @if ($data['msl_address_country_name'] !== "") {{ $data['msl_address_country_name'] }}<br> @endif
                                        </p>
                                    </div>
                                </div>
                                
                                @if (array_key_exists("msl_location", $data))
                                    <br>
                                    <div class="detailEntryDiv flex flex-row">
                                        <h4 class="detailEntrySub1">Location</h4>
                                        <div class="detailEntrySub2">
                                            <div id="map" style="height: 300px;"></div>

                                            <script>
                                                function onEachFeature(feature, layer) {
                                                    if (feature.properties) {                                
                                                        var popupContent = `<h5>${feature.properties.title}</h5><p>${feature.properties.msl_organization_name}</p>`;

                                                        layer.bindPopup(popupContent);
                                                    }
                                                }

                                                var features = <?php echo $data['msl_location']; ?>;

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

                            <div class="p-20 w-full flex justify-around">
                                <a href="{{ route('laboratory-contact-person', [
                                    'id'          => $data['name']
                                ]) }}">
                                    <button class="btn btn-primary btn-lg btn-wide ">Contact Laboratory</button>
                                </a>
                            </div>

                        </div>

 


                </div>

        </div>
       
    </div>

</x-layout_main>