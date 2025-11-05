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
            <button class="btn btn-xl btn-wide shadow-xl">Data Access</button>
          </a>

          <div class="w-full
           text-primary-900 
           place-items-center pt-4
          flex flex-row
          text-left
          gap-4
          ">
              
            <a class="flex flex-col justify-between w-1/3 hover-interactive shadow-xl rounded-xl hover:rounded-xl p-2 bg-primary-100 "
            href="{{ route('labs-map') }}">
              <h2 class="font-bold ">{{ $datasetsCount }}</h2>
              <h5 >Datasets</h5>
            </a>
            <a class="flex flex-col justify-between w-1/3 hover-interactive shadow-xl rounded-xl hover:rounded-xl p-2 bg-primary-100 "
            href="{{ route('labs-map') }}">
              <h2 class="font-bold ">{{ $labCount }}</h2>
              <h5 >Labs</h5>
            </a>
            <a class="flex flex-col justify-between w-1/3 hover-interactive shadow-xl rounded-xl hover:rounded-xl p-2 bg-primary-100 "
              href="{{ route('data-repositories') }}"
            >
              <h2 class="font-bold ">{{ $reposCount }}</h2>
              <h5 class="" >Repos</h5>
            </a>
          </div>

        </div>

      </div>
       
    </div>
</x-layout_main>

