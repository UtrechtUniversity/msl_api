@section('title', 'demoPage')
<x-layout_main>
    <div class="mainContentDiv flex-col">

        this is the mainContentDiv. The main content of most of the pages are wrapped in this class. All content below are in this div

        
        <div class="py-20">
            <p>this is text, which is always justified and with padding, unless indicated differently</p>
        </div>


        <div class="py-20 w-full flex justify-center items-center flex-col">
            <p>all buttons come with primary style and secondary on hover, unless indicated differently</p>
            <button class="btn btn-lg btn-wide ">Data Access</button>
        </div>


    </div>

</x-layout_main>
