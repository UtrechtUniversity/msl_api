@section('title', 'Contribute as a researcher')
<x-layout_main>
<div class="mainContentDiv flex-col">
        <p class="text-4xl p-20">
            How to contribute as a laboratory
        </p>

        <div class="flex justify-center items-center flex-col px-4">
            <p class="max-w-screen-md px-4">
              Thank you for your interest in joining the EPOS Multi-Scale Laboratories (MSL) community. Below you will find a brief description of EPOS and MSL, and the application procedure to join our community.
            </p>

        </div>

        <ul class="timeline timeline-vertical py-20 max-w-2xl">
          <li>
            <div id='nextStep' class="timeline-end timeline-box">Fill out the online form about your laboratory to start the process</div>
            <div class="timeline-middle">
              <svg
                viewBox="0 0 20 20"
                fill="currentColor"
                class="h-5 w-5">
                <path
                  fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                  clip-rule="evenodd" />
              </svg>
            </div>
            <hr />
          </li>
          <li>
            <hr />
            <div class="timeline-middle">
              <svg
                viewBox="0 0 20 20"
                fill="currentColor"
                class="h-5 w-5">
                <path
                  fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                  clip-rule="evenodd" />
              </svg>
            </div>
            {{-- (add info-upon-hover: “We do this for two reasons: 1) To check whether your application to MSL affects any national projects, strategy or policy, and 2) For community building! We’re in this together.”), and relayed to the MSL Consortium Board (add info-upon-hover: “The MSL consortium board includes the twelve institutes that founded MSL”). --}}
            <div class="timeline-start timeline-box">Your application will be checked with an MSL colleague situated in your country  </div>
            <hr />
          </li>

          <li>
            <hr />
            <div class="timeline-end timeline-box">Once approved by the MSL Consortium Board, you are officially part of the MSL community!</div>

            <div class="timeline-middle">
              <svg
                viewBox="0 0 20 20"
                fill="currentColor"
                class="h-5 w-5">
                <path
                  fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                  clip-rule="evenodd" />
              </svg>
            </div>
            <hr />
          </li>

          <li>
            <hr />
            <div class="timeline-start timeline-box">The MSL Coordinator will initiate the registration of your laboratory via the FAST registration system. </div>

            <div class="timeline-middle">
              <svg
                viewBox="0 0 20 20"
                fill="currentColor"
                class="h-5 w-5">
                <path
                  fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                  clip-rule="evenodd" />
              </svg>
            </div>
            <hr />
          </li>

          <li>
            <hr />
            <div class="timeline-end timeline-box">Interested in how you can make your data publications discoverable in EPOS? 
              <a href="{{ route('contribute-researcher') }}">
                Look here 
              </a>

            </div>
            <div class="timeline-middle">
              <svg
                viewBox="0 0 20 20"
                fill="currentColor"
                class="h-5 w-5">
                <path
                  fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                  clip-rule="evenodd" />
              </svg>
            </div>
          </li>
        </ul>

          <div class="max-w-screen-md px-4">
            <h2 class="pt-10 pb-4">Introduction to EPOS and MSL
            </h2>
            <p>The goal of EPOS is to establish a comprehensive multidisciplinary research platform for the Earth sciences in Europe. It aims to facilitate the integrated use of data, models, and laboratories, from both existing and new pan-European Research Infrastructures, allowing open access and transparent use of data. To reach this goal EPOS is thematically subdivided in 10 different communities: so called Thematic Core Services (TCS). One of these is Multi-Scale Laboratories (MSL).</p>
            <p>
              MSL research facilities, and the data that these generate, are currently grouped into 6 domains:
            </p>
            <div class="flex place-content-center">
              <ul class="list-decimal list-inside">
                <li>Analogue modelling of geological processes</li>
                <li>Geochemistry</li>
                <li>Geo-energy test beds</li>
                <li>Microscopy & tomography</li>
                <li>Paleomagnetic and magnetic data</li>
                <li>Rock and melt physical properties</li>
              </ul>
            </div>

    
            <h2 class="pt-10 pb-4">How does your laboratory benefit from EPOS MSL?
            </h2>
            <p>By becoming part of our network, your lab is offered a platform to showcase its research data output (example), facility equipment and information, and, the opportunity to present your lab as accessible to potential visitors.</p>
            
            
            <h2 class="pt-10 pb-4">What’s in it for the researchers?</h2>
            <p>
              Researchers are increasingly asked to publish their research data 
              <a href="https://www.doi.org/">(incl. a DOI)</a>, 
              for example when publishing a research article. While a publication makes data openly accessible by others in principle, in practice data are available fragmented across Europe and difficult to find. EPOS MSL provides tools and assistance in making published data of above MSL disciplines centrally discoverable in MSL and EPOS data portals. This makes the research data more Findable and Accessible. MSL also works actively with the community to collect and further develop best practices and standards, on how data is best shared. This makes data more Interoperable and easier to Reuse for future research. In turn, this increases the citation index and ensures that researchers are compliant with the (increasingly more common) requirement by funders and publication agencies to publish data in an open and FAIR manner.</p>

            <h2 class="pt-10 pb-4">What do we expect from you?</h2>
            <p>A healthy research community requires active engagement of its partners. As an EPOS MSL laboratory you:
              </p>
            <div class="flex place-content-center ">
              <ul class="list-disc list-outside w-4/5">
                <li class="p-1">Are engaged in at least one of the domains within MSL (see above)</li>
                <li class="p-1">Publish research data in a FAIR manner, at a reputable data repository. In doing so, you use MSL vocabulary terms in assigning metadata/keywords, and follow best practices, as far as these are developed for your field: <a href="{{ route('contribute-researcher') }}">look here</a></li>
                <li class="p-1">Pro-actively let us know which repository you use, when publishing data. Only then we can make your data findable in EPOS!</li>
                <li class="p-1">Share laboratory information (location, equipment, contact details) with us, for hosting on the EPOS portal. </li>
                <li class="p-1">Are strongly encouraged to take part in community initiatives. These can relate, for example, to improving FAIR data practices and tools, funding proposals or lab accessibility. Most of the community initiatives are online (video meetings), but occasionally take place in person (e.g. at EGU, EPOS days, or other).</li>
                <li class="p-1">Are a pro-active ambassador for EPOS MSL within your professional network. As said: we’re in this together! </li>
              </ul>
            </div>
    
            <div class="flex place-content-center p-20">
              <a href="{{ route('laboratory-intake') }}">
                <button class="btn btn-primary btn-lg btn-wide ">Laboratory intake form</button>
              </a>
            </div>

        </div>
        
</div>


</x-layout_main>