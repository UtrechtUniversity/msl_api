@section('title', 'Data repositories')

<x-layout_main>
    <div class="flex flex-col justify-center items-center p-10 ">
        <h1 class="p-20">
            Data repositories</h1>

            <p class="max-w-screen-md pb-10">
              EPOS MSL currently provides access to MSL relevant data at the data repositories shown on this page. 
              The fastest route to make your data discoverable by MSL, is to publish your data at one of these. Note that some of these are only accessible for 
              researchers affiliated to the hosting institutes. Would you like to publish elsewhere? <a href="{{ route('contact-us') }}" title="Contact us">Let us know</a>! 
              We can then start working towards including data from your repository too.
            </p>              
          
        <div class="flex flex-wrap justify-center gap-4 max-w-screen-lg">

          @foreach ($repositories as $repo)
            @if ($repo["hide"] == "false")
              <div class="card bg-base-300 size-80 shadow-xl flex justify-between flex-col p-2">
                <figure>
                  <img
                  class="h-48 object-contain"
                  src= {{ asset( 'images/'.str_replace(' ', '', $repo["image_url"]) )}}
                    alt={{ $repo["organization_display_name"] }} />
                </figure>
                <div class="card-body p-2">
                  <h5 class="text-center">
                    {{ $repo["organization_display_name"] }}
                    {{-- <div class="badge badge-secondary">NEW</div> --}}
                  </h5>
                </div>
                <a href="/data-access?organization[]={{ $repo["name"] }}">
                  <button class="btn btn-primary w-full">View Datasets</button>
                </a>
              </div>
            @endif            
          @endforeach
        </div>        
    </div>    
</x-layout_main>