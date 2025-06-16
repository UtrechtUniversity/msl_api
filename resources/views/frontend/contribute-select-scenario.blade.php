@section('title', 'Scenario Survey')
<x-layout_main>
<div class="mainContentDiv flex-col">
    <h1 class="pt-20">Data Tooling - Survey</h1>

    <div class="max-w-screen-md pt-10 sm:pt-20">
        <h3 class="pb-2 pt-6">What is this survey about?</h3>
        <p class="inline">
            We ask questions, you answer and everyone is happy!!
        </p>
    </div>

    @php
    $domainList = [
      array(
        "name"=>"Analogue Modelling of Geological Processes", 
        "routeParam" => 'analogue'
      ),
      array(
        "name"=>"Geochemistry", 
        "routeParam" => 'geochemistry'
      ),
      array(
        "name"=>"Geo-energy Test Beds", 
        "routeParam" => 'testbeds'
      ),
      array(
        "name"=>"Rock and Melt Physics",  
        "routeParam" => 'rockmelt'
      ),
      array(
        "name"=>"Magnetism and Paleomagnetism", 
        "routeParam" => 'paleomag'
      ),
      array(
        "name"=>"Microscopy and Tomography", 
        "routeParam" => 'mircotomo'
      ),
    ]
  @endphp

        <h3 class="pb-2 pt-16">Select your domain!</h3>
        <div class="sm:max-w-screen-lg flex md:flex-row flex-wrap sm:p-10 py-10 gap-8 justify-center place-items-center">
            @foreach ($domainList as $infoElement)
                    <a role="button" 
                    href="{{ route('contribute-survey-scenario', ['domain' => $infoElement["routeParam"]]) }}"
                    class="no-underline m-1 p-4 bg-base-300 rounded-lg w-64 h-24 place-content-center shadow-lg hover:bg-secondary-100 hover:text-secondary-900"
                    > 
                        <h5 class="text-base font-normal">{{ $infoElement["name"] }}</h5>
                    </a>
            @endforeach
        </div>
        
</div>


</x-layout_main>