{{-- 


Variables

filterDataPath:  (string) the path to the file which contains the data for the filtertree


--}}
 
<div class="border-r-2 w-3/12 min-w-80 bg-base-300 flex flex-col place-items-center justify-self-center">

    <div class='w-full mx-auto p-4 flex flex-col '>
        <div class="relative flex items-center w-full h-12 rounded-lg shadow-lg overflow-hidden">
            
            <div class="grid place-items-center h-full w-12 bg-primary-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
    
            <input
            class="peer h-full w-full outline-none text-sm pr-2 pl-2"
            type="text"
            id="search-filters"
            placeholder="Search Filters.." /> 

        </div>
            @if (isset($pbDetail) && $pbDetail)
                <div class="pt-6 flex flex-col">
                    <div class="flex flex-col justify-center place-content-center">
                        <div class="form-control w-full">
                            <label class="label cursor-pointer justify-center space-x-2">
                                <input type="checkbox" id="filterTreeToggleInterpreted" checked="checked" class="checkbox" />
                                <span class="label-text">MSL enriched keywords <i id="enriched-keywords-popup">i</i></span>
                            </label>
                            <script>
                                tippy('#enriched-keywords-popup', {
                                    content: "MSL enriched keywords include MSL vocabulary terms corresponding to the keywords originally assigned by the authors, parent terms, and MSL vocabulary terms corresponding to words used in the data publication title and abstract. In enriching keyword sets like this, MSL strives to make datasets more findable. See anything odd? Contact us at epos.msl.data@uu.nl. MSL vocabularies available on GitHub - see top tab ‘vocabularies'.",
                                    placement: "right",
                                    theme: "msl"
                                });
                            </script>
                        </div>
        
                        <div class="form-control w-full">
                            <label class="label cursor-pointer justify-center space-x-2">
                                <input type="checkbox" id="filterTreeToggleOriginal" class="checkbox" />
                                <span class="label-text">MSL original keywords <i id="original-keywords-popup">i</i></span>
                            </label>
                            <script>
                                tippy('#original-keywords-popup', {
                                    content: "Lists only the MSL vocabulary terms corresponding to the keywords originally assigned by the authors.",
                                    placement: "right",
                                    theme: "msl"
                                });
                            </script>
                        </div>

                        <div class="divide-y w-1/2 flex flex-col place-self-center pt-2 pb-2 divide-primary-700 opacity-50">
                            <div></div>
                            <div></div>
                        </div>

                        <div class="form-control w-full ">
                            <label class="label cursor-pointer justify-center space-x-2">
                                    <input type="checkbox" class="checkbox checkbox-sm" id="hide_empty_terms" />
                                    <span class="label-text place-content-center">Hide empty terms</span>
                            </label>
                        </div>
                    </div>

    
                    <div class="p-2">
                        <div class="w-full flex place-content-evenly">
                            <a href="#" id="expand_all" title="expand all nodes">
                                <button class="btn btn-sm">
                                    expand all
                                </button>
                            </a>
                            <a href="#" id="close_all" title="close all nodes">
                                <button class="btn btn-sm">
                                    close all
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        <div>
            
            
            <h2 class="pt-4">Filters</h1>

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