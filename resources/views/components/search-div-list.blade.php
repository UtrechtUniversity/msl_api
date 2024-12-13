<div class='mx-auto p-4 w-full'>
    <div class="relative flex items-center w-full h-12 rounded-lg shadow-lg overflow-hidden">

        <div class="grid place-items-center h-full w-12 ">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        
        <form method="get" action="{{ request()->fullUrl() }}" class="w-full h-16">
            <input type="hidden" name="page" value="1" />
            <input 
                class="peer search-bar pl-2" 
                type="text" 
                id="search" 
                placeholder="Search {{ $searchFor }}.." 
                name="query[]" />
                @if(true)
                @foreach($queryParams as $param => $values)
                    @if (is_array($values))
                        @foreach ($values as $value)
                        <input type="hidden" name="{{ $param }}[]" value="{{ $value }}">    
                        @endforeach
                    @endif
                @endforeach
                @endif
        </form>
    </div>

    <div class="flex justify-between p-4 px-10">
                                   
        {{-- styling needs some tuning regarding long filter text elements --}}
        <div class="flex flex-col text-wrap">            

                <h5 class="pb-2">Applied Filters  
                    @if ( sizeof($activeFiltersFrontend) > 0 )
                        <a href="{{ route('data-access') }}">
                            <x-ri-delete-bin-2-line  class="remove-all-icon" id="remove-all-popup"/>
                            <script>
                                tippy('#remove-all-popup', {
                                    content: "remove all filters",
                                    placement: "right",
                                    theme: "msl"
                                });
                            </script>
                        </a>
                    @endif
                </h5>

            @if ( sizeof($activeFiltersFrontend) > 0 )
                <div class="wordCardParent" id="active-filter-container"> 
                    @foreach ( $activeFiltersFrontend as $filter )
                        <a href="{{ $filter['removeUrl'] }}" class="wordCard no-underline">
                            <x-ri-close-line class="close-icon"/>
                            {{ $filter['label'] }}
                        </a>
                    @endforeach
                    <script>
                        tippy.delegate('#active-filter-container', {
                            target: '.wordCard',
                            content: "click to remove filter",
                            theme: "msl",
                            placement: "right"
                        });
                    </script>
                </div>
            @endif

        </div>


        <div>
            <h5>{{ $result->getTotalResultsCount() }} {{ $amountFound }} found</h5>
        </div>
                                    

        @if (isset($dpDropdown) && $dpDropdown)
            {{-- worthy to be a component? --}}
            <div>
                <form class="min-w-64 mx-auto flex justify-between content-center" method="get" action="">
                    <label for="sort" class="self-center">
                    <h5 >Order by</h5>
                    </label>
                    <select id="sort" name="sort" onchange="this.form.submit()" class="p-2.5 text-sm rounded-lg">
                        <option value="score desc" @if ($sort == 'score desc') {{ 'selected' }} @endif>Relevance</option>
                        <option value="msl_citation asc" @if ($sort == 'msl_citation asc') {{ 'selected' }} @endif>Author Ascending</option>
                        <option value="msl_citation desc" @if ($sort == 'msl_citation desc') {{ 'selected' }} @endif>Author Descending</option>
                        <option value="msl_publication_date desc" @if ($sort == 'msl_publication_date desc') {{ 'selected' }} @endif>Publication date</option>
                    </select>
                    <input type="hidden" name="page" value="1" />
                </form>
            </div>

        @endif

    </div>
</div>