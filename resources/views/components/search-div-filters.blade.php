
 
<div class="w-80 bg-base-200 flex flex-col place-items-center justify-self-center">

    <h2 class="py-4">Filters</h1>

    <div class='w-full flex flex-col px-6'>

        <div class="search-bar-container form-field-text p-0 m-0 ">
            <div class="search-bar-container-icon">
                <x-ri-search-line class="search-icon"/>
            </div>
            <input
            class="search-bar"
            type="text"
            id="search-filters"
            placeholder="Search Filters..." /> 
        </div>

        <div class="flex flex-col gap-3 pt-6">

            @if (isset($pbDetail) && $pbDetail)

                <div class="bg-primary-100">
                    @php
                        $titleBold = true;
                    @endphp
                    <x-forms.radio-select

                        title=""
                        sectionName='selectInterpretation'
                        titleBold=''
                        :options="[
                                'MSL enriched keywords', 
                                'MSL original keywords'
                            ]"
                        :ids="[
                                'filterTreeToggleInterpreted', 
                                'filterTreeToggleOriginal'
                            ]" 
                        :infoIconsIds="[
                                'enriched-keywords-popup',
                                'original-keywords-popup'
                            ]"
                        :asCol=true
                    />
                </div>



                <script>
                    tippy('#enriched-keywords-popup', {
                        content: "MSL enriched keywords include MSL vocabulary terms corresponding to the keywords originally assigned by the authors, parent terms, and MSL vocabulary terms corresponding to words used in the data publication title and abstract. In enriching keyword sets like this, MSL strives to make datasets more findable. See anything odd? Contact us at epos.msl.data@uu.nl. MSL vocabularies available on GitHub - see top tab ‘vocabularies'.",
                        placement: "right",
                        theme: "msl"
                    });
                </script>
                <script>
                    tippy('#original-keywords-popup', {
                        content: "Lists only the MSL vocabulary terms corresponding to the keywords originally assigned by the authors.",
                        placement: "right",
                        theme: "msl"
                    });
                </script>



                <div class="bg-primary-100 w-full">
                    
                    <x-forms.check-box
                    title=""
                    sectionName='EmptyTerms'
                    :options="['Hide empty terms']"
                    :ids="['hide_empty_terms']" 
                    />
                </div>
            @endif


            <div class="px-2 py-3 w-full">
                <div class="w-full flex place-content-evenly">
                    <a href="#" id="expand_all" title="expand all nodes">
                        <button class="btn btn-sm w-20">
                            expand all
                        </button>
                    </a>
                    <a href="#" id="close_all" title="close all nodes">
                        <button class="btn btn-sm w-20">
                            close all
                        </button>
                    </a>
                </div>
            </div>
        </div>

        <div class="divide-y w-1/2 flex flex-col place-self-center py-3 divide-primary-700 opacity-50">
            <div></div>
            <div></div>
        </div>


        <div class="pb-10">
            
            @if (isset($pbDetail) && $pbDetail)
                <div id="jstree-interpreted" class="text-wrap pt-4"></div>
                <div id="jstree-original" class="text-wrap pt-4" style="display: none;"></div>
                <script>
                    var dataInterpreted = @php echo File::get(base_path($filterDataPath)) @endphp;                    
                    var dataOriginal = @php echo File::get(base_path('public/original.json')) @endphp;
                    var facets = @php echo json_encode($result->getFacets()); @endphp;
                    var activeFilters = @php echo json_encode($activeFilters); @endphp;
                    var activeNodes = [];
                </script>
                @push('vite')
                    @vite(['resources/js/jstree.js', 'resources/js/filters-menu.js'])
                @endpush
            @else
                <div id="jstree-laboratories" class="text-wrap pt-4"></div>
                <script>
                    var dataLaboratories = @php echo File::get(base_path( $filterDataPath )) @endphp;
                    var facets = @php echo json_encode($result->getFacets()); @endphp;
                    var activeFilters = @php echo json_encode($activeFilters); @endphp;
                    var activeNodes = [];
            
                </script>
                @push('vite')
                    @vite(['resources/js/jstree.js', 'resources/js/filters-menu-labs.js'])
                @endpush
            @endif

        </div>

    </div>

    

</div>