@section('title', 'Contribute as a researcher')
<x-layout_main>
<div class="mainContentDiv flex-col">
        <p class="text-4xl p-20">
            How to contribute as a researcher
        </p>

        <div class="flex justify-center items-center flex-col px-4">
            <p>
                Would you like to share Earth scientific laboratory data, or models, from one of the Multi-Scale Laboratories disciplines?
            </p>
            <p>
                And are you looking to make these discoverable in this catalogue, and in the EPOS data portal?
            </p>
            <p>
                Find out how to do this here.
            </p>
        </div>

        <ul class="timeline timeline-vertical py-20 max-w-2xl">
          <li>
            <a href="{{ route('contribute-researcher') }}#step-1"
            class="timeline-end timeline-box no-underline hover:bg-secondary-200">
              <div id='nextStep' >At which repository will you publish your data?</div>
            </a>
            
            <div class="timeline-middle ">
              <svg 
              viewBox="0 0 24 24"
              class="h-8 w-8 p-1"
              fill="currentColor">
                  <path 
                      d="M12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 14C10.8954 14 10 13.1046 10 12C10 10.8954 10.8954 10 12 10C13.1046 10 14 10.8954 14 12C14 13.1046 13.1046 14 12 14Z">
                  </path>
              </svg>
            </div>
            <hr class="timeline-line-element"/>
          </li>
          <li>
            <hr class="timeline-line-element"/>
            <div class="timeline-middle">
              <svg 
              viewBox="0 0 24 24"
              class="h-8 w-8 p-1"
              fill="currentColor">
                  <path 
                      d="M12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 14C10.8954 14 10 13.1046 10 12C10 10.8954 10.8954 10 12 10C13.1046 10 14 10.8954 14 12C14 13.1046 13.1046 14 12 14Z">
                  </path>
              </svg>
            </div>
            {{-- (add info-upon-hover: “We do this for two reasons: 1) To check whether your application to MSL affects any national projects, strategy or policy, and 2) For community building! We’re in this together.”), and relayed to the MSL Consortium Board (add info-upon-hover: “The MSL consortium board includes the twelve institutes that founded MSL”). --}}
            
            <a href="{{ route('contribute-researcher') }}#step-2"
              class="timeline-start timeline-box no-underline hover:bg-secondary-200">
              <div id='nextStep' >Check data formatting requirements</div>
              

            </a>

            <hr class="timeline-line-element"/>
          </li>

          <li>
            <hr class="timeline-line-element"/>
            <a href="{{ route('contribute-researcher') }}#step-3"
            class="timeline-end timeline-box no-underline hover:bg-secondary-200">
              <div id='nextStep' >Enrich your work with MSL vocabularies</div>

            </a>

            <div class="timeline-middle">
              <svg 
              viewBox="0 0 24 24"
              class="h-8 w-8 p-1"
              fill="currentColor">
                  <path 
                      d="M12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 14C10.8954 14 10 13.1046 10 12C10 10.8954 10.8954 10 12 10C13.1046 10 14 10.8954 14 12C14 13.1046 13.1046 14 12 14Z">
                  </path>
              </svg>
            </div>
            <hr class="timeline-line-element"/>
          </li>

          <li>
            <hr class="timeline-line-element"/>
            <a href="{{ route('contribute-researcher') }}#step-4"
            class="timeline-start timeline-box no-underline hover:bg-secondary-200">
              <div id='nextStep'>Publish your work</div>

            </a>

            <div class="timeline-middle">
              <svg 
              viewBox="0 0 24 24" 
              class="h-10 w-10 p-2"

              fill="currentColor">
                  <path 
                      d="M2 3H21.1384C21.4146 3 21.6385 3.22386 21.6385 3.5C21.6385 3.58701 21.6157 3.67252 21.5725 3.74807L18 10L21.5725 16.2519C21.7095 16.4917 21.6262 16.7971 21.3865 16.9341C21.3109 16.9773 21.2254 17 21.1384 17H4V22H2V3Z">
                  </path>
              </svg>
            </div>
          </li>


        </ul>

        

        <div class="max-w-screen-md px-4">
            <h2 id='step-1' class="pt-10 pb-4">Step 1: At which repository will you publish your data? 
            </h2>
            <p>Key is that you publish your data at a repository that can generate <a href="https://www.doi.org/">DOI</a>.  
            </p>
            <p>
                EPOS MSL currently provides access to data at these data repositories. The fastest route to make your data discoverable by MSL, 
                is to publish your data at one of these. Would you like to publish elsewhere? Let us know! 
                We can then start working towards including data from your repository too. 
            </p>
    
            <h2 class="pt-10 pb-4" id="step-2">Step 2: Check data formatting requirements
            </h2>
            <p>Some repositories have specific requirements or data formats/templates to take into account. 
                Check whether this is relevant for your work → better to know this before you start publishing.</p>
            <p>
                There may also be best practices available for specific research disciplines within MSL → see the list below. 
                These can help you optimize the re-usability of your data by others, and therefore increase the impact of your data publication.
            </p>
            <p class="pb-8">
                Do you know about a best practice relevant for MSL data and models, not listed here? Let us know!
            </p>
    


            <div role="tablist" class="tabs tabs-lifted tabs-lg p-4 rounded-xl bg-primary-200">
                <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="Microscopy"/>
                <div role="tabpanel" class="tab-content tabs-div bg-primary-100 border-primary-300 rounded-box">
                    Structure 2D micrographs using StraboMicro, 
                    and include the exported .smz file in your data publication <a href="Strabospot.org/whatisStrabomicro">Strabospot.org/whatisStrabomicro</a>
                </div>
              
                <input
                  type="radio"
                  name="my_tabs_2"
                  role="tab"
                  class="tab"
                  aria-label="Paleomagnetism"
                  checked="checked" />
                <div role="tabpanel" class="tab-content tabs-div bg-primary-100 border-primary-300 rounded-box">
                    For paleomagnetic data, publish your data at MagIC, and use their data standards <a href="www2.earthref.org/MagIC">www2.earthref.org/MagIC</a>
                </div>
              
                <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="Geochemistry" />
                <div role="tabpanel" class="tab-content tabs-div bg-primary-100 border-primary-300 rounded-box">
                    Geochemistry: For Geochemistry data, publish your data at EarthChem, and follow their data standards: <a href="https://www.earthchem.org/ecl/submission-guidelines/">https://www.earthchem.org/ecl/submission-guidelines/</a>
                </div>
              </div>
    
    
            <h2 class="pt-10 pb-4" id="step-3">Step 3: Enrich your work with MSL vocabularies    
            </h2>

            <p>Wherever you publish, you make it a lot easier for us to find your data by adding keywords from the Multi-Scale Labs vocabularies (the more the better!). 
                Here you can explore and select which Multi-Scale Labs keywords apply to your research. 
                When you’re done, you can export the keywords you selected, so you know which words to assign when you’re making your next data publication.
            </p>

            <div class="flex w-full place-content-center p-4">
                <a href="{{ route('keyword-selector') }}">
                    <button class="btn btn-primary btn-lg btn-wide ">Keyword Selector</button>
                </a>
            </div>

            <p>Would you like to embed MSL vocabularies, or the above keyword selector in the data repository you’re affiliated to? You can - it’s all open access and open source. 
                Have a look here on how to approach this (link to: how to contribute as a data repository → and include a few instructions on how to embed the keyword selector there). </p>
            
            <h2 class="pt-10 pb-4" id="step-4">Step 4: Publish your work
            </h2>
            <p>When you publish your data, make sure you do so with an open access license (e.g. <a href="https://creativecommons.org/licenses/by/4.0/deed.en">CC BY 4.0</a> ). 
                Most repositories provide clear guidance on which licenses are useful to provide your data openly, and promote citing. 
            </p>
            <p>Congratulations! Your data is now open access. Your job is done… </p>
            <p>…and our job starts. We strive to find your data, and make it findable for others in EPOS MSL, and the EPOS data portal. We do so by looking for data in these data repositories (see Step 1), 
                that have MSL vocabulary terms (see Step 3) used in the keywords, tags, data descriptions or titles, referring to: </p>
        
            <ul class="list-disc  pt-6 pb-6 pl-10 pr-10">
                <li>The Earth sciences (i.e. from MSL vocabularies: material, geological setting, or (sub)surface utilization) and </li>
                <li> Laboratories (i.e. terms from a.o. “apparatus”, “measured property” or “analyzed feature” sections of MSL 
                    subdomain vocabularies: analogue modelling, geochemistry, paleomagnetism, microscopy and tomography and rock and melt physics), or</li>
                </li>
                <li>A facility name from the Geo-Energy Test Beds. In this case, we know it concerns a GETB data publication.</li>
            </ul>

            <p>Note that we renew this search once or twice per year, meaning that the data you just published may not be harvested by MSL for several months. In doubt? Or do you feel we missed your data publication? Let us know. </p>

        </div>
        
</div>


</x-layout_main>