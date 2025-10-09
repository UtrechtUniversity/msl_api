@section('title', 'Home')
<x-layout_main>
    <div
    class="hero h-dvh"
    style="background-image: url('images/heros/7.jpg');">

      <div class="text-neutral-content relative w-96 rounded rounded-xl">
        <div class="w-full h-full bg-primary-100 opacity-75 absolute inset-0 rounded rounded-xl">

        </div>
        <div class="w-96 backdrop-blur-sm flex flex-col place-items-center gap-8 p-6 text-primary-900 rounded rounded-xl">

          <h1 class="p-2">Welcome </h1>

          <p class="">
            This is the EPOS Multi-Scale Labs data catalogue, an access point for Earth scientific laboratory data in Europe. 
            Here you can find data, labs and lab equipment from rock and melt physics, paleomagnetism, geochemistry, microscopy, 
            tomography, geo-energy test beds and analogue modelling of geological processes.
          </p>
          

          <a href="{{ route('data-access') }}" class="w-full flex justify-center">
            <button class="btn btn-lg btn-wide ">Data Access</button>
          </a>

          <div class="w-1/2 
          bg-primary-100 text-primary-900 rounded-lg 
           place-items-center p-4
          flex flex-col
          text-left
          ">
            <div class="flex justify-between w-full">
              <h2 class="font-bold text-primary-800">{{ $datasetsCount }}</h2>
              <h5 >Datasets</h5>
            </div>
            <p class="text-center">
              {{ $datasetsCount }} datasets
            </p>
            <p class="text-center">
              {{ $labCount }} labs
            </p>
            <p class="text-center">
              {{ $reposCount }} data repositories
            </p>
          </div>

        </div>

        {{-- <div class="index-opacity-parent h-max">

        </div> --}}

      </div>
       
    </div>
</x-layout_main>

