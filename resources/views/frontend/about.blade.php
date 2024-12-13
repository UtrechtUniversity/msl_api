@section('title', 'About')
<x-layout_main >
    <div class="mainContentDiv flex-col">
        <h1 class="pt-20">About MSL</h1>

        <img 
            src= {{ asset('images/heros/about.png') }}
            alt="aboutImage"
            class="object-contain max-w-lg p-10">

            <div class="max-w-screen-lg flex flex-col md:flex-row p-10 gap-6">
                <div class="w-full card bg-base-300 shadow-xl p-4 flex flex-col justify-start ">
                    <h4>Our Vision</h4>
                    <p class="pr-10 pl-10">We strive to create the best earth science laboratory data service to 
                        support Geo-scientists in their scientific endeavor and 
                        to facilitate knowledge exchange between them. </p>
                </div>
                <div class="w-full card bg-base-300 shadow-xl p-4 flex flex-col justify-start ">
                    <h4>Our Mission</h4>
                    <p class="pr-10 pl-10">Our mission is to support your research into Earth system behavior, 
                        by providing you with data, models and expertise on 
                        rock properties and processes, 
                        building on standards and tools developed with the community. </p>
                </div>
            
            </div>

        <div class="max-w-screen-md pt-20">
            <h3 class="pb-2">Why EPOS MSL?</h3>
            <p class="inline">
                In a world that demands increasing collaboration within the scientific community, solid Earth science laboratories are challenged with finding each other 
                to exchange best practices and re-use data. Data produced by the EPOS Multi-Scale Laboratories are crucial to serving society’s needs for geo-resources exploration 
                and for protection against geo-hazards. Indeed, <p class="font-bold inline">to model Earth system behavior during human or tectonic forcing</p>, we need to understand the governing processes and 
                properties from the molecule scale to the scale of the operating system. Therefore, coordination and communication inside the European solid Earth science laboratories, 
                complemented with services to increase curation and access for re-use of laboratory data is needed to effectively contribute to solve the grand challenges facing society.
            </p>
            <h3 class="pb-2">What is EPOS MSL?</h3>
            <p class="inline">
                EPOS Multi-Scale Laboratories includes a wide range of world-class laboratories, aimed at studying the <p class="font-bold inline">properties and processes controlling rock system behaviour</p>. 
                The length scales addressed by these infrastructures cover the nano- and micrometre levels (electron microscopy and micro-beam analysis) to the scale of experiments 
                on centimetre and decimetre sized samples, 
                to analogue model experiments simulating the reservoir scale, the basin scale and the plate scale.
            </p>
            <h3 class="pb-2">What does EPOS MSL do?</h3>
            <p>
                EPOS MSL collects and harmonizes available and emerging laboratory (meta)data, aiming to generate data products that are easily Findable, Accessible, 
                Interoperable and Re-usable (FAIR) for future research, notably into Geo-resources, 
                Geo-storage, Geo-hazards and Earth System Evolution. Data emerging from MSL labs can be categorized as below, following the six major sub-domains below. 
            </p>            
        </div>

        @php
        $infoList = [
          array(
            "name"=>"Analogue modelling of geological processes", 
            "listItems"=>array(
                "From reservoir (km) to tectonic plate (global) scale",
                "To study and visualize the fundamental processes underlying crustal and mantle deformation processes, including fault formation, mountain building and sedimentary basin evolution"
            )
          ),
          array(
            "name"=>"Geochemistry", 
            "listItems"=>array(
                "Volcanic ash from explosive eruptions",
                "Magmas in the context of eruption and lava-flow hazard evaluation",
                "Rock systems of key importance in mineral exploration and mining operations",
                "Soil and rock chemistry or mineralogy"
            )
          ),
          array(
            "name"=>"Geo-energy test beds", 
            "listItems"=>array(
                "The latest addition to Multi-Scale Labs",
                "Testing or monitoring field-scale subsurface applications aimed at curbing CO2 emissions. Such applications include subsurface storage of fluids (H, N, CO2, hot water), or geothermal energy production.",
                "Data are often (but not always) site-specific, and can include a.o. fiber optics sensing data (DAS, DSS, DTS), seismological data, electromagnetics, geological models, microscopy and tomography and rock physics data."
            )
          ),
          array(
            "name"=>"Rock and melt physics", 
            "listItems"=>array(
                "Rock and fault properties of importance for modelling and forecasting natural and induced subsidence, seismicity and associated hazards",
                "Crustal and upper mantle rheology as needed for modelling sedimentary basin formation and crustal stress distributions",
                "The composition, porosity and permeability of reservoir rocks of interest in relation to unconventional resources, fluid storage and geothermal energy"
            )
          ),
          array(
            "name"=>"Magnetism and paleomagnetism", 
            "listItems"=>array(
                "Understanding the evolution of sedimentary basins and associated resources",
                "Charting geo-hazard frequency",
            )
          ),
          array(
            "name"=>"Microscopy and tomography", 
            "listItems"=>array(
                "Image data of Earth materials obtained with a wide variety in techniques",
                "Aimed at analyzing the process, often occurring at nano- to micrometer scale, governing the physical, chemical and transport behavior of Earth materials"
            )
          ),
        ]
      @endphp
            
        
        <h3 class="pt-20">The MSL subdomains</h3>

        <div class="max-w-screen-lg flex md:flex-row flex-wrap p-10 gap-4 justify-between">
            @foreach ($infoList as $infoElement)
                <div class="dropdown dropdown-hover p-4">
                    <div tabindex="0" role="button" class="m-1 p-4 bg-base-300 rounded-lg w-64 h-24 place-content-center"> 
                        <h5 class="text-base font-normal">{{ $infoElement["name"] }}</h5>
                    </div>

                    <ul tabindex="0" class="dropdown-content menu bg-base-300 rounded-box z-[1] p-2 shadow-xl w-96 list-disc">
                        @foreach ( $infoElement["listItems"] as $listItem)
                            <li class="p-1">{{ $listItem }}</li> 
                        @endforeach
                    </ul>
                </div>
            @endforeach
        
        </div>

        <div class="max-w-screen-lg flex flex-col md:flex-row p-10 gap-6 justify-center">
            <div class="w-full card bg-base-300 shadow-xl p-4 flex flex-col justify-center md:w-2/3">
                <h4>EPOS central data portal</h4>
                <p class="pr-10 pl-10">MSL data can be found in the MSL data catalogue on this dedicated website, and in the <a href="https://www.epos-eu.org/dataportal">EPOS data portal</a>, 
                    where these can be explored alongside other solid Earth scientific data, 
                    from seismology, geology, volcanology, satellite observations and other disciplines. </p>
            </div>            
        </div>

    </div>


  
  </x-layout_main>