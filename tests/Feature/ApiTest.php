<?php

namespace Tests\Feature;

use App\Http\Controllers\ApiController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ApiTest extends TestCase
{

    /**
     * Test /all API endpoint based on mocked CKAN request
     * 
     * @return void
     */
    public function test_all_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function($app){
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/package_search_datapublications_all.txt'));
        
            $mock = new MockHandler([
                new Response(200, [], $response)
            ]);
        
            $handler = HandlerStack::create($mock);
                    
            return new ApiController(new Client(['handler' => $handler]));
        });
        
        // Retrieve response from API
        $response = $this->get('webservice/api/all');
        
        // Check for 200 status response
        $response->assertStatus(200);
        
        // Verify response body contents
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('success')
                ->where('success', true)
                ->where('result.count', 670)
                ->where('result.resultCount', 10)
                ->has('result.results.1', fn (AssertableJson $json) =>
                    $json
                        ->where('title', 'The 125–150 Ma high-resolution Apparent Polar Wander Path for Adria from magnetostratigraphic sections in Umbria–Marche (Northern Apennines, Italy): Timing and duration of the global Jurassic–Cretaceous hairpin turn (Dataset)')
                        ->where('name', 'f24c11eb13c8263373a6e01f02c86bd8')
                        ->where('portalLink', config('app.url') . '/data-publication/f24c11eb13c8263373a6e01f02c86bd8')
                        ->where('doi', '10.7288/V4/MAGIC/20030')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'http://dx.doi.org/10.7288/V4/MAGIC/20030')
                        ->where('publisher', '2d5b899a-d9f3-4c77-a4d0-d478a0f5ccd7')
                        
                        ->where('description', 'Paleomagnetic, rock magnetic, or geomagnetic data found in the MagIC data repository from a paper titled: Sara Satolli, Jean Besse, Fabio Speranza, Fernando Calamita (2007). The 125–150 Ma high-resolution Apparent Polar Wander Path for Adria from magnetostratigraphic sections in Umbria–Marche (Northern Apennines, Italy): Timing and duration of the global Jurassic–Cretaceous hairpin turn. Earth and Planetary Science Letters 257 (1-2):329-342. doi:10.1016/J.EPSL.2007.03.009.')
                        ->where('publicationDate', '2007-01-01')
                        ->where('citation', 'Satolli, S., Besse, J., Speranza, F., &amp; Calamita, F. (2007). <i>The 125–150 Ma high-resolution Apparent Polar Wander Path for Adria from magnetostratigraphic sections in Umbria–Marche (Northern Apennines, Italy): Timing and duration of the global Jurassic–Cretaceous hairpin turn (Dataset)</i> (Version 1) [Data set]. Earth and Planetary Science Letters. https://doi.org/10.7288/V4/MAGIC/20030')
                        
                        ->count('creators', 4)
                        ->where('creators.0.authorName', 'Sara Satolli')
                        ->has('creators.0.authorOrcid')
                        ->has('creators.0.authorScopus') //most cases (99%) do have orcid instead
                        ->has('creators.0.authorAffiliation')
                        
                        ->where('contributors.0.contributorName', 'Magnetics Information Consortium (MagIC)')
                        ->where('contributors.0.contributorRole', 'Distributor')
                        ->has('contributors.0.contributorOrcid')
                        ->has('contributors.0.contributorScopus')
                        ->has('contributors.0.contributorAffiliation')
                        
                        ->where('references.0.referenceDoi', '10.1016/J.EPSL.2007.03.009')
                        ->where('references.0.referenceType', 'IsDocumentedBy')
                        ->where('references.0.referenceTitle', "Satolli, S., Besse, J., Speranza, F., & Calamita, F. (2007). The 125–150 Ma high-resolution Apparent Polar Wander Path for Adria from magnetostratigraphic sections in Umbria–Marche (Northern Apennines, Italy): Timing and duration of the global Jurassic–Cretaceous hairpin turn. Earth and Planetary Science Letters, 257(1–2), 329–342. https://doi.org/10.1016/j.epsl.2007.03.009\n")
                        ->has('references.0.referenceHandle')

                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')

                        ->where('downloads.0.fileName', 'magic_contribution_20030')
                        ->where('downloads.0.downloadLink', 'https://earthref.org/MagIC/download/20030/magic_contribution_20030.txt')

                        ->has('researchAspects')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /rock_physics API endpoint based on mocked CKAN request
     * 
     * @return void
     */
    public function test_rockphysics_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function($app){
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/package_search_datapublications_rockphysics.txt'));
        
            $mock = new MockHandler([
                new Response(200, [], $response)
            ]);
        
            $handler = HandlerStack::create($mock);
                    
            return new ApiController(new Client(['handler' => $handler]));
        });
        
        // Retrieve response from API
        $response = $this->get('webservice/api/rock_physics');
        
        // Check for 200 status response
        $response->assertStatus(200);
        
        // Verify response body contents
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('success')
                ->where('success', true)
                ->where('result.count', 256)
                ->where('result.resultCount', 10)
                ->has('result.results.0', fn (AssertableJson $json) =>
                    $json   
                        ->where('title', 'Archaeomagnetic results from Cambodia in Southeast Asia: Evidence for possible low-latitude flux expulsion (Dataset)')
                        ->where('name', '7a04d77711bd95046f23e09111afbdbf')
                        ->where('portalLink', config('app.url') . '/data-publication/7a04d77711bd95046f23e09111afbdbf')
                        ->where('doi', '10.7288/V4/MAGIC/19551')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'http://dx.doi.org/10.7288/V4/MAGIC/19551')
                        ->where('publisher', '2d5b899a-d9f3-4c77-a4d0-d478a0f5ccd7')
                        ->where('subdomain.0', 'rock and melt physics')
                        
                        ->where('description', 'Paleomagnetic, rock magnetic, or geomagnetic data found in the MagIC data repository from a paper titled: Shuhui Cai, Rashida Doctor, Lisa Tauxe, Mitch Hendrickson, Quan Hua, Stéphanie Leroy, Kaseka Phon (2021). Archaeomagnetic results from Cambodia in Southeast Asia: Evidence for possible low-latitude flux expulsion. Proceedings of the National Academy of Sciences 118 (11). doi:10.1073/PNAS.2022490118.')
                        ->where('publicationDate', '2021-01-01')
                        ->where('citation', 'Cai, S., Doctor, R., Tauxe, L., Hendrickson, M., Hua, Q., Leroy, S., &amp; Kaseka Phon. (2021). <i>Archaeomagnetic results from Cambodia in Southeast Asia: Evidence for possible low-latitude flux expulsion (Dataset)</i> (Version 2) [Data set]. Proceedings of the National Academy of Sciences. https://doi.org/10.7288/V4/MAGIC/19551')
                        
                        ->count('creators', 7)
                        ->where('creators.0.authorName', 'Shuhui Cai')
                        ->where('creators.0.authorOrcid', '0000-0003-1607-3477')
                        ->has('creators.0.authorScopus') //most cases (99%) do have orcid instead
                        
                        ->where('creators.0.authorAffiliation', 'State Key Laboratory of Lithospheric Evolution, Institute of Geology and Geophysics, Chinese Academy of Sciences, 100029 Beijing, China;; Scripps Institution of Oceanography, University of California San Diego, La Jolla, CA 92093;;')
                        
                        ->where('contributors.0.contributorName', 'Magnetics Information Consortium (MagIC)')
                        ->where('contributors.0.contributorRole', 'Distributor')
                        ->has('contributors.0.contributorOrcid')
                        ->has('contributors.0.contributorScopus')
                        ->has('contributors.0.contributorAffiliation')
                        
                        ->where('references.0.referenceDoi', '10.1073/PNAS.2022490118')
                        ->where('references.0.referenceType', 'IsDocumentedBy')
                        ->where('references.0.referenceTitle', "Cai, S., Doctor, R., Tauxe, L., Hendrickson, M., Hua, Q., Leroy, S., & Phon, K. (2021). Archaeomagnetic results from Cambodia in Southeast Asia: Evidence for possible low-latitude flux expulsion. Proceedings of the National Academy of Sciences, 118(11). https://doi.org/10.1073/pnas.2022490118\n")
                        ->has('references.0.referenceHandle')

                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')

                        ->where('downloads.0.fileName', 'magic_contribution_19551')
                        ->where('downloads.0.downloadLink', 'https://earthref.org/MagIC/download/19551/magic_contribution_19551.txt')

                        ->has('researchAspects')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /analogue API endpoint based on mocked CKAN request
     * 
     * @return void
     */
    public function test_analogue_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function($app){
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/package_search_datapublications_analogue.txt'));
        
            $mock = new MockHandler([
                new Response(200, [], $response)
            ]);
        
            $handler = HandlerStack::create($mock);
                    
            return new ApiController(new Client(['handler' => $handler]));
        });
        
        // Retrieve response from API
        $response = $this->get('webservice/api/analogue');
        
        // Check for 200 status response
        $response->assertStatus(200);
        
        // Verify response body contents
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('success')
                ->where('success', true)
                ->where('result.count', 183)
                ->where('result.resultCount', 10)
                ->has('result.results.0', fn (AssertableJson $json) =>
                    $json   
                        ->where('title', 'North America during the Lower Cretaceous: new palaeomagnetic constraints from intrusions in New England (Dataset)')
                        ->where('name', 'eb57658150cb78661c354363879495ba')
                        ->where('portalLink', config('app.url') . '/data-publication/eb57658150cb78661c354363879495ba')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'http://dx.doi.org/10.7288/V4/MAGIC/20031')
                        ->where('doi', '10.7288/V4/MAGIC/20031')

                        ->has('handle')
                        ->where('publisher', '2d5b899a-d9f3-4c77-a4d0-d478a0f5ccd7')

                        ->where('subdomain.0', 'analogue modelling of geologic processes')
                        
                        ->where('description', 'Paleomagnetic, rock magnetic, or geomagnetic data found in the MagIC data repository from a paper titled: Suzanne A. McEnroe (1996). North America during the Lower Cretaceous: new palaeomagnetic constraints from intrusions in New England. Geophysical Journal International 126 (2):477-494. doi:10.1111/J.1365-246X.1996.TB05304.X.')
                        ->where('publicationDate', '1996-01-01')
                        ->where('citation', 'McEnroe, S. A. (1996). <i>North America during the Lower Cretaceous: new palaeomagnetic constraints from intrusions in New England (Dataset)</i> (Version 1) [Data set]. Geophysical Journal International. https://doi.org/10.7288/V4/MAGIC/20031')
                        
                        ->count('creators', 1)
                        ->where('creators.0.authorName', 'Suzanne A. McEnroe')
                        ->has('creators.0.authorOrcid')
                        ->has('creators.0.authorScopus') //most cases (99%) do have orcid instead
                        ->has('creators.0.authorAffiliation')
                        
                        ->where('contributors.0.contributorName', 'Magnetics Information Consortium (MagIC)') 
                        ->where('contributors.0.contributorRole', 'Distributor')
                        ->has('contributors.0.contributorOrcid')
                        ->has('contributors.0.contributorScopus')
                        ->has('contributors.0.contributorAffiliation')

                        ->where('references.0.referenceDoi', '10.1111/J.1365-246X.1996.TB05304.X')
                        ->where('references.0.referenceTitle', "McEnroe, S. A. (1996). North America during the Lower Cretaceous: new palaeomagnetic constraints from intrusions in New England. Geophysical Journal International, 126(2), 477–494. https://doi.org/10.1111/j.1365-246x.1996.tb05304.x\n")
                        ->where('references.0.referenceType', 'IsDocumentedBy')
                        ->where('references.0.referenceDoi', '10.1111/J.1365-246X.1996.TB05304.X')
                        ->has('references.0.referenceHandle')

                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')

                        ->where('downloads.0.fileName', 'magic_contribution_20031')
                        ->where('downloads.0.downloadLink', 'https://earthref.org/MagIC/download/20031/magic_contribution_20031.txt')

                        ->has('researchAspects')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /paleo API endpoint based on mocked CKAN request
     * 
     * @return void
     */
    public function test_paleo_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function($app){
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/package_search_datapublications_paleo.txt'));
        
            $mock = new MockHandler([
                new Response(200, [], $response)
            ]);
        
            $handler = HandlerStack::create($mock);
                    
            return new ApiController(new Client(['handler' => $handler]));
        });
        
        // Retrieve response from API
        $response = $this->get('webservice/api/paleo');
        
        // Check for 200 status response
        $response->assertStatus(200);
        
        // Verify response body contents
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('success')
                ->where('success', true)
                ->where('result.count', 63)
                ->where('result.resultCount', 10)
                ->has('result.results.0', fn (AssertableJson $json) =>
                    $json   
                        ->where('title', 'A time framework based on magnetostratigraphy for the siwalik sediments of the Khaur area, Northern Pakistan (Dataset)')
                        ->where('name', '1487d4269eefd09a91a57cc0e7c3d147')
                        ->where('portalLink', config('app.url') . '/data-publication/1487d4269eefd09a91a57cc0e7c3d147')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'http://dx.doi.org/10.7288/V4/MAGIC/20010')
                        ->where('doi', '10.7288/V4/MAGIC/20010')

                        ->has('handle')
                        ->where('publisher', '2d5b899a-d9f3-4c77-a4d0-d478a0f5ccd7')

                        ->where('subdomain.0', 'paleomagnetism')
                        
                        ->where('description', 'Paleomagnetic, rock magnetic, or geomagnetic data found in the MagIC data repository from a paper titled: Lisa Tauxe, Neil D. Opdyke (1982). A time framework based on magnetostratigraphy for the siwalik sediments of the Khaur area, Northern Pakistan. Palaeogeography, Palaeoclimatology, Palaeoecology 37 (1):43-61. doi:10.1016/0031-0182(82)90057-8.')
                        ->where('publicationDate', '1982-01-01')
                        ->where('citation', 'Tauxe, L., &amp; Opdyke, N. D. (1982). <i>A time framework based on magnetostratigraphy for the siwalik sediments of the Khaur area, Northern Pakistan (Dataset)</i> (Version 1) [Data set]. Palaeogeography, Palaeoclimatology, Palaeoecology. https://doi.org/10.7288/V4/MAGIC/20010')
                        
                        ->count('creators', 2)
                        ->where('creators.0.authorName', 'Lisa Tauxe')
                        ->has('creators.0.authorOrcid')
                        ->has('creators.0.authorScopus') //most cases (99%) do have orcid instead
                        ->has('creators.0.authorName')
                        
                        ->where('contributors.0.contributorName', 'Magnetics Information Consortium (MagIC)') 
                        ->where('contributors.0.contributorRole', 'Distributor')
                        ->has('contributors.0.contributorOrcid')
                        ->has('contributors.0.contributorScopus')
                        ->has('contributors.0.contributorAffiliation')

                        ->where('references.0.referenceDoi', '10.1016/0031-0182(82)90057-8')
                        ->where('references.0.referenceTitle', "Tauxe, L., & Opdyke, N. D. (1982). A time framework based on magnetostratigraphy for the siwalik sediments of the Khaur area, Northern Pakistan. Palaeogeography, Palaeoclimatology, Palaeoecology, 37(1), 43–61. https://doi.org/10.1016/0031-0182(82)90057-8\n")
                        ->where('references.0.referenceType', 'IsDocumentedBy')
                        ->has('references.0.referenceHandle')

                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')

                        ->where('downloads.0.fileName', 'magic_contribution_20010')
                        ->where('downloads.0.downloadLink', 'https://earthref.org/MagIC/download/20010/magic_contribution_20010.txt')

                        ->has('researchAspects')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /microscopy API endpoint based on mocked CKAN request
     * 
     * @return void
     */
    public function test_microscopy_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function($app){
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/package_search_datapublications_microscopy.txt'));
        
            $mock = new MockHandler([
                new Response(200, [], $response)
            ]);
        
            $handler = HandlerStack::create($mock);
                    
            return new ApiController(new Client(['handler' => $handler]));
        });
        
        // Retrieve response from API
        $response = $this->get('webservice/api/microscopy');
        
        // Check for 200 status response
        $response->assertStatus(200);
        
        // Verify response body contents
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('success')
                ->where('success', true)
                ->where('result.count', 228)
                ->where('result.resultCount', 10)
                ->has('result.results.0', fn (AssertableJson $json) =>
                    $json   
                        ->where('title', 'A rock-magnetic and paleointensity study of some Mexican volcanic lava flows during the Latest Pleistocene to the Holocene (Dataset)')
                        ->where('name', '50295d3f4bf09c0329d9a562e37f0036')
                        ->where('portalLink', config('app.url') . '/data-publication/50295d3f4bf09c0329d9a562e37f0036')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'http://dx.doi.org/10.7288/V4/MAGIC/19374')
                        ->where('doi', '10.7288/V4/MAGIC/19374')

                        ->has('handle')
                        ->where('publisher', '2d5b899a-d9f3-4c77-a4d0-d478a0f5ccd7')

                        ->where('subdomain.1', 'microscopy and tomography')
                        
                        ->where('description', 'Eleven Late Quaternary lava flows were sampled in the Chichinautzin volcanic field of central Mexico to determine their magnetic characteristics and absolute paleointensity. The samples studied cover a geological time interval of approximately 0.39 My to 2000 years. Several rock-magnetic experiments were carried out in order to identify the magnetic carriers and to obtain information about their paleomagnetic stability. Continuous susceptibility measurements with temperature in most cases yield reasonably reversible curves with Curie points close to that of almost pure magnetite, which is compatible with low-Ti titanomagnetite resulting from oxi-exsolution. Judging from the ratios of hysteresis parameters, it seems that all samples fall within the pseudo-single domain grain size region, probably indicating a mixture of multidomain and a significant amount of single domain grains. Forty-two samples belonging to six independent cooling units yielded acceptable absolute paleointensity estimates. The NRM fractions used for paleointensity determination range from 0.34 to 0.97 and the quality factors varies between 4.5 and 97.8, being normally greater than 5. The obtained virtual dipole moment values are higher than those recently reported for the past 5 My and to the present day geomagnetic field strength. Individual paleointensity of around 2000 BP is substantially higher than the present day intensity, which is in broad agreement with worldwide archeomagnetic results.')
                        ->where('publicationDate', '2001-01-01')
                        ->where('citation', 'Morales, J., Avto Goguitchaichvili, &amp; Urrutia-Fucugauchi, J. (2001). <i>A rock-magnetic and paleointensity study of some Mexican volcanic lava flows during the Latest Pleistocene to the Holocene (Dataset)</i> (Version 10) [Data set]. Earth, Planets and Space. https://doi.org/10.7288/V4/MAGIC/19374')
                        
                        ->count('creators', 3)
                        ->where('creators.0.authorName', 'Juan Morales')
                        ->has('creators.0.authorOrcid')
                        ->has('creators.0.authorScopus') //most cases (99%) do have orcid instead
                        ->has('creators.0.authorAffiliation')
                        
                        ->where('contributors.0.contributorName', 'Magnetics Information Consortium (MagIC)') 
                        ->where('contributors.0.contributorRole', 'Distributor')
                        ->has('contributors.0.contributorOrcid')
                        ->has('contributors.0.contributorScopus')
                        ->has('contributors.0.contributorAffiliation')

                        ->where('references.0.referenceDoi', '10.1186/BF03351686')
                        ->where('references.0.referenceTitle', "Morales, J., Goguitchaichvili, A., & Urrutia-Fucugauchi, J. (2014). A rock-magnetic and paleointensity study of some Mexican volcanic lava flows during the Latest Pleistocene to the Holocene. Earth, Planets and Space, 53(9), 893–902. https://doi.org/10.1186/bf03351686\n")
                        ->where('references.0.referenceType', 'IsDocumentedBy')
                        ->has('references.0.referenceHandle')

                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')

                        ->where('downloads.0.fileName', 'magic_contribution_19374')
                        ->where('downloads.0.downloadLink', 'https://earthref.org/MagIC/download/19374/magic_contribution_19374.txt')

                        ->has('researchAspects')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /geochemistry API endpoint based on mocked CKAN request
     * 
     * @return void
     */
    public function test_geochemistry_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function($app){
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/package_search_datapublications_geochemistry.txt'));

            $mock = new MockHandler([
                new Response(200, [], $response)
            ]);

            $handler = HandlerStack::create($mock);
                    
            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/geochemistry');

        // Check for 200 status response
        $response->assertStatus(200);

        // Verify response body contents
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('success')
                ->where('success', true)
                ->where('result.count', 273)
                ->where('result.resultCount', 10)
                ->has('result.results.0', fn (AssertableJson $json) =>
                    $json      
                        ->where('title', 'New paleomagnetic results from the Eureka Sound Group: Implications for the age of early Tertiary Arctic biota (Dataset)')
                        ->where('name', '1c6f44820342a409af0701f5997d8528')
                        ->where('portalLink', config('app.url') . '/data-publication/1c6f44820342a409af0701f5997d8528')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'http://dx.doi.org/10.7288/V4/MAGIC/20007')
                        ->where('doi', '10.7288/V4/MAGIC/20007')

                        ->has('handle')
                        ->where('publisher', '2d5b899a-d9f3-4c77-a4d0-d478a0f5ccd7')

                        ->where('subdomain.0', 'geochemistry')
                        
                        ->where('description', 'Paleomagnetic, rock magnetic, or geomagnetic data found in the MagIC data repository from a paper titled: LISA TAUXE, DAVID R. CLARK (1987). New paleomagnetic results from the Eureka Sound Group: Implications for the age of early Tertiary Arctic biota. Geological Society of America Bulletin 99 (6):739. doi:10.1130/0016-7606(1987)992.0.CO;2.')
                        ->where('publicationDate', '1987-01-01')
                        ->where('citation', 'LISA Tauxe, &amp; DAVID R. Clark. (1987). <i>New paleomagnetic results from the Eureka Sound Group: Implications for the age of early Tertiary Arctic biota (Dataset)</i> (Version 6) [Data set]. Geological Society of America Bulletin. https://doi.org/10.7288/V4/MAGIC/20007')
                        
                        ->count('creators', 2)
                        ->where('creators.0.authorName', 'LISA Tauxe')
                        ->has('creators.0.authorOrcid')
                        ->has('creators.0.authorScopus') //most cases (99%) do have orcid instead
                        ->has('creators.0.authorAffiliation')
                        
                        ->where('contributors.0.contributorName', 'Magnetics Information Consortium (MagIC)') 
                        ->where('contributors.0.contributorRole', 'Distributor')
                        ->has('contributors.0.contributorOrcid')
                        ->has('contributors.0.contributorScopus')
                        ->has('contributors.0.contributorAffiliation')

                        ->where('references.0.referenceDoi', '10.1130/0016-7606(1987)99<739:NPRFTE>2.0.CO;2')
                        ->where('references.0.referenceTitle', "TAUXE, L., & CLARK, D. R. (1987). New paleomagnetic results from the Eureka Sound Group: Implications for the age of early Tertiary Arctic biota. Geological Society of America Bulletin, 99(6), 739. https://doi.org/10.1130/0016-7606(1987)99<739:nprfte>2.0.co;2\n")
                        ->where('references.0.referenceType', 'IsDocumentedBy')
                        ->has('references.0.referenceHandle')

                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')

                        ->where('downloads.0.fileName', 'magic_contribution_20007')
                        ->where('downloads.0.downloadLink', 'https://earthref.org/MagIC/download/20007/magic_contribution_20007.txt')

                        ->has('researchAspects')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /geoenergy API endpoint based on mocked CKAN request
     * 
     * @return void
     */
    public function test_geoenergy_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function($app){
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/package_search_datapublications_geoenergy.txt'));

            $mock = new MockHandler([
                new Response(200, [], $response)
            ]);

            $handler = HandlerStack::create($mock);
                    
            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/geoenergy');

        // Check for 200 status response
        $response->assertStatus(200);

        // Verify response body contents
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('success')
                ->where('success', true)
                ->where('result.count', 116)
                ->where('result.resultCount', 10)
                ->has('result.results.0', fn (AssertableJson $json) =>
                    $json      
                        ->where('title', 'The database of ground-motion recordings, site profiles and amplification factors used for the development of the Groningen Ground-Motion Prediction Models')
                        ->where('name', 'c244d12fef1aba659598ded01699d46e')
                        ->where('portalLink', config('app.url') . '/data-publication/c244d12fef1aba659598ded01699d46e')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'http://dx.doi.org/10.24416/uu01-kc2zhq')
                        ->where('doi', '10.24416/uu01-kc2zhq')

                        ->has('handle')
                        ->where('publisher', '68f99610-b8a8-467d-b2ab-df16b9c40028')

                        ->where('subdomain.0', 'geo-energy test beds')
                        
                        ->where('description', 'Induced earthquakes have occurred in the Groningen gas field in the Netherlands, since 1991, almost three decades after production began in 1963. As part of the Hazard and Risk Assment for the Groningen gas field, the field operator NAM (Nederlandse Aardolie Maatschappij BV) developed the Ground-Motion Model or Ground-Motion Prediction Model (GMM/GMPM) for spectral accelerations (Bommer et al., 2022a and b). The model translates seismicity to forces applied to buildings. With the model already published in said research articles, this data publication contains the data that were used to develop the model. This includes: (1) ground-motion recordings, (2) a field-wide shear-wave velocity and lithology model, (3) site characterization data at recording stations and (4) supplementary files, such as MATLAB code for the GMPM as well as key reports and papers. The published database is the result of an unprecedented data acquisition programme, lasting nearly 10 years, and is now provided openly by NAM BV, through EPOS-NL, the Dutch infrastructure for solid Earth sciences. Contact person for this dataset: Michail Ntinalexis - michail.ntinalexis10@alumni.imperial.ac.uk.   ')
                        ->where('publicationDate', '2024-01-01')
                        ->where('citation', 'Ntinalexis, M., Kruiver, P., Bommer, J., Ruigrok, E., Rodriguez-Marek, A., Edwards, B., Pinho, R., Spetzler, J., Obando Hernandez, E., Pefkos, M., Bahrampouri, M., van Onselen, E., Dost, B., &amp; van Elk, J. (2024). <i>The database of ground-motion recordings, site profiles and amplification factors used for the development of the Groningen Ground-Motion Prediction Models</i> (Version 1.0) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-KC2ZHQ')
                        
                        ->count('creators', 14)
                        ->where('creators.0.authorName', 'Ntinalexis, Michail')
                        ->has('creators.0.authorOrcid')
                        ->has('creators.0.authorScopus') //most cases (99%) do have orcid instead
                        ->has('creators.0.authorAffiliation')

                        ->where('references.0.referenceDoi', '10.1007/s10950-022-10120-w')
                        ->where('references.0.referenceTitle', "Bommer, J. J., Stafford, P. J., Ruigrok, E., Rodriguez-Marek, A., Ntinalexis, M., Kruiver, P. P., Edwards, B., Dost, B., & van Elk, J. (2022). Ground-motion prediction models for induced earthquakes in the Groningen gas field, the Netherlands. Journal of Seismology, 26(6), 1157–1184. https://doi.org/10.1007/s10950-022-10120-w\n")
                        ->where('references.0.referenceType', 'IsSupplementTo')
                        ->has('references.0.referenceHandle')

                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')

                        ->where('downloads.0.fileName', 'Data description')
                        ->where('downloads.0.downloadLink', 'https://geo.public.data.uu.nl/vault-nam-groningen-gas-field-ground-motion-model-complete/research-nam-groningen-gas-field-ground-motion-model-complete[1712039436]/original/Data description.pdf')

                        ->has('researchAspects')
                        ->etc()
                    )
                        ->etc()
            );
    }


    /**
     * Test /all endpoint with error received from CKAN
     * 
     * @return void
     */
    public function test_all_error_ckan(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function($app){
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/package_search_error.txt'));

            $mock = new MockHandler([
                new Response(400, [], $response)
            ]);

            $handler = HandlerStack::create($mock);
                    
            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/all');

        // Check for 500 status response
        $response->assertStatus(500);

        // Verify response body contents
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('success')
                ->where('success', false)
                ->where('message', "Error received from CKAN api.")
                ->etc()
        );
    }

    /**
     * Test /all endpoint with empty resultset received from CKAN
     * 
     * @return void
     */
    public function test_all_success_empty(): void
    {
       // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
       $this->app->bind(ApiController::class, function($app){
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/package_search_datapublications_noresults.txt'));

            $mock = new MockHandler([
                new Response(200, [], $response)
            ]);

            $handler = HandlerStack::create($mock);
                    
            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/all');

        // Check for 500 status response
        $response->assertStatus(200);

        // Verify response body contents
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('success')
                ->where('success', true)
                ->has('message')
                ->has('result', fn (AssertableJson $json) =>
                $json
                    ->has('count')
                    ->has('resultCount') 
                    ->has('results') 
                )
        );
    }

    /**
     * Test /all endpoint with Exception returned by GuzzleClient
     * 
     * @return void
     */
    public function test_all_guzzle_exception(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function($app){
            $mock = new MockHandler([
                new RequestException('Error Communicating with Server', new Request('GET', 'test'))
            ]);

            $handler = HandlerStack::create($mock);
                    
            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/all');

        // Check for 500 status response
        $response->assertStatus(500);

        // Verify response body contents
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('success')
                ->where('success', false)
                ->where('message', "Malformed request to CKAN.")
                ->etc()
        );
    }

}
