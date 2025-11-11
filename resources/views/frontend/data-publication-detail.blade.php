@section('title', 'Data publication')
<x-layout_main>


    <div class="flex flex-col sm:flex-row pt-10 sm:pt-0 justify-center items-center w-full relative">
        @session('data_publication_active_search')
            <div class="px-2 md:px-10 sm:absolute left-0 ">
                    <a href="{{ $value }}">
                        <div class="btn btn-primary btn-wide bg-primary-200">
                            <x-ri-arrow-left-line id="" class="goBack-icon inline"/>
                            Back to search results
                        </div>
                    </a>
            </div>
        @endsession
        <div class="tab-links-parent ">
            @include('components.tab-links',[
                // 'categoryName'  => 'Sections',
                'routes'        => array(
                        'Metadata'  => route("data-publication-detail", ['id' => $data->name]),
                        'Files'     => route("data-publication-detail-files", ['id' => $data->name])
                ),
                'routeActive'   => route("data-publication-detail", ['id' => $data->name])
            ])
        </div>
    </div>


    <div class="main-content">

        <div class="detail-div content-divide-y">

            <div class="detail-entry-div !flex-col">
                <h2 class="">Data Publication</h2>
                @if ($data->msl_title_annotated != '')
                    <h1 class="text-lg">{!! $data->msl_title_annotated !!}</h1>
                @else
                    <h1 class="text-lg italic">- no title found -</h1>
                @endif

                @if (sizeof($data->msl_creators) > 0)
                    <p class="italic text-center">
                        @foreach ( $data->msl_creators as $authorKey => $author )
                            {{ $author->getFullName() }} 
                                @if (sizeof($data->msl_creators) -1 != $authorKey )
                                    |
                                @endif
                        @endforeach
                    </p>
                @else
                    <p class="italic text-center">- no authors found -</p>
                @endif

                @if ($data->msl_publisher != '')
                    <p class="italic text-center">{{ $data->msl_publisher }} </p>
                @else
                    <p class="italic text-center">- no publisher found -</p>
                @endif

                @if ($data->msl_publication_year != '')
                    <p class="italic text-center">({{ $data->msl_publication_year }})</p>
                @else
                    <p class="italic text-center">- no publication year found -</p>
                @endif
            </div>

            <div class="detail-entry-div !flex-col">
                <h3 class="">Descriptions</h3>


                @include('components.tab-list',[
                    'allTabs' => array(
                        'Abstract' => [
                            'content' => $data->msl_description_abstract_annotated,
                            'id' => 'msl_description_abstract_annotated'
                        ],
                        'Methods' => [
                            'content' => $data->msl_description_methods_annotated,
                            'id' => 'msl_description_methods_annotated'
                        ],
                        'Other' => [
                            'content' => $data->msl_description_other_annotated,
                            'id' => 'msl_description_other_annotated'
                        ],
                        'Series Information' => [
                            'content' => $data->msl_description_series_information_annotated,
                            'id' => 'msl_description_series_information_annotated'
                        ],
                        'Content' => [
                            'content' => $data->msl_description_table_of_contents_annotated,
                            'id' => 'msl_description_table_of_contents_annotated'
                        ],
                        'Technical Information' => [
                            'content' => $data->msl_description_technical_info_annotated,
                            'id' => 'msl_description_technical_info_annotated'
                        ],
                    ),
                    'checkedElementId' => 'msl_description_abstract_annotated'
                ])

            </div >
            
            <div class="detail-entry-div !flex-col">
                <h3 class="">Keywords</h3>

                @if (sizeof($data->msl_tags) > 0)
                    <br>
                    <details class="collapse collapse-arrow word-card-collapser" id="original-keywords-panel">
                        <summary class="collapse-title">Originally assigned keywords 
                            <x-ri-information-line id="orginal-keywords-popup" class="info-icon"/>
                        </summary>
                        <div class="collapse-content word-card-parent">
                            @foreach ( $data->msl_tags as $keyword)
                                <div 
                                    class="word-card"
                                    data-highlight="tag"
                                    data-uris='{!! json_encode($keyword->msl_tag_msl_uris) !!}'
                                >
                                    {{ $keyword->msl_tag_string }}
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


                @if (sizeof($data->msl_original_keywords) > 0)
                    <br>
                    <details class="collapse collapse-arrow word-card-collapser" id="corresponding-keywords-panel">

                    <summary class="collapse-title">Corresponding MSL vocabulary keywords 
                        <x-ri-information-line id="corresponding-keywords-popup" class="info-icon"/>
                    </summary>
                    <div class="collapse-content word-card-parent" id="corresponding-keywords-container">
                        @foreach ( $data->msl_original_keywords as $keyword)
                            <div 
                                class="word-card"
                                data-uri="{{ $keyword->msl_original_keyword_uri }}"
                                data-highlight="text-keyword"
                                data-filter-link="/data-access?msl_enriched_keyword_uri[]={{ $keyword->msl_original_keyword_uri }}"
                            >
                                {{ $keyword->msl_original_keyword_label }}
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
                        target: '.word-card',
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
                                url: '/webservice/api/vocabularies' + "/term?uri=" + instance.reference.dataset.uri,
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

                @if (sizeof($data->msl_enriched_keywords))
                    <br>
                    <details class="collapse collapse-arrow word-card-collapser" open>
                    <summary class="collapse-title">MSL enriched keywords 
                        <x-ri-information-line id="enriched-keywords-popup" class="info-icon"/>
                    </summary>
                    <div class="collapse-content word-card-parent" id="enriched-keywords-container">
                        @foreach ( $data->msl_enriched_keywords as $keyword)
                            <div
                                class="word-card" 
                                data-associated-subdomains='["{{ implode(', ', $keyword->msl_enriched_keyword_associated_subdomains) }}"]'
                                data-uri="{{ $keyword->msl_enriched_keyword_uri }}"
                                data-filter-link="/data-access?msl_enriched_keyword_uri[]={{ $keyword->msl_enriched_keyword_uri }}"
                                data-highlight="text-keyword"
                                data-matched-child-uris='{!! json_encode($keyword->msl_enriched_keyword_match_child_uris) !!}'
                                data-sources='{!! json_encode($keyword->msl_enriched_keyword_match_locations) !!}'
                            >
                                {{ $keyword->msl_enriched_keyword_label }}
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
                            target: '.word-card',
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
                                    url: '/webservice/api/vocabularies' + "/term?uri=" + instance.reference.dataset.uri,
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

            <h3 class="border-none py-5 pt-10">Metadata</h3>
            
            @if ($data->msl_subdomains != '')
                <br>
                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">MSL original sub domains</h4>
                    <div class="word-card-parent justify-start">
                        @foreach ( $data->msl_subdomains as $keyword)
                            <div class="word-card">{{ $keyword['msl_subdomain'] }}</div>
                        @endforeach
                    </div>
                </div>
                <br>
                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">MSL enriched sub domains <x-ri-information-line id="enriched-subdomains-popup" class="info-icon"/></h4>
                    
                    <div class="word-card-parent justify-start">
                        @foreach ( $data->msl_subdomains as $keyword)
                            <div 
                                class="word-card" 
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

            @if ($data->msl_resource_type != '')
                <br>
                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">Resource Type</h4>
                    <div class="detail-entry-content">
                        <p class="">{{ $data->msl_resource_type }}</p>
                    </div>
                </div>
            @endif

            @if ($data->msl_source)
                <br>
                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">Source</h4>
                    <div class="detail-entry-content ">
                        @include('components.list-views.table-list',[
                            'entries' => [
                                $data->msl_source
                            ],
                            'withKeys' => false,
                            'textSize' => 'base'
                        ])
                    </div>
                </div>
            @endif

            @if ($data->msl_publisher != '')
                <br>
                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">Source publisher</h4>
                    <div class="detail-entry-content">
                        @include('components.list-views.table-list',[
                            'entries' => [
                                $data->msl_publisher
                            ],
                            'withKeys' => false,
                            'textSize' => 'base'
                        ])
                    </div>
                </div>
            @endif

            @if ($data->msl_doi)
                <br>
                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">DOI</h4>
                    <div class="detail-entry-content">
                        @include('components.list-views.table-list',[
                            'entries' => [
                                $data->msl_doi
                            ],
                            'withKeys' => false,
                            'textSize' => 'base'
                        ])
                    </div>
                </div>
            @endif

            @if (sizeof($data->msl_creators) > 0)
                <br>
                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">Creators</h4>
                    <div class="detail-entry-content">
                        @foreach ( $data->msl_creators as $creator)
                            @include('components.list-views.table-list',[
                                        'entries' => [
                                            $creator->getFullName(),
                                            implode(' ', preg_split('/(?=[A-Z])/', $creator->msl_creator_name_type)),
                                            implode(' | ', $creator->getAllAffilitationNames()),
                                            implode(' | ', $creator->getAllNameIdentifiers()),
                                        ],
                                        'withKeys' => false,
                                    ])
                        @endforeach
                    </div>
                </div>
            @endif

            @if (sizeof($data->msl_contributors) > 0)
            <br>

                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">Contributors</h4>
                    <div class="detail-entry-content">
                        @foreach ( $data->msl_contributors as $contributor)
                            @include('components.list-views.table-list',[
                                        'entries' => [
                                            $contributor->getFullName(),
                                            implode(' ', preg_split('/(?=[A-Z])/', $contributor->msl_contributor_name_type)),
                                            implode(' | ', $contributor->getAllAffilitationNames()),
                                            implode(' | ', $contributor->getAllNameIdentifiers()),
                                        ],
                                        'withKeys' => false,
                                    ])
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if ($data->msl_citation != '')
                <br>
                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">Citation</h4>
                    <div class="detail-entry-content">
                        <p class="text-sm">{!! $data->msl_citation !!}</p>
                    </div>
                </div>
            @endif

            @if (sizeof($data->msl_related_identifiers) > 0)
                <br>
                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">References</h4>
                    <div class="detail-entry-content">
                        @foreach ( $data->msl_related_identifiers as $entry)
                            @include('components.list-views.table-list',[
                                'entries' => $entry->getDisplayInformation(),
                                'withKeys' => false,
                            ])
                        @endforeach
                    </div>
                </div>
            @endif
            

            @if ($data->msl_dates)
                <br>
                <div class="detail-entry-div">  
                    <h4 class="detail-entry-title">Dates</h4>
                    <div class="detail-entry-content">
                        @php
                            $dataList = [];
                            foreach ($data->msl_dates as $key => $value) {
                                $dataList[$value->msl_date_type] = $value->msl_date_date;
                            }
                        @endphp

                        @include('components.list-views.table-list',[
                            'entries' => $dataList,
                            'withKeys' => true,
                        ])
                    </div>
                </div>
            @endif

            <br>
            <div class="detail-entry-div">
                <h4 class="detail-entry-title">Language</h4>
                <div class="detail-entry-content">
                    @if ($data->msl_language != '')
                        <p class="text-sm p-0">{{ $data->msl_language }}</p>                                        
                    @else
                        <p class="text-sm p-0 italic">- no language entry found -</p>                                        
                    @endif
                </div>
            </div>


            <br>
            @if (sizeof($data->msl_funding_references) > 0)                                 
                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">Funding References</h4>
                    <div class="detail-entry-content">
                        @foreach ($data->msl_funding_references as $fundingReference)
                            <div class="py-2">
                                @include('components.list-views.table-list',[
                                    'entries' => [
                                        "Funder Name" => $fundingReference->msl_funding_reference_funder_name,
                                        "Funder Identifier" => $fundingReference->msl_funding_reference_funder_identifier,
                                        "Scheme URI" => $fundingReference->msl_funding_reference_scheme_uri,
                                        "Award Number" => $fundingReference->msl_funding_reference_award_number,
                                        "Award Title" => $fundingReference->msl_funding_reference_award_title,
                                    ],
                                    'withKeys' => true,
                                ])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
                    

            @if (sizeof($data->msl_rights) > 0)
                <br>
                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">Rights</h4>
                    <div class="detail-entry-content">
                        @foreach ($data->msl_rights as $right)
                            @include('components.list-views.table-list',[
                                'entries' => [
                                    "Name" => $right->msl_right,
                                    "URI" => $right->msl_right_uri,
                                    "Identifier" => $right->msl_right_identifier,
                                    "Identifier Scheme" => $right->msl_right_identifier_scheme,
                                    "Scheme URI" => $right->msl_right_scheme_uri,
                                ],
                                'withKeys' => true,
                            ])
                        @endforeach
                    </div>
                </div>
            @endif

            <h3 class="border-none py-5 pt-10">Locations</h3>
            
            @if (sizeof($data->msl_geolocations) > 0 && $data->msl_geojson_featurecollection != '')
                <p class="italic text-center w-full">- no geo-locations found -</p>
            @endif

            @if (sizeof($data->msl_geolocations) > 0)
                <br>
                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">Geo location(s)</h4>
                    <div class="detail-entry-content">
                        @foreach ( $data->msl_geolocations as $locationPackage)
                            @foreach ($locationPackage as $location)
                                <p class="text-sm">{{ $location }}</p>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($data->msl_geojson_featurecollection != '')
                <br>
                <div class="detail-entry-div">
                    <h4 class="detail-entry-title">Spatial coordinates</h4>
                    <div class="detail-entry-content">
                        <div id="map" style="height: 300px;"></div>
                    </div>
                    <script>
                        function onEachFeature(feature, layer) {
                            if (feature.properties.name) {                                
                                var popupContent = `<h5>${feature.properties.name}</h5>`;

                                layer.bindPopup(popupContent);
                            }
                        }
                    
                        var features = <?php echo $data->msl_geojson_featurecollection; ?>;        				
                    
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

@push('vite')
    @vite(['resources/js/tooltip.js'])
@endpush

</x-layout_main>