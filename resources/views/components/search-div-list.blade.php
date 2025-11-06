<div class='mx-auto sm:p-4 w-full'>

    <div class="search-bar-container form-field-text p-0">

        <div class="search-bar-container-icon">
            <x-ri-search-line class="search-icon"/>
        </div>
        
        <form method="get" action="{{ request()->fullUrl() }}" class="w-full h-16">
            <input type="hidden" name="page" value="1" />
            <input 
                class="search-bar" 
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

    <div class="flex flex-col justify-around pt-6 gap-3">
        <div class="flex max-[470px]:flex-col items-center place-content-center gap-3">
            <div class="basis-1/2">
                <p class="inline italic"> {{ ucfirst($amountFound) }} found:</p>
                <h5 class="inline">{{ $result->getTotalResultsCount() }}</h5>
            </div>
    
            @if (isset($dpDropdown) && $dpDropdown)
                <div class="basis-1/2  ">
                    <form 
                        class="w-full flex flex-col sm:flex-row justify-end items-center place-content-center" 
                        method="get" 
                        action=""
                    >
                        <p class="italic w-30 text-center sm:text-end  pr-2" >Order by:</p>
                        <div class="min-w-64">
                            <div class="w-full">
                                <select 
                                    name="sort"
                                    onchange="this.form.submit()"
                                    class="select form-field-text focus:select-secondary w-full pr-9 bg-white">
                                        @foreach ([
                                            'score desc' => 'Relevance',
                                            'msl_citation asc' => 'Author Ascending',
                                            'msl_citation desc' => 'Author Descending',
                                            'msl_publication_date desc' => 'Publication date'
                                        ] as $key => $option)
                                            <option 
                                                value="{{ $key }}" 
                                            >
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="page" value="1" />
                    </form>
                </div>
            @endif
        </div>      

        <div>
            <div class="flex flex-col items-center place-content-center gap-2">            

                <div class="w-fit flex flex-row items-center place-content-center gap-3 ">
                    <h5 class="inline">Applied Filters  </h5>
                    @if ( sizeof($activeFiltersFrontend) > 0 )
                    
                        <a href="{{ route('data-access') }}" id="remove-all-popup">
                            <div class="
                                flex place-content-center 
                                hover-interactive
                                p-2
                                size-fit
                                ">
                                <x-ri-delete-bin-2-line  class="remove-all-icon" />
    
                            </div>
    
                            <script>
                                tippy('#remove-all-popup', {
                                    content: "remove all filters",
                                    placement: "right",
                                    theme: "msl"
                                });
                            </script>
                        </a>
                        
                    @endif
                </div>
                
                
                <div class="word-card-parent" id="active-filter-container"> 
    
                @if ( sizeof($activeFiltersFrontend) > 0 )
                        @foreach ( $activeFiltersFrontend as $filter )
    
                            <a href="{{ $filter['removeUrl'] }}" class="">
                                @include('components.word-card',[
                                    'word' => $filter['label'],
                                    'closeIcon' => true
                                ])
                            </a>
                        @endforeach
                        <script>
                            tippy.delegate('#active-filter-container', {
                                target: '.word-card',
                                content: "click to remove filter",
                                theme: "msl",
                                placement: "right"
                            });
                        </script>
                @else
                
                    <h6 class="italic">- no filter applied -</h6>
                @endif
            </div>

            <div class="divide-y w-1/2 flex flex-col place-self-center py-3 divide-primary-700 opacity-50">
                <div></div>
                <div></div>
            </div>
            </div>
            
        </div>


    </div>
</div>