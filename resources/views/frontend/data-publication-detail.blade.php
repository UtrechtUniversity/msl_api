@section('title', 'Data publication')
<x-layout_main>

    <div class="mainContentDiv">

        {{-- a general no small width view notification --}}
        @include('components.no_mobile_view')

        <div class="noMobileView_wideScreenDiv">

        <div class="tabLinksParent">
            @session('data_publication_active_search')
                @include('components.tabLinks',[
                    'categoryName'  => 'Results',
                    'routes'        => array(
                            'Back to search results'   => $value,
                    )
                ])
            @endsession
            @include('components.tabLinks',[
                'categoryName'  => 'Sections',
                'routes'        => array(
                        'Metadata'   => route("data-publication-detail", ['id' => $data['name']]),
                        'Files'  => route("data-publication-detail-files", ['id' => $data['name']])
                ),
                'routeActive'   => route("data-publication-detail", ['id' => $data['name']])
            ])
        </div>

            <div class="listMapDetailDivParent">
                    <div class="detailDiv dividers">

                                <div class="detailEntryDiv">
                                    <h2 class="">Data Publication</h2>
                                    <h1 class="text-lg">{!! $data['msl_title_annotated'] !!}</h1>
                                    <p class="italic text-center">                                       
                                        @foreach ( $data['msl_authors'] as $author )
                                            {{ $author["msl_author_name"] }} 
                                            {{-- a little divider between names --}}
                                                @if (sizeof($data['msl_authors']) -1 != array_search($author, $data['msl_authors']) )
                                                    |
                                                @endif
                                        @endforeach 
                                    </p>
                                </div>
                                
                                <div class="detailEntryDiv">
                                    <p class="">{{ $data['msl_publisher'] }} ({{ $data['msl_publication_year'] }})</p>
                                    <p>
                                        {!! $data['msl_notes_annotated'] !!}
                                    </p>
                                </div >

                                
                                <div class="detailEntryDiv">
                                    <h4 class="text-left">Keywords</h4>

                                    @if (array_key_exists("msl_tags", $data))
                                        <br>
                                        <details class="collapse collapse-arrow wordCardCollapser" id="original-keywords-panel">
                                            <summary class="collapse-title">Originally assigned keywords 
                                                <x-ri-information-line id="orginal-keywords-popup" class="info-icon"/>
                                            </summary>
                                            <div class="collapse-content wordCardParent">
                                                @foreach ( $data['msl_tags'] as $keyword)
                                                    <div 
                                                        class="wordCard"
                                                        data-highlight="tag"
                                                        data-uris='{!! json_encode($keyword['msl_tag_uris']) !!}'
                                                    >
                                                        {{ $keyword['msl_tag_string'] }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                        <script>
                                            tippy('#orginal-keywords-popup', {
                                                content: "lists only keywords originally assigned by the authors",
                                                placement: "right",
                                                theme: "msl"
                                            });                                    
                                        </script>
                                    @endif


                                    @if (array_key_exists("msl_original_keywords", $data))
                                        <br>
                                        <details class="collapse collapse-arrow wordCardCollapser" id="corresponding-keywords-panel">

                                        <summary class="collapse-title">Corresponding MSL vocabulary keywords 
                                            <x-ri-information-line id="corresponding-keywords-popup" class="info-icon"/>
                                        </summary>
                                        <div class="collapse-content wordCardParent" id="corresponding-keywords-container">
                                            @foreach ( $data['msl_original_keywords'] as $keyword)
                                                <div 
                                                    class="wordCard"
                                                    data-uri="{{ $keyword['msl_original_keyword_uri'] }}"
                                                    data-highlight="text-keyword"
                                                    data-filter-link="/data-access?msl_enriched_keyword_uri[]={{ $keyword['msl_original_keyword_uri'] }}"
                                                >
                                                    {{ $keyword['msl_original_keyword_label'] }}
                                                </div>
                                            @endforeach
                                        </div>
                                        </details>
                                        <script>
                                            tippy('#corresponding-keywords-popup', {
                                                content: "lists terms from MSL vocabularies that are the same as, or are interpreted synonymous to the originally assigned keywords",
                                                placement: "right",
                                                theme: "msl"
                                            });

                                            tippy.delegate('#corresponding-keywords-container', {
                                            target: '.wordCard',
                                            trigger: 'click',
                                            theme: "msl",
                                            placement: 'right',
                                            interactive: true,
                                            allowHTML: true,
                                            appendTo: document.body,
                                            maxWidth: 600,
                                            onShow(instance) {
                                                if (instance.state.ajax === undefined) {
                                                    instance.state.ajax = {
                                                        isFetching: false,
                                                        canFetch: true,
                                                    }
                                                }

                                                if (instance.state.ajax.isFetching || !instance.state.ajax.canFetch) {
                                                    return
                                                }

                                                $.ajax({
                                                    url: '/api/vocabularies' + "/term?uri=" + instance.reference.dataset.uri,
                                                    type: 'GET',
                                                    dataType: 'json',
                                                    dataset: instance.reference.dataset,
                                                    async: true,
                                                    beforeSend: function () {
                                                        instance.state.ajax.isFetching = true;
                                                    },
                                                    success: function(res) {        
                                                        content = "<div>";
                                                        content += "<table>";
                                                        content += "<tr><td class=\"\">name</td><td>" + res.name + "</td></tr>";
                                                        content += "<tr><td class=\"\">indicators</td><td>";
                                                        res.synonyms.forEach((synonym) => {
                                                        content += '"' + synonym.name + '" ';
                                                        });
                                                        content += "</td></tr>";
                                                        content += "<tr><td class=\"\">parent term</td><td>";
                                                        if(res.parent) {
                                                        content += res.parent.name;
                                                        } else {
                                                        content += 'none';
                                                        }
                                                        content += "</td></tr>";
                                                        content += "<tr><td class=\"\">occurs in vocabulary</td><td>" + res.vocabulary.display_name + "</td></tr>";
                                                        content += "<tr><td class=\"\">uri</td><td>" + res.uri + "</td></tr>";

                                                        if(this.dataset.sources) {
                                                            matchSources = JSON.parse(this.dataset.sources);
                                                            if(matchSources.length > 0) {
                                                                content += "<tr><td class=\"\">sources</td><td>" + matchSources.join(", ") + "</td></tr>";
                                                            }
                                                        }

                                                        content += "</table>";
                                                        content += "<a href=\"" + this.dataset.filterLink + "\"><button class=\"btn btn-primary\">view data publications with keyword</button</a>";
                                                        content += "</div>";

                                                        instance.setContent(content);
                                                        instance.state.ajax.isFetching = false;
                                                    }
                                                });
                                            },
                                            onHidden(instance) {
                                                instance.setContent('Loading...')
                                                instance.state.ajax.canFetch = true
                                            },
                                        });
                                        </script>
                                    @endif

                                    @if (array_key_exists("msl_enriched_keywords", $data))
                                    <br>
                                    <details class="collapse collapse-arrow wordCardCollapser" open>
                                    <summary class="collapse-title">MSL enriched keywords 
                                        <x-ri-information-line id="enriched-keywords-popup" class="info-icon"/>
                                    </summary>
                                    <div class="collapse-content wordCardParent" id="enriched-keywords-container">
                                        @foreach ( $data['msl_enriched_keywords'] as $keyword)
                                            <div
                                                class="wordCard" 
                                                data-associated-subdomains='["{{ implode(', ', $keyword['msl_enriched_keyword_associated_subdomains']) }}"]'
                                                data-uri="{{ $keyword['msl_enriched_keyword_uri'] }}"
                                                data-filter-link="/data-access?msl_enriched_keyword_uri[]={{ $keyword['msl_enriched_keyword_uri'] }}"
                                                data-highlight="text-keyword"
                                                data-matched-child-uris='{!! json_encode($keyword['msl_enriched_keyword_match_child_uris']) !!}'
                                                data-sources='{!! json_encode($keyword['msl_enriched_keyword_match_locations']) !!}'
                                            >
                                                {{ $keyword['msl_enriched_keyword_label'] }}
                                            </div>
                                        @endforeach
                                    </div>
                                    </details>
                                    <script>
                                        tippy('#enriched-keywords-popup', {
                                            content: "MSL enriched keywords include MSL vocabulary terms corresponding to the keywords originally assigned by the authors, parent terms, and MSL vocabulary terms corresponding to words used in the data publication title and abstract. In enriching keyword sets like this, MSL strives to make datasets more findable. See anything odd? Contact us at epos.msl.data@uu.nl. MSL vocabularies available on GitHub - see top tab ‘vocabularies'.",
                                            placement: "right",
                                            theme: "msl"
                                        });

                                        tippy.delegate('#enriched-keywords-container', {
                                            target: '.wordCard',
                                            trigger: 'click',
                                            theme: 'msl',
                                            placement: 'right',
                                            interactive: true,
                                            allowHTML: true,
                                            appendTo: document.body,
                                            maxWidth: 600,
                                            onShow(instance) {
                                                if (instance.state.ajax === undefined) {
                                                    instance.state.ajax = {
                                                        isFetching: false,
                                                        canFetch: true,
                                                    }
                                                }

                                                if (instance.state.ajax.isFetching || !instance.state.ajax.canFetch) {
                                                    return
                                                }

                                                $.ajax({
                                                    url: '/api/vocabularies' + "/term?uri=" + instance.reference.dataset.uri,
                                                    type: 'GET',
                                                    dataType: 'json',
                                                    dataset: instance.reference.dataset,
                                                    async: true,
                                                    beforeSend: function () {
                                                        instance.state.ajax.isFetching = true;
                                                    },
                                                    success: function(res) {        
                                                        content = "<div>";
                                                        content += "<table>";
                                                        content += "<tr><td class=\"\">name</td><td>" + res.name + "</td></tr>";
                                                        content += "<tr><td class=\"\">indicators</td><td>";
                                                        res.synonyms.forEach((synonym) => {
                                                        content += '"' + synonym.name + '" ';
                                                        });
                                                        content += "</td></tr>";
                                                        content += "<tr><td class=\"\">parent term</td><td>";
                                                        if(res.parent) {
                                                        content += res.parent.name;
                                                        } else {
                                                        content += 'none';
                                                        }
                                                        content += "</td></tr>";
                                                        content += "<tr><td class=\"\">occurs in vocabulary</td><td>" + res.vocabulary.display_name + "</td></tr>";
                                                        content += "<tr><td class=\"\">uri</td><td>" + res.uri + "</td></tr>";

                                                        if(this.dataset.sources) {
                                                            matchSources = JSON.parse(this.dataset.sources);
                                                            if(matchSources.length > 0) {
                                                                content += "<tr><td class=\"\">sources</td><td>" + matchSources.join(", ") + "</td></tr>";
                                                            }
                                                        }

                                                        content += "</table>";
                                                        content += "<a href=\"" + this.dataset.filterLink + "\"><button class=\"btn btn-primary\">view data publications with keyword</button</a>";
                                                        content += "</div>";

                                                        instance.setContent(content);
                                                        instance.state.ajax.isFetching = false;
                                                    }
                                                });
                                            },
                                            onHidden(instance) {
                                                instance.setContent('Loading...')
                                                instance.state.ajax.canFetch = true
                                            },
                                        });
                                    </script>
                                    @endif
                                </div>

                                @if (array_key_exists("msl_subdomains_original", $data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">MSL original sub domains</h4>
                                    <div class="wordCardParent">
                                        {{-- hover behaviour: highlights all related tags above --}}
                                        @foreach ( $data['msl_subdomains_original'] as $keyword)
                                            <div class="wordCard">{{ $keyword['msl_subdomain_original'] }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if (array_key_exists("msl_subdomains",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">MSL enriched sub domains <i id="enriched-subdomains-popup">i</i></h4>
                                    <div class="wordCardParent">
                                        {{-- hover behaviour: highlights all related tags above --}}
                                        @foreach ( $data['msl_subdomains'] as $keyword)
                                            <div 
                                                class="wordCard" 
                                                data-toggle="domain-highlight"
                                                data-domain="{{ $keyword['msl_subdomain'] }}"                                                                                        
                                            >
                                                {{ $keyword['msl_subdomain'] }}
                                            </div>
                                        @endforeach
                                    </div>
                                    <script>
                                        tippy('#enriched-subdomains-popup', {
                                            content: "Based on the MSL enriched keywords, enriched sub domains are added based on the originating vocabularies.",
                                            placement: "right",
                                            theme: "msl"
                                        });
                                    </script>
                                </div>
                                @endif

                                @if (array_key_exists("msl_source",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Source</h4>
                                    <a class="detailEntrySub2" href="{{ $data['msl_source'] }}">{{ $data['msl_source'] }}</a>
                                </div>
                                @endif

                                @if (array_key_exists("msl_publisher",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Source publisher</h4>
                                    <p class="detailEntrySub2">{{ $data['msl_publisher'] }}</p>
                                </div>
                                @endif

                                @if (array_key_exists("msl_doi",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">DOI</h4>
                                    <p class="detailEntrySub2">{{ $data['msl_doi'] }}</p>
                                </div>
                                @endif

                                @if (array_key_exists("msl_authors",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Authors</h4>
                                    <div class="detailEntrySub2 dividers flex flex-col gap-4">
                                        @foreach ( $data['msl_authors'] as $keyword)
                                            <div>
                                                <p class="text-sm p-0">{{ $keyword['msl_author_name'] }}</p>
                                                <p class="text-sm p-0">{{ $keyword['msl_author_orcid'] }}</p>
                                                <p class="text-sm p-0">{{ $keyword['msl_author_affiliation'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if (array_key_exists("msl_contributors",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Contributers</h4>
                                    <div class="detailEntrySub2 dividers flex flex-col gap-4">
                                        @foreach ( $data['msl_contributors'] as $keyword)
                                            <div>
                                                <p class="text-sm p-0">{{ $keyword['msl_contributor_name'] }}</p>
                                                <p class="text-sm p-0">{{ $keyword['msl_contributor_role'] }}</p>
                                                <p class="text-sm p-0">{{ $keyword['msl_contributor_orcid'] }}</p>
                                                <p class="text-sm p-0">{{ $keyword['msl_contributor_affiliation'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if (array_key_exists("msl_references",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">References</h4>
                                    <div class="detailEntrySub2 dividers flex flex-col gap-4">
                                        @foreach ( $data['msl_references'] as $keyword)
                                            <div>
                                                <p class="text-sm p-0">{{ $keyword['msl_reference_title'] }}</p>
                                                <p class="text-sm p-0">{{ $keyword['msl_reference_doi'] }}</p>
                                                <p class="text-sm p-0">{{ $keyword['msl_reference_handle'] }}</p>
                                                <p class="text-sm p-0">{{ $keyword['msl_reference_type'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if (array_key_exists("msl_points_of_contact",$data))
                                <br>
                                    <div class="detailEntryDiv flex flex-row">
                                        <h4 class="detailEntrySub1">Contact</h4>
                                        <div class="detailEntrySub2 dividers flex flex-col gap-4">
                                            {{-- hover behaviour: highlights all related tags above --}}
                                            @foreach ( $data['msl_points_of_contact'] as $keyword)
                                                <div>
                                                    <p class="text-sm p-0">{{ $keyword['msl_contact_name'] }}</p>
                                                    <p class="text-sm p-0">{{ $keyword['msl_contact_electronic_address'] }}</p>
                                                    <p class="text-sm p-0">{{ $keyword['msl_contact_organisation'] }}</p>
                                                </div>
                                            @endforeach
  
                                        </div>
                                    </div>
                                @endif
                                
                                @if (array_key_exists("msl_citation",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Citiation</h4>
                                    <p class="detailEntrySub2 text-sm">{!! $data['msl_citation'] !!}</p>
                                </div>
                                @endif


                                @if (array_key_exists("msl_collection_period",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Collection Period</h4>
                                    <div class="detailEntrySub2 flex flex-row">
                                        @foreach ( $data['msl_collection_period'] as $keyword)
                                            <p class="text-sm p-0">{{ $keyword["msl_collection_start_date"] }} - {{ $keyword["msl_collection_end_date"] }}</p>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if (array_key_exists("msl_geolocations",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Geo location(s)</h4>
                                    <div class="detailEntrySub2 flex flex-col">
                                        @foreach ( $data['msl_geolocations'] as $keyword)
                                            <p class="text-sm">{{ $keyword['msl_geolocation_place'] }}</p>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if (array_key_exists("msl_geojson_featurecollection",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Spatial coordinates</h4>
                                    <div class="detailEntrySub2 flex flex-col">
                                        <div id="map" style="height: 300px;"></div>
                                    </div>

                                    <script>
                                        function onEachFeature(feature, layer) {
                                            if (feature.properties.name) {                                
                                                var popupContent = `<h5>${feature.properties.name}</h5>`;

                                                layer.bindPopup(popupContent);
                                            }
                                        }
                                    
                                        var features = <?php echo $data['msl_geojson_featurecollection']; ?>;        				
                                    
                                        var map = L.map('map').setView([0, 0], 1);
                                        
                                        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            maxZoom: 19,
                                            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                                        }).addTo(map);
                                                                                                                                
                                        L.geoJSON(features, {
                                            onEachFeature: onEachFeature
                                        }).addTo(map);                                                                                                                                                                                
                                    </script>
                                </div>
                                @endif


                        

                    </div>
                    
            </div>
        </div>
       
        


    </div>

@push('vite')
    @vite(['resources/js/tooltip.js'])
@endpush


</x-layout_main>