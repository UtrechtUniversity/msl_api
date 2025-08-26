@section('title', 'Contribute as a researcher')
<x-layout_main>
<div class="mainContentDiv flex-col">
        <p class="text-4xl p-20">
          How to contribute with your proposal or project?
        </p>

        <div class="flex justify-center items-center flex-col py-10 max-w-2xl px-4">
          <h3 class=" ">There is so much to be gained!</h3>
            <ul class="list-disc">
                <li class="py-4">
                  For you: By involving EPOS Multi-Scale Laboratories in your proposal or project, you can ensure a sustainable improvement on data sharing services for solid Earth scientific laboratories, as we can take care of hosting your service beyond the time-span of your project.
                </li>

                <li class="py-4">
                  Including an ERIC (European Research Infrastructure Consortium, like <a href="https://www.epos-eu.org/epos-eric">EPOS-ERIC</a>) can be a requirement for European (Horizon) calls. We can help you make this connection.
                </li>

                <li class="py-4">
                  For you and us: When you allocate resources in your project to collaborate with us, you help us develop further, in turn improving the services we offer to others in your field and to the wider solid Earth sciences community.
                </li>
            </ul>
        </div>

        <h3>Our process</h3>
        <ul class="timeline timeline-vertical pb-10 max-w-2xl">
          <li>
            <a href="{{ route('contribute-project') }}#step-1"
            class="timeline-end timeline-box no-underline hover:bg-secondary-200">
              <div id='nextStep' >User research and design</div>
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
            <a href="{{ route('contribute-project') }}#step-2"
              class="timeline-start timeline-box no-underline hover:bg-secondary-200">
              <div id='nextStep' >Establishing or updating best practices</div>
              

            </a>

            <hr class="timeline-line-element"/>
          </li>

          <li>
            <hr class="timeline-line-element"/>
            <a href="{{ route('contribute-project') }}#step-3"
            class="timeline-end timeline-box no-underline hover:bg-secondary-200">
              <div id='nextStep'>Data service development and integration in EPOS</div>
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

        

        <div class="w-screen sm:max-w-screen-md px-4">
            <h2 id='step-1' class="pt-10 pb-4">Step 1: User research and design 
            </h2>
            <p>
              This step is often overlooked, but is key for optimizing the impact of what you ultimately aim to develop. Here, our staff interacts with you and/or other researchers from your community, through workshops and surveys, to make sure that: 
            </p>
            <ul class="list-none">
              <li>
                A) The right problem is identified
              </li>
              <li>
                B) Researchers can relate to it
              </li>
              <li>
                C) The way we plan to address it (e.g. through tools or workflows) really makes an impact.
              </li>
            </ul>
            <p>
              Estimated time investment: typically in the order of 10 hours for 4-5 involved people, with a wider group more sporadically involved (e.g. by answering a survey)
            </p>
            <p>
              Products at the of this step can include: Scenario’s, Mockup’s, Mapped-out workflows, Surveys
            </p>
    
            <h2 class="pt-10 pb-4" id="step-2">Step 2: Establishing or updating best practices
            </h2>
            <p>
              With the user research and the design step finished, you now have the right ingredients in your hand to confidently start developing, or improving a data sharing service. Such a service could be in the form of a tool, a workflow, a (standardized) data collection, vocabularies, or other. 
              Depending on what you develop, this can have impact on how you would like researchers, from your own community or beyond, to share their data. You can capture this by developing or updating the best practices for sharing data. 
              Current best practices for sharing data from MSL domains are listed <a href="{{ route('contribute-researcher') }}">here</a>. You can help develop these further!
            </p>
            <p>
              Estimated time investment: this can vary, but a best practice generally takes 1-5 days to draft or update, by 1-2 persons.
            </p>
            <p>
              Product at the end of this step: (updated) best practice for data sharing, (of which the key ingredients are) published on the MSL data catalogue
            </p>
    
    
            <h2 class="pt-10 pb-4" id="step-3">Step 3: Data service development and integration in EPOS    
            </h2>

            <p>
              While the above steps typically require limited input from researchers involved in your project (where needed supported by us), this last step often requires a dedicated software or data engineer on your end, 
              who closely interacts with MSL developers. A data service can be a tool for data exploration, analysis, or processing, a workflow, a (standardized) data collection, harmonized metadata standards, or other. 
              Whatever it is you wish to develop, we can help each other most if it can ultimately fit in the MSL data infrastructure, and/or services provided to the <a href="https://www.epos-eu.org/dataportal">EPOS data portal</a>. That way, we can make sure that we can durably host your new development, even after your project ends.
            </p>

            <p>
              Estimated time investment: obviously will depend on what needs to be developed, but typically in the range of 0.5 to 3 years, at 1 FTE.
            </p>

            <p>
              Products at the end of this step: a new data service, ideally integrated directly within the EPOS infrastructure, but at minimum referred to in EPOS.
            </p>
        </div>
        
</div>


</x-layout_main>