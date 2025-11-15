@section('title', 'Data publication')
<x-layout_main>



    <div class="mainContentDiv">
        
        {{-- @php
            dd($data);
        @endphp --}}

        {{-- a general no small width view notification --}}
        @include('components.no_mobile_view')

        <div class="noMobileView_wideScreenDiv">

            <div class="absolute">

                @session('data_publication_active_search')
                    @include('components.tabLinks', [
                        'includeIcon'   => 'goBack',
                        'routes'        => array(
                                'Back to search results'   => $value,
                        )
                    ])
                @endsession
            </div>

            <div class="tabLinksParent">
 
                @include('components.tabLinks',[
                    'routes'        => array(
                            'Metadata'  => route("data-publication-detail", ['id' => $data->name]),
                            'Files'     => route("data-publication-detail-files", ['id' => $data->name])
                    ),
                    'routeActive'   => route("data-publication-detail", ['id' => $data->name])
                ])
            </div>

            <div class="listMapDetailDivParent">
                    <div class="detailDiv dividers">
                                <div class="detailEntryDiv">
                                    <h2 class="">Data Publication</h2>
                                    <h1 class="text-lg">{!! $data->msl_title_annotated !!}</h1>
                                    @if (count($data->msl_creators) > 0)
                                        <p class="italic text-center">
                                            @foreach ( $data->msl_creators as $authorKey => $author )
                                                {{ $author->getFullName() }} 
                                                    @if (count($data->msl_creators) -1 != $authorKey )
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

                                <div class="detailEntryDiv">
                                    <h3 class="detailEntrySub1 w-full text-center">Descriptions</h3>

                                    <div role="tablist" class="
                                    tabs 
                                    tabs-lifted
                                    p-4 bg-primary-200 
                                    text-sm
                                    sm:text-base
                                    whitespace-nowrap
                                    ">

                                        @php
                                            $first = true;
                                        @endphp
                                        @foreach ([
                                            'msl_description_abstract_annotated' => 'Abstract',
                                            'msl_description_methods_annotated' => 'Methods',
                                            'msl_description_other_annotated' => 'Other',
                                            'msl_description_series_information_annotated' => 'Series Information',
                                            'msl_description_table_of_contents_annotated' => 'Content',
                                            'msl_description_technical_info_annotated' => 'Technical Information',
                                            ] as $property => $title)

                                            @if ($data->{$property} != "")                                            
                                                <input type="radio" name="my_tabs_2" role="tab" class="tab hover:bg-secondary-100" aria-label="{{ $title }}" @if ($first) checked='checked' @endif/>
                                                <div role="tabpanel" class="tab-content tabs-div bg-primary-100 whitespace-normal border-primary-300 rounded-box">
                                                    {!! $data->{$property} !!}
                                                </div>
                                                @php 
                                                    $first = false; 
                                                @endphp
                                            @endif
                                        @endforeach
                                    </div>
                                </div >
                                
                                <div class="detailEntryDiv">
                                    <h4 class="text-left">Keywords</h4>

                                    @if (count($data->msl_tags) > 0)
                                        <br>
                                        <details class="collapse collapse-arrow wordCardCollapser" id="original-keywords-panel">
                                            <summary class="collapse-title">Originally assigned keywords 
                                                <x-ri-information-line id="orginal-keywords-popup" class="info-icon"/>
                                            </summary>
                                            <div class="collapse-content wordCardParent">
                                                @foreach ( $data->msl_tags as $keyword)
                                                    <div 
                                                        class="wordCard"
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
                                        <details class="collapse collapse-arrow wordCardCollapser" id="corresponding-keywords-panel">

                                        <summary class="collapse-title">Corresponding MSL vocabulary keywords 
                                            <x-ri-information-line id="corresponding-keywords-popup" class="info-icon"/>
                                        </summary>
                                        <div class="collapse-content wordCardParent" id="corresponding-keywords-container">
                                            @foreach ( $data->msl_original_keywords as $keyword)
                                                <div 
                                                    class="wordCard"
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

                                    @if (count($data->msl_enriched_keywords) > 0)
                                    <br>
                                    <details class="collapse collapse-arrow wordCardCollapser" open>
                                    <summary class="collapse-title">MSL enriched keywords 
                                        <x-ri-information-line id="enriched-keywords-popup" class="info-icon"/>
                                    </summary>
                                    <div class="collapse-content wordCardParent" id="enriched-keywords-container">
                                        @foreach ( $data->msl_enriched_keywords as $keyword)
                                            <div
                                                class="wordCard" 
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

                                @if (count($data->msl_subdomains_original) > 0)
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">MSL original sub domains</h4>
                                    <div class="wordCardParent">
                                        {{-- hover behaviour: highlights all related tags above --}}
                                        @foreach ( $data->msl_subdomains_original as $domain)
                                            <div class="wordCard">{{ $domain['msl_subdomain_original'] }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if (count($data->msl_subdomains_interpreted) > 0)
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">MSL enriched sub domains <i id="enriched-subdomains-popup">i</i></h4>
                                    <div class="wordCardParent">
                                        {{-- hover behaviour: highlights all related tags above --}}
                                        @foreach ( $data->msl_subdomains_interpreted as $domain)
                                            <div 
                                                class="wordCard" 
                                                data-toggle="domain-highlight"
                                                data-domain="{{ $domain['msl_subdomain_interpreted'] }}"                                                                                        
                                            >
                                                {{ $domain['msl_subdomain_interpreted'] }}
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
                                

                                @if ($data->msl_source != "")
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Source</h4>
                                    <a class="detailEntrySub2" href="{{ $data->msl_source }}" target="_blank">{{ $data->msl_source }}</a>
                                </div>
                                @endif

                                @if ($data->msl_publisher != "")
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Source publisher</h4>
                                    <p class="detailEntrySub2">{{ $data->msl_publisher }}</p>
                                </div>                                
                                @endif

                                @if ($data->msl_doi != "")
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">DOI</h4>
                                    <p class="detailEntrySub2">{{ $data->msl_doi }}</p>
                                </div>
                                @endif

                                @if (count($data->msl_creators) > 0)
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Creators</h4>
                                    <div class="detailEntrySub2 dividers flex flex-col gap-4">
                                        @foreach ( $data->msl_creators as $creator)
                                            <div>
                                                <p class="text-sm p-0">{{ $creator->getFullName() }}</p>

                                                @if (count($creator->affiliations) > 0)
                                                    <p class="text-sm p-0">
                                                        {{ implode(' | ', $creator->getAffilitationNames()) }}                                                        
                                                    </p>
                                                @endif

                                                @if (count($creator->nameIdentifiers) > 0)                                            
                                                    @foreach ( $creator->nameIdentifiers as $nameIdentifier )
                                                        <div class="flex flex-row w-96">
                                                            <p class="text-sm p-0">{{ $nameIdentifier->msl_creator_name_identifiers_scheme }}:</p>
                                                            <p class="text-sm p-0">{{ $nameIdentifier->msl_creator_name_identifier }}</p>
                                                        </div>
                                                    @endforeach
                                                @endif

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if (count($data->msl_contributors) > 0)
                                <br>

                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Contributors</h4>
                                    <div class="detailEntrySub2 dividers flex flex-col gap-4">
                                        @foreach ( $data->msl_contributors as $contributor)
                                            <div>
                                                <p class="text-sm p-0">{{ $contributor->msl_contributor_name }}</p>
                                                <p class="text-sm p-0">{{ $contributor->msl_contributor_type }}</p>

                                                @if (count($contributor->affiliations) > 0)
                                                    <p class="text-sm p-0">
                                                        {{ implode(' | ', $contributor->getAffilitationNames()) }}                                                        
                                                    </p>
                                                @endif

                                                @if (count($contributor->nameIdentifiers) > 0)
                                                    @foreach ( $contributor->nameIdentifiers as $nameIdentifier )
                                                        <div class="flex flex-row w-96">
                                                            <p class="text-sm p-0">{{ $nameIdentifier->msl_creator_name_identifiers_scheme }}:</p>
                                                            <p class="text-sm p-0">{{ $nameIdentifier->msl_creator_name_identifier }}</p>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                @if (count($data->msl_related_identifiers) > 0)
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">References</h4>
                                    <div class="detailEntrySub2 dividers flex flex-col gap-4">
                                        @foreach ( $data->msl_related_identifiers as $relatedIdentifier)
                                            <div>
                                                <p class="text-sm p-0">{{ $relatedIdentifier->msl_related_identifier }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif                                
                                
                                @if ($data->msl_citation != "")
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Citation</h4>
                                    <p class="detailEntrySub2 text-sm">{!! $data->msl_citation !!}</p>
                                </div>
                                @endif

                                @if (count($data->msl_dates) > 0)
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Dates</h4>
                                    <div class="detailEntrySub2 flex flex-col justify-items-start">
                                        @foreach ( $data->msl_dates as $date)
                                            <div class="flex flex-row justify-between w-3/4">
                                                <p class="text-sm p-0">{{ $date->msl_date_type }}:</p>
                                                <p class="text-sm p-0">{{ $date->msl_date_date }}</p>
                                            </div>                                    
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if ($data->msl_language != "")
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Language</h4>
                                    <div class="detailEntrySub2">
                                        <p class="text-sm p-0">{{ $data->msl_language }}</p>    
                                    </div>
                                </div>
                                @endif

                                @if (count($data->msl_funding_references) > 0)
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Funding References</h4>
                                    <div class="detailEntrySub2 dividers">
                                        @foreach ( $data->msl_funding_references as $fundingReference)
                                            <div>
                                                @if ($fundingReference->msl_funding_reference_funder_name != "")
                                                    <p class="text-sm p-0">Funder name: {{ $fundingReference->msl_funding_reference_funder_name }}</p>
                                                @endif
                                                @if ($fundingReference->msl_funding_reference_funder_identifier != "")
                                                    <p class="text-sm p-0">Funder identifier: {{ $fundingReference->msl_funding_reference_funder_identifier }}</p>
                                                @endif
                                                @if ($fundingReference->msl_funding_reference_funder_identifier_type != "")
                                                    <p class="text-sm p-0">Funder identifier type: {{ $fundingReference->msl_funding_reference_funder_identifier_type }}</p>
                                                @endif
                                                @if ($fundingReference->msl_funding_reference_scheme_uri != "")
                                                    <p class="text-sm p-0">Funder reference scheme uri: {{ $fundingReference->msl_funding_reference_scheme_uri }}</p>
                                                @endif
                                                @if ($fundingReference->msl_funding_reference_award_number != "")
                                                    <p class="text-sm p-0">Award number: {{ $fundingReference->msl_funding_reference_award_number }}</p>
                                                @endif
                                                @if ($fundingReference->msl_funding_reference_award_uri != "")
                                                    <p class="text-sm p-0">Award uri: {{ $fundingReference->msl_funding_reference_award_uri }}</p>
                                                @endif
                                                @if ($fundingReference->msl_funding_reference_award_title != "")
                                                    <p class="text-sm p-0">Award title: {{ $fundingReference->msl_funding_reference_award_title }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if (count($data->msl_rights) > 0)
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Rights</h4>
                                    <div class="detailEntrySub2">
                                        @foreach ( $data->msl_rights as $right)
                                            <p class="text-sm p-0">{{ $right->msl_right }}</p>                                             
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if ($data->msl_datacite_version != "")
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Datacite version</h4>
                                    <div class="detailEntrySub2">
                                            <p class="text-sm p-0 ">{{ $data->msl_datacite_version }}</p>                                             
                                    </div>
                                </div>
                                @endif

                                @if (count($data->msl_geolocations) > 0)
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Geo location(s)</h4>
                                    <div class="detailEntrySub2">
                                        @foreach ( $data->msl_geolocations as $geolocation)
                                            <p class="text-sm">{{ $geolocation['msl_geolocation'] }}</p>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if ($data->msl_geojson_featurecollection != "")
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Spatial coordinates</h4>
                                    <div class="detailEntrySub2">
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
        </div>
       
        


    </div>

@push('vite')
    @vite(['resources/js/tooltip.js'])
@endpush


</x-layout_main>