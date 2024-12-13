@section('title', 'Home')
<x-layout_main>
    <div
    class="hero h-dvh"
    style="background-image: url('images/heros/7.jpg');">
      <div class="hero-content text-neutral-content grid w-full">
        <div class="index-opacity-parent flex flex-col place-items-center gap-8">

          <h1 class="index-opacity-child p-2">Welcome </h1>

          <p class="index-opacity-child">
              This is the EPOS Multi-Scale Labs data catalogue, an access point for Earth scientific laboratory data in Europe. 
              Here you can find data from rock and melt physics, paleomagnetism, geochemistry, microscopy, tomography and analogue modelling of geological processes. 
          </p>
          

          <a href="{{ route('data-access') }}">
            <button class="btn btn-primary btn-lg btn-wide ">Data Access</button>
          </a>

          <div class="index-opacity-child w-1/2 
          bg-primary-100 text-primary-900 rounded-lg 
           place-items-center pl-4 pr-4 
          flex flex-col
          text-left
          ">
            
            <p class="">
              {{ $datasetsCount }} datasets
            </p>
            <p class="">
              {{ $labCount }} labs
            </p>
            <p class="">
              {{ $reposCount }} data repositories
            </p>
          </div>

        </div>

        {{-- <div class="index-opacity-parent h-max">

        </div> --}}

      </div>
       
    </div>
</x-layout_main>

