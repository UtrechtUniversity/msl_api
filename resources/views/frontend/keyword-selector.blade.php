@section('title', 'Keyword selector')
<x-layout_main>
<div class="mainContentDiv flex-col">
    <h1 class="pt-20">Keyword selector</h1>

    <div class="max-w-screen-lg px-4">
        <p>Here you can explore and select which Multi-Scale Labs keywords apply to your research. When you’re done, you 
        can export the keywords you selected, so you know which words to assign when you’re making your next data 
        publication. By adding terms like these (the more the better!), you make it much easier for us to find your data 
        and to make it discoverable in this catalogue and the EPOS data portal.</p>

        <div>
            <form method="post" action="{{ @route('keyword-export') }}" class="w-full">
            @csrf
            <div class="relative flex items-center w-full h-12 rounded-lg shadow-lg overflow-hidden">                
                <input class="peer search-bar pl-2"  type="text" id="search-input" placeholder="Search keywords" name="query" />
                <div class="grid place-items-center h-full ">
                    <button class="btn btn-primary" type="button" id="button-add-custom-keyword">Add</button>
                </div>
            </div>

            <div class="flex pb-10 pt-10">
                <div class="w-1/2 flex">
                    <div id="sampleKeywords-tree"></div>
                </div>
                <div class="w-1/2 flex">
                    <ul class="list-none" id="sampleKeywords-modal-list-group"></ul>
                </div>
            </div>
            
            <button class="btn btn-primary">Export</button>
            </form>            
        </div>

        <p>Are you interested in embedding the above keyword selector in the data repository you’re affiliated to? The keyword selector is published openly on Github! If you’re interested in using this open source tool, do let us know – we’re very interested to hear who intends to use it. Similarly, if you need support to embed it at your repository get in touch.</p>

    </div>    

</div>
@push('vite')
    @vite(['resources/js/jstree.js', 'resources/js/keyword-form.js'])
@endpush
</x-layout_main>