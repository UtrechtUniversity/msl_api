@section('title', 'Keyword selector')
<x-layout_main>
<div class="main-content">

    {{-- a general no small width view notification --}}
    @include('components.no_mobile_view', [
        'breakpoint' => 'md'
    ])

    {{-- top div --}}
    <div class="detail-div !bg-primary-100 hidden md:block ">

        <h1 class="pt-20">Keyword selector</h1>

        <div class="max-w-(--breakpoint-lg) p-4">
            <p>Here you can explore and select which Multi-Scale Labs keywords apply to your research. When you’re done, you 
            can export the keywords you selected, so you know which words to assign when you’re making your next data 
            publication. By adding terms like these (the more the better!), you make it much easier for us to find your data 
            and to make it discoverable in this catalogue and the EPOS data portal.</p>
    
            <div class=" p-6 bg-primary-200">
                <form method="post" action="{{ @route('keyword-export') }}" 
                class="w-full flex flex-col place-items-center ">
                @csrf
                
                <div class="w-full ">
                    <div class="search-bar-container form-field-text">
                        <div class="search-bar-container-icon">
                            <x-ri-search-line class="search-icon"/>
                        </div>
                        <input
                        class="search-bar"
                        type="text"
                        id="search-input"
                        name="query"
                        placeholder="Search keywords..." />
                        <div class="grid place-items-center h-full ">
                            <button class="btn btn-primary mx-2 " type="button" id="button-add-custom-keyword">Add</button>
                        </div>
                    </div>
                </div>
    
                <div class="flex p-6 w-full bg-primary-100">
                    <div class="w-1/2 flex">
                        <div id="sampleKeywords-tree"></div>
                    </div>
                    <div class="w-1/2 flex">
                        <ul class="list-none" id="sampleKeywords-modal-list-group"></ul>
                    </div>
                </div>
                
                <div class="pt-6">
                    <button class="btn btn-xl btn-primary ">Export to csv</button>

                </div>
                </form>            
            </div>
    
            <p class="pt-6">Are you interested in embedding the above keyword selector in the data repository you’re affiliated to? 
                The keyword selector is published openly on <a href="https://github.com/UtrechtUniversity/msl_vocabularies" target="_blank" title="MSL vocabularies on GitHub">Github</a>! If you’re interested in using this open source tool, do let us know – we’re very 
                interested to hear who intends to use it. Similarly, if you need support to embed it at your repository <a href="{{ route('contact-us') }}" title="Contact us" target="_blank">get in touch</a>.</p>
    
        </div>    

    </div>


   

</div>
@push('vite')
    @vite(['resources/js/jstree.js', 'resources/js/keyword-form.js'])
@endpush
</x-layout_main>