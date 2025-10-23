@section('title', 'Data publication')
<x-layout_main>

    <div class="mainContentDiv">

        {{-- a general no small width view notification --}}
        @include('components.no_mobile_view', [
            'breakpoint' => 'md'
        ])

        <div class="noMobileView_wideScreenDiv">

            <div class="absolute">
                @session('data_publication_active_search')
                    @include('components.tab-links',[
                        // 'categoryName'  => 'Results',
                        'includeIcon'   => 'goBack',
                        'routes'        => array(
                                'Back to search results'   => $value,
                        )
                    ])
                @endsession
            </div>


            <div class="tabLinksParent">
 
                @include('components.tab-links',[
                    // 'categoryName'  => 'Sections',
                    'routes'        => array(
                            'Metadata'  => route("data-publication-detail", ['id' => $data['name']]),
                            'Files'     => route("data-publication-detail-files", ['id' => $data['name']])
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
                                        @foreach ( $data['msl_creators'] as $authorKey => $author )
                                            {{ $author["msl_creator_family_name"]}} {{ $author["msl_creator_given_name"] }} 
                                                {{-- a little divider between names --}}
                                                @if (sizeof($data['msl_creators']) -1 != $authorKey )
                                                    |
                                                @endif
                                        @endforeach
                                    </p>
                                    @if (array_key_exists("msl_publisher", $data))
                                    <p class="italic text-center">{{ $data['msl_publisher'] }} </p>
                                    @endif
                                    @if (array_key_exists("msl_publication_year", $data))
                                    <p class="italic text-center">({{ $data['msl_publication_year'] }})</p>
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

                                        @foreach ([
                                            'msl_description_abstract_annotated' => 'Abstract',
                                            'msl_description_methods' => 'Methods',
                                            'msl_description_other' => 'Other',
                                            'msl_description_series_information' => 'Series Information',
                                            'msl_description_table_of_contents' => 'Content',
                                            'msl_description_technical_info' => 'Technical Information',
                                            ] 
                                            as $id => $title)

                                            @if (array_key_exists( $id , $data) 
                                            && $data[$id] != ""
                                            )
                                            
                                                <input type="radio" name="my_tabs_2" role="tab" class="tab hover:bg-secondary-100" aria-label="{{ $title }}" @if ($id =='msl_description_abstract_annotated') checked='checked' @endif/>
                                                <div role="tabpanel" class="tab-content tabs-div bg-primary-100 whitespace-normal border-primary-300 rounded-box">
                                                    {!! $data[$id] !!}
                                                </div>
                                            @endif

                                        @endforeach

                                    </div>
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
                                                        data-uris='{!! json_encode($keyword['msl_tag_msl_uris']) !!}'
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

                                
                                @if (array_key_exists("msl_subdomains", $data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">MSL original sub domains</h4>
                                    <div class="wordCardParent">
                                        {{-- hover behaviour: highlights all related tags above --}}
                                        @foreach ( $data['msl_subdomains'] as $keyword)
                                            <div class="wordCard">{{ $keyword['msl_subdomain'] }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                <h4 class="detailEntrySub1 bg-red-500">missing: MSL original sub domains</h4>
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
                                @else
                                <h4 class="detailEntrySub1 bg-red-500">missing: MSL enriched sub domains</h4>
                                @endif

                                @if (array_key_exists("msl_resource_type",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Resource Type</h4>
                                    <div class="detailEntrySub2">
                                        <p class="pt-2 detailEntrySub2">{{ $data['msl_resource_type'] }}</p>
                                    </div>
    
                                </div>
                                @endif

                                @if (array_key_exists("msl_source",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Source</h4>
                                    <div class="detailEntrySub2">
                                        <a  href="{{ $data['msl_source'] }}" target="_blank">{{ $data['msl_source'] }}</a>
                                        @if (array_key_exists('msl_alternate_identifiers', $data))
                                            @foreach ( $data['msl_alternate_identifiers'] as $altId)
                                                @if (filter_var($altId['msl_alternate_identifier'], FILTER_VALIDATE_URL) )
                                                    <a class="pt-2" href="{{ $altId['msl_alternate_identifier'] }}" target="_blank">{{ $altId['msl_alternate_identifier'] }}</a>
                                                @else
                                                    <p class="pt-2 detailEntrySub2">{{ $altId['msl_alternate_identifier'] }}</p>
                                                @endif 
                                            @endforeach
                                        @endif
                                    </div>
    
                                </div>
                                @endif

                                @if (array_key_exists("msl_publisher",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Source publisher</h4>
                                    <p class="detailEntrySub2">{{ $data['msl_publisher'] }}</p>
                                </div>
                                @else
                                <h4 class="detailEntrySub1 bg-red-500">missing: Source publisher</h4>
                                @endif

                                @if (array_key_exists("msl_doi",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">DOI</h4>
                                    <p class="detailEntrySub2">{{ $data['msl_doi'] }}</p>
                                </div>
                                @else
                                <h4 class="detailEntrySub1 bg-red-500">missing: DOI</h4>
                                @endif

                                @if (array_key_exists("msl_creators",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Creators</h4>
                                    <div class="detailEntrySub2 dividers flex flex-col gap-4">
                                        @foreach ( $data['msl_creators'] as $creator)
                                            @php
                                            $dataList = [];
                                            
                                            //names
                                            if ($creator["msl_creator_family_name"] != '') {
                                                $dataList[] = $creator["msl_creator_family_name"].' '.$creator["msl_creator_given_name"];
                                            } else {
                                                $dataList[] = $creator["msl_creator_name"];
                                            }

                                            //creator type
                                            if (array_key_exists('msl_creator_name_type', $creator)) {
                                                $dataList[] = implode(' ', preg_split('/(?=[A-Z])/', $creator['msl_creator_name_type']));
                                            }

                                            //affilitation
                                            if (array_key_exists("msl_creator_affiliations_names",$creator)) {
                                                $preppedString = '';
                                                foreach ($creator['msl_creator_affiliations_names'] as $key => $affiliation) {
                                                    $preppedString = $preppedString.$affiliation;
                                                    if (sizeof($creator['msl_creator_affiliations_names']) -1 != $key ){
                                                        $preppedString = $preppedString.' | ';
                                                    }
                                                }
                                                $dataList[] = $preppedString;
                                            }

                                            // name identifiers
                                            if (array_key_exists("msl_creator_name_identifiers",$creator) && array_key_exists("msl_creator_name_identifiers_schemes",$creator)) {
                                                foreach ($creator['msl_creator_name_identifiers'] as $key => $value) {
                                                    if ($creator['msl_creator_name_identifiers_schemes'][$key] == 'https://orcid.org/') {
                                                        $dataList[] = $creator['msl_creator_name_identifiers_schemes'][$key].$value;
                                                    }else {
                                                        $dataList[] = $value;
                                                    }
                                                }
                                            }
                                        @endphp

                                        @include('components.list-views.table-list',[
                                                    'entries' => $dataList,
                                                    'withKeys' => false,
                                                ])
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if (array_key_exists("msl_contributors",$data))
                                <br>

                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Contributors</h4>
                                    <div class="detailEntrySub2 dividers flex flex-col gap-4">
                                        @foreach ( $data['msl_contributors'] as $contributor)
                                            @php
                                                $dataList = [];
                                                
                                                //names
                                                if ($contributor["msl_contributor_family_name"] != '') {
                                                    $dataList[] = $contributor["msl_contributor_family_name"].' '.$contributor["msl_contributor_given_name"];
                                                } else {
                                                    $dataList[] = $contributor["msl_contributor_name"];
                                                }

                                                //contributor type
                                                if (array_key_exists('msl_contributor_type', $contributor)) {
                                                    $dataList[] = implode(' ', preg_split('/(?=[A-Z])/', $contributor['msl_contributor_type']));
                                                }

                                                //affilitation
                                                if (array_key_exists("msl_contributor_affiliations_names",$contributor)) {
                                                    $preppedString = '';
                                                    foreach ($contributor['msl_contributor_affiliations_names'] as $key => $affiliation) {
                                                        $preppedString = $preppedString.$affiliation;
                                                        if (sizeof($contributor['msl_contributor_affiliations_names']) -1 != $key ){
                                                            $preppedString = $preppedString.' | ';
                                                        }
                                                    }
                                                    $dataList[] = $preppedString;
                                                }

                                                // name identifiers
                                                if (array_key_exists("msl_contributor_name_identifiers",$contributor) && array_key_exists("msl_contributor_name_identifiers_schemes",$contributor)) {
                                                    foreach ($contributor['msl_contributor_name_identifiers'] as $key => $value) {
                                                        if ($contributor['msl_contributor_name_identifiers_schemes'][$key] == 'https://orcid.org/') {
                                                            $dataList[] = $contributor['msl_contributor_name_identifiers_schemes'][$key].$value;
                                                        }else {
                                                            $dataList[] = $value;
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @include('components.list-views.table-list',[
                                                        'entries' => $dataList,
                                                        'withKeys' => false,
                                                    ])
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                @if (array_key_exists("msl_citation",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Citation</h4>
                                    <p class="detailEntrySub2 text-sm">{!! $data['msl_citation'] !!}</p>
                                </div>
                                @endif

                                @if (array_key_exists( 'msl_related_identifiers', $data ))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">References</h4>
                                    <div class="detailEntrySub2 dividers flex flex-col gap-4">
                                            @foreach ( $data['msl_related_identifiers'] as $entry)
                                                <div class="">
                                                    @php
                                                        $dataList = [];
                                                        if ($entry['msl_related_identifier_type'] == 'DOI') {
                                                            $dataList[] = "https://doi.org/".$entry['msl_related_identifier'];
                                                        }else {
                                                            $dataList[] = $entry['msl_related_identifier'];
                                                            if(array_key_exists( 'msl_related_identifier_type', $entry )){
                                                                $dataList[] = $entry['msl_related_identifier_type'];
                                                            }

                                                            if(array_key_exists( 'msl_related_identifier_relation_type', $entry )){
                                                                $dataList[] = $entry['msl_related_identifier_relation_type'];
                                                            }
                                                        }
                                                    @endphp
                                                    @include('components.list-views.table-list',[
                                                        'entries' => $dataList,
                                                        'withKeys' => false,
                                                    ])
                                                   
                                                </div>

                                            @endforeach
                                    </div>
                                </div>
                                @endif
                                

                                @if (array_key_exists("msl_dates",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">  
                                    <h4 class="detailEntrySub1">Dates</h4>
                                        @php
                                            $dataList = [];
                                            foreach ($data['msl_dates'] as $key => $value) {
                                                $dataList[$value['msl_date_type']] = $value['msl_date_date'];
                                            }
                                        @endphp

                                        @include('components.list-views.table-list',[
                                            'entries' => $dataList,
                                            'withKeys' => true,
                                        ])
                                </div>
                                @endif

                                @if (array_key_exists("msl_language",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Language</h4>
                                    <div class="detailEntrySub2">
                                        @if ($data['msl_language'] != '')
                                            <p class="text-sm p-0">{{ $data['msl_language'] }}</p>                                        
                                        @else
                                            <p class="text-sm p-0 italic">No language entry found</p>                                        
                                        @endif
                                    </div>
                                </div>
                                @endif


                                <br>
                                @if (array_key_exists("msl_funding_references",$data))                                 
                                    <div class="detailEntryDiv flex flex-row">
                                        <h4 class="detailEntrySub1">Funding References</h4>
                                        <div class="detailEntrySub2 dividers">

                                            @foreach ($data['msl_funding_references'] as $entries)
                                                @php
                                                    $dataList = [];
                                                    foreach ($entries as $key => $value) {
                                                        if (
                                                            $key != 'msl_funding_reference_funder_identifier_type'
                                                            &&
                                                            $key != 'msl_funding_reference_award_uri'
                                                            &&
                                                            $value != ''
                                                        ) {
                                                            $keys = explode('_', $key);
                                                            unset($keys[0]);
                                                            unset($keys[1]);
                                                            unset($keys[2]);
                                                            unset($keys[3]);

                                                            foreach ($keys as $pos => $key_s) {
                                                                $keys[$pos] = ucfirst($key_s);
                                                            };
                                                            
                                                            $key = implode(' ', $keys);
                                                            if ($key == 'Name') {
                                                                $dataList = array($key => $value) + $dataList;
                                                            } else {
                                                                $dataList[$key] = $value;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <div class="py-2">
                                                    @include('components.list-views.table-list',[
                                                        'entries' => $dataList,
                                                        'withKeys' => true,
                                                    ])
                                                </div>
                                                

                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                

                            @if (array_key_exists("msl_rights",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Rights</h4>
                                    <div class="detailEntrySub2 dividers">
                                        @foreach ($data['msl_rights'] as $entries)
                                            @php
                                                $dataList = [];

                                                foreach ($entries as $key => $value) {
                                                    $keys = explode('_', $key);
                                                    unset($keys[0]);
                                                    unset($keys[1]);
                                                    if(sizeof($keys) == 0){
                                                        $keys[0] = 'Name';
                                                    } 

                                                    foreach ($keys as $pos => $key_s) {

                                                        $keys[$pos] = ucfirst($key_s);

                                                    };
                                                    $dataList[implode(' ', $keys)] = $value;
                                                }
                                            
                                            @endphp

                                            @include('components.list-views.table-list',[
                                                'entries' => $dataList,
                                                'withKeys' => true,
                                            ])
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (array_key_exists("msl_geolocations",$data))
                                <br>
                                <div class="detailEntryDiv flex flex-row">
                                    <h4 class="detailEntrySub1">Geo location(s)</h4>
                                    <div class="detailEntrySub2">
                                        @foreach ( $data['msl_geolocations'] as $locationPackage)
                                            @foreach ($locationPackage as $location)
                                                <p class="text-sm">{{ $location }}</p>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (array_key_exists("msl_geojson_featurecollection",$data))
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