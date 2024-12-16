@section('title', 'Contribute as a repository')
<x-layout_main>

<div class="mainContentDiv flex-col">
        <p class="text-4xl p-20">
            How to contribute as a repository
        </p>

        <div class="max-w-screen-md flex justify-center items-center flex-col px-4">
            <p class="text-center">
                Do you work at a data repository?
            </p>
            <p class="text-center">
                Does your repository host data or models pertaining to one of the EPOS Multi-Scale Laboratories research domains?
            </p>
            <p class="text-center">
                Would you like to make sure that these can be found on this catalogue, and on the EPOS data portal, alongside other European solid Earth scientific data?
            </p>            
            <p class="text-center">
                Find out how to do this here.
            </p>
        </div>        

        <div class="max-w-screen-md px-4">
            <h2 class="pt-10 pb-4">Identifying data publications relevant to EPOS MSL</h2>
            <p>First, we need to be able to identify which data publications you want to make discoverable within this catalogue. We currently support the following options:</p>
            <ul class="list-disc pt-6 pb-6 pl-10 pr-10">
                <li>DataCite query</li>
                <li>OAI-PMH service(s)</li>
                <li>Full repository</li>
                <li>Custom API integration</i>
            </ul>
            <p>It might not be possible for us to exclusively filter your repository on MSL-relevant data publications. In that case we can filter the retrieved DOIs for MSL-relevancy by checking the metadata, as follows:</p>
            <ul class="list-disc pt-6 pb-6 pl-10 pr-10">
                <li>either the metadata of a data publication contains A) one term reflecting Earth scientific research (e.g. Earth material, or geological setting) and B) a term reflecting laboratory research (e.g. lab apparatus; measured/modeled properties or behavior).</li>
                <li>or the metadata of a data publication contains the name of a geo-energy test bed facility that is part of the <a href="{{ route('labs-map') }}">EPOS MSL community</a>.</li>
            </ul>
            <p>With that in mind, you make it a lot easier for us to find MSL-relevant data, if researchers publishing at your repository know what terms to add, to optimize data findability. See the below metadata recommendations on how to possibly go about that.</p>

            <h2 class="pt-5 pb-4">How do we import metadata?</h2>
            <ol class="list-decimal pt-6 pb-6 pl-10 pr-10">
                <li>Retrieve list of relevant DOIs</li>
                <li>(DataCite) metadata retrieval</li>
                <li>Mapping of metadata to internal schema</li>
                <li>You data is now findable within the MSL and EPOS data catalogues!</li>
            </ol>

            <h2 class="pt-5 pb-4">How you can help: metadata recommendations</h2>
            <ul class="list-disc pt-6 pb-6 pl-10 pr-10">
                <li>Assign <abbr title="Persistent Identifier">PIDs</abbr> to digital objects where possible</li>
                <li>
                    Use keywords from the MSL vocabularies and include <abbr title="Uniform Resource Identifier">URIs</abbr> to assigned terms.<br>
                    For specific implementation in the Datacite schema please see <a title="DataCite subjects metadata" target="_blank" href="https://datacite-metadata-schema.readthedocs.io/en/4.5/properties/subject/">here</a>.
                </li>
            </ul>

            <details class="collapse collapse-arrow  rounded-lg shadow-md mb-4">
                <summary class="collapse-title">File information</summary>
                <div class="collapse-content">
                Using the general metadata description we can improve the findability of your data publications however this 
                does not include the actual related files! Researchers will need to know what actual data is available to 
                understand their value. At a minimum we try to provide a list of files and download links. By providing machine 
                readable metadata about the actual files we can offer more sophisticated insights in the value of and contents of the data files. 
                </div>
            </details>

            <details class="collapse collapse-arrow rounded-lg shadow-md mb-4">
                <summary class="collapse-title">Keywords and vocabularies</summary>
                <div class="collapse-content">
                Assigning keywords to your data publications greatly improves their findability. To maximize its impact it is important to understand 
                the origin of the keyword. This can be done by assigning terms from controlled vocabularies. To store this context we recommend to assign 
                the URI of the term and vocabulary to keywords. For more information about the vocabularies developed by the MSL community click here. 
                Information about storing this information within the DataCite schema click <a title="DataCite subjects metadata" target="_blank" href="https://datacite-metadata-schema.readthedocs.io/en/4.5/properties/subject/">here</a>.
                </div>
            </details>

            <details class="collapse collapse-arrow rounded-lg shadow-md mb-4">
                <summary class="collapse-title">Versioning</summary>
                <div class="collapse-content">
                    Data publications might be published as several versions over the course of time. It is 
                    important to understand from the metadata if we are looking at the latest version as we do not 
                    want to show users several versions of the same data publication. For more information about the options 
                    available in the DataCite schema please see <a href="https://datacite-metadata-schema.readthedocs.io/en/4.5/properties/version/" target="_blank" title="DataCite versioning">here</a>.
                </div>
            </details>

            <details class="collapse collapse-arrow rounded-lg shadow-md mb-4">
                <summary class="collapse-title">Location data</summary>
                <div class="collapse-content">
                    By providing information about the geolocations associated with your data publication we can greatly improve the findability. 
                    Data can be displayed on maps and be found using spatial queries. How to describe this within the DataCite schema can be found <a href="https://datacite-metadata-schema.readthedocs.io/en/4.5/properties/geolocation/" target="_blank" title="DataCite versioning">here</a>.
                </div>
            </details>

            <h2 class="pt-5 pb-4">Custom implementations</h2>
            <p>
                Every data repository might have unique technologies in place to share data. Feel free to 
                <a href="{{ route('contact-us') }}" title="contact us">contact us</a> to discuss what we can do to setup the best way of harvesting all related metadata.
            </p>
        </div>        
</div>
</x-layout_main>