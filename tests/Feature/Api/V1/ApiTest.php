<?php

namespace Tests\Feature\Api\V1;

use App\Http\Controllers\API\V1\ApiController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * Test /all API endpoint based on mocked CKAN request
     */
    public function test_all_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V1/package_search_datapublications_all.txt'));

            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/all');

        // Check for 200 status response
        $response->assertStatus(200);

        // Verify response body contents
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', true)
                ->where('result.count', 670)
                ->where('result.resultCount', 10)
                ->has(
                    'result.results.0',
                    fn(AssertableJson $json) => $json
                        ->where('title', 'Micro Computational Tomography, Acoustic Emission and rock temperature data from frost weathering tests on Dachstein Limestone')
                        ->where('name', 'a6434e5f71718999519d775c8239c8a3')
                        ->where('portalLink', config('app.url') . '/data-publication/a6434e5f71718999519d775c8239c8a3')
                        ->where('doi', '10.24416/uu01-q5k96z')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'https://public.yoda.uu.nl/geo/UU01/Q5K96Z.html')
                        ->where('publisher', '1d22b8b4-b362-4d37-97ed-4886cdc465b1')
                        ->where('description', 'We tested the efficacy of frequent diurnal freeze-thaw cycles (FT-1) and sustained freezing cycles (FT-2) on low-porosity Dachstein limestone (0.1 % porosity). For FT-1, we exposed three rock samples with different saturation regimes (30%, 70%, 100%) to 20 freeze-thaw cycles between 10 and -10Â°C while monitoring rock temperature and acoustic emission (AE). We scanned the rock samples using micro computational tomography (CT) before the first cycle, after 5, 10, 15 and 20 cycles. For FT-2, we exposed three rock samples with different saturation regimes (30%, 70%, 100%) to one sustained freezing cycle with 66 h freezing at -10Â°C while monitoring rock temperature and AE.  We scanned the rock samples using micro CT before the first cycle and after the end of the experiment. The data set comprises all micro CT scans, rock temperature and AE data.')
                        ->where('publicationDate', '')
                        ->where('citation', 'Draebing, D. (2023). <i>Micro Computational Tomography, Acoustic Emission and rock temperature data from frost weathering tests on Dachstein Limestone</i> (Version 1) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-Q5K96Z')
                        ->count('creators', 1)
                        ->where('creators.0.authorName', 'Daniel Draebing')
                        ->has('creators.0.authorOrcid')
                        ->has('creators.0.authorScopus')
                        ->has('creators.0.authorAffiliation')
                        ->count('contributors', 5)
                        ->where('contributors.0.contributorName', 'Mayer, Till')
                        ->where('contributors.0.contributorRole', 'Researcher')
                        ->has('contributors.0.contributorOrcid')
                        ->has('contributors.0.contributorScopus')
                        ->has('contributors.0.contributorAffiliation')
                        ->has('references')
                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')
                        ->count('downloads', 1)
                        ->where('downloads.0.fileName', 'Original data')
                        ->where('downloads.0.downloadLink', 'https://geo.public.data.uu.nl:443/vault-frost-shared/research-frost-shared[1697635081]/original/Original data/')
                        ->has('researchAspects')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /rock_physics API endpoint based on mocked CKAN request
     */
    public function test_rockphysics_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V1/package_search_datapublications_rockphysics.txt'));

            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/rock_physics');

        // Check for 200 status response
        $response->assertStatus(200);

        // Verify response body contents
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', true)
                ->where('result.count', 256)
                ->where('result.resultCount', 10)
                ->has(
                    'result.results.0',
                    fn(AssertableJson $json) => $json
                        ->where('title', 'Micro Computational Tomography, Acoustic Emission and rock temperature data from frost weathering tests on Dachstein Limestone')
                        ->where('name', 'a6434e5f71718999519d775c8239c8a3')
                        ->where('portalLink', config('app.url') . '/data-publication/a6434e5f71718999519d775c8239c8a3')
                        ->where('doi', '10.24416/uu01-q5k96z')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'https://public.yoda.uu.nl/geo/UU01/Q5K96Z.html')
                        ->where('publisher', '1d22b8b4-b362-4d37-97ed-4886cdc465b1')
                        ->count('subdomain', 3)
                        ->where('description', 'We tested the efficacy of frequent diurnal freeze-thaw cycles (FT-1) and sustained freezing cycles (FT-2) on low-porosity Dachstein limestone (0.1 % porosity). For FT-1, we exposed three rock samples with different saturation regimes (30%, 70%, 100%) to 20 freeze-thaw cycles between 10 and -10Â°C while monitoring rock temperature and acoustic emission (AE). We scanned the rock samples using micro computational tomography (CT) before the first cycle, after 5, 10, 15 and 20 cycles. For FT-2, we exposed three rock samples with different saturation regimes (30%, 70%, 100%) to one sustained freezing cycle with 66 h freezing at -10Â°C while monitoring rock temperature and AE.  We scanned the rock samples using micro CT before the first cycle and after the end of the experiment. The data set comprises all micro CT scans, rock temperature and AE data.')
                        ->where('publicationDate', '')
                        ->where('citation', 'Draebing, D. (2023). <i>Micro Computational Tomography, Acoustic Emission and rock temperature data from frost weathering tests on Dachstein Limestone</i> (Version 1) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-Q5K96Z')
                        ->count('creators', 1)
                        ->where('creators.0.authorName', 'Daniel Draebing')
                        ->count('contributors', 5)
                        ->where('contributors.0.contributorName', 'Mayer, Till')
                        ->where('contributors.0.contributorRole', 'Researcher')
                        ->has('contributors.0.contributorOrcid')
                        ->has('contributors.0.contributorScopus')
                        ->has('contributors.0.contributorAffiliation')
                        ->has('references')
                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')
                        ->count('downloads', 1)
                        ->where('downloads.0.fileName', 'Original data')
                        ->where('downloads.0.downloadLink', 'https://geo.public.data.uu.nl:443/vault-frost-shared/research-frost-shared[1697635081]/original/Original data/')
                        ->has('researchAspects')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /analogue API endpoint based on mocked CKAN request
     */
    public function test_analogue_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V1/package_search_datapublications_analogue.txt'));

            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/analogue');

        // Check for 200 status response
        $response->assertStatus(200);

        // Verify response body contents
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', true)
                ->where('result.count', 183)
                ->where('result.resultCount', 10)
                ->has(
                    'result.results.1',
                    fn(AssertableJson $json) => $json
                        ->where('title', 'Which Ion Dominates Temperature and Pressure Response of Halide Perovskites and Elpasolites?')
                        ->where('name', 'a8dd3f7d8b3b49d39f97316ccb77f70c')
                        ->where('portalLink', config('app.url') . '/data-publication/a8dd3f7d8b3b49d39f97316ccb77f70c')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'https://public.yoda.uu.nl/science/UU01/W60H58.html')
                        ->where('doi', '10.24416/uu01-w60h58')
                        ->has('handle')
                        ->where('publisher', '1d22b8b4-b362-4d37-97ed-4886cdc465b1')
                        ->count('subdomain', 3)
                        ->where('description', 'Halide perovskite and elpasolite semiconductors are extensively studied for optoelectronic applications due to their excellent performance together with significant chemical and structural flexibility. However, there is still limited understanding of how their basic elastic properties vary with crystal orientation, composition and temperature, which is relevant for synthesis and device operation. To address this, we performed temperature- and pressure-dependent synchrotron-based powder X-ray diffraction (XRD). In contrast to previous pressure-dependent XRD studies, our relatively low pressures (ambient to 0.06 GPa) enabled us to investigate the elastic properties of halide perovskites and elpasolites in their ambient crystal structure. We find that halide perovskites and elpasolites show common trends in the bulk modulus and thermal expansivity. Both materials become softer as the halide ionic radius increases from Cl to Br to I, exhibiting higher compressibility and larger thermal expansivity. The mixed-halide compositions show intermediate properties to the pure compounds. Contrary, cations show a minor effect on the elastic properties. Finally, we observe that thermal phase transitions in e.g., MAPbI3 and CsPbCl3 lead to a softening of the lattice, together with negative expansivity for certain crystal axes, already tens of degrees away from the transition temperature. Hence, the range in which the phase transition affects thermal and elastic properties is substantially broader than previously thought. These findings highlight the importance of considering the temperature-dependent elastic properties of these materials, since stress induced during manufacturing or temperature sweeps can significantly impact the stability and performance of the corresponding devices. ')
                        ->where('citation', 'Muscarella, L. A., &amp; Jöbsis, H. J. (2023). <i>Which Ion Dominates Temperature and Pressure Response of Halide Perovskites and Elpasolites?</i> (Version 1.0) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-W60H58')
                        ->count('creators', 2)
                        ->where('creators.0.authorName', 'Loreta A. Muscarella')
                        ->has('creators.0.authorOrcid')
                        ->has('creators.0.authorScopus')
                        ->has('creators.0.authorAffiliation')
                        ->count('contributors', 7)
                        ->where('contributors.0.contributorName', 'Baumgartner, Bettina')
                        ->where('contributors.0.contributorRole', 'DataCollector')
                        ->has('contributors.0.contributorOrcid')
                        ->has('contributors.0.contributorScopus')
                        ->has('contributors.0.contributorAffiliation')
                        ->has('references')
                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')
                        ->has('downloads')
                        ->has('researchAspects')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /paleo API endpoint based on mocked CKAN request
     */
    public function test_paleo_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V1/package_search_datapublications_paleo.txt'));

            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/paleo');

        // Check for 200 status response
        $response->assertStatus(200);

        // Verify response body contents
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', true)
                ->where('result.count', 63)
                ->where('result.resultCount', 10)
                ->has(
                    'result.results.0',
                    fn(AssertableJson $json) => $json
                        ->where('title', 'Data supplement to: Local magnetic anomalies in rugged volcanic terrain explain bias in paleomagnetic data: consequences for sampling - UPDATED')
                        ->where('name', '5f8c41071cf366c074a907a23e0b7db3')
                        ->where('portalLink', config('app.url') . '/data-publication/5f8c41071cf366c074a907a23e0b7db3')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'https://public.yoda.uu.nl/geo/UU01/B6JJC0.html')
                        ->where('doi', '10.24416/uu01-b6jjc0')
                        ->has('handle')
                        ->where('publisher', '1d22b8b4-b362-4d37-97ed-4886cdc465b1')
                        ->where('subdomain.0', 'paleomagnetism')
                        ->where('citation', 'Meyer, R. (2023). <i>Data supplement to: Local magnetic anomalies in rugged volcanic terrain explain bias in paleomagnetic data: consequences for sampling - UPDATED</i> (Version 1.0) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-B6JJC0')
                        ->count('creators', 1)
                        ->where('creators.0.authorName', 'Romy Meyer')
                        ->has('creators.0.authorOrcid')
                        ->has('creators.0.authorScopus')
                        ->count('contributors', 2)
                        ->where('contributors.0.contributorName', 'de Groot, Lennart V.')
                        ->where('contributors.0.contributorRole', 'ProjectLeader')
                        ->has('contributors.0.contributorOrcid')
                        ->has('contributors.0.contributorScopus')
                        ->has('contributors.0.contributorAffiliation')
                        ->has('references')
                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')
                        ->has('downloads')
                        ->has('researchAspects')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /microscopy API endpoint based on mocked CKAN request
     */
    public function test_microscopy_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V1/package_search_datapublications_microscopy.txt'));

            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/microscopy');

        // Check for 200 status response
        $response->assertStatus(200);

        // Verify response body contents
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', true)
                ->where('result.count', 228)
                ->where('result.resultCount', 10)
                ->has(
                    'result.results.0',
                    fn(AssertableJson $json) => $json
                        ->where('title', 'Micro Computational Tomography, Acoustic Emission and rock temperature data from frost weathering tests on Dachstein Limestone')
                        ->where('name', 'a6434e5f71718999519d775c8239c8a3')
                        ->where('portalLink', config('app.url') . '/data-publication/a6434e5f71718999519d775c8239c8a3')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'https://public.yoda.uu.nl/geo/UU01/Q5K96Z.html')
                        ->where('doi', '10.24416/uu01-q5k96z')
                        ->has('handle')
                        ->where('publisher', '1d22b8b4-b362-4d37-97ed-4886cdc465b1')
                        ->count('subdomain', 3)
                        ->where('subdomain.0', 'microscopy and tomography')
                        ->where('description', 'We tested the efficacy of frequent diurnal freeze-thaw cycles (FT-1) and sustained freezing cycles (FT-2) on low-porosity Dachstein limestone (0.1 % porosity). For FT-1, we exposed three rock samples with different saturation regimes (30%, 70%, 100%) to 20 freeze-thaw cycles between 10 and -10Â°C while monitoring rock temperature and acoustic emission (AE). We scanned the rock samples using micro computational tomography (CT) before the first cycle, after 5, 10, 15 and 20 cycles. For FT-2, we exposed three rock samples with different saturation regimes (30%, 70%, 100%) to one sustained freezing cycle with 66 h freezing at -10Â°C while monitoring rock temperature and AE.  We scanned the rock samples using micro CT before the first cycle and after the end of the experiment. The data set comprises all micro CT scans, rock temperature and AE data.')
                        ->where('citation', 'Draebing, D. (2023). <i>Micro Computational Tomography, Acoustic Emission and rock temperature data from frost weathering tests on Dachstein Limestone</i> (Version 1) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-Q5K96Z')
                        ->count('creators', 1)
                        ->where('creators.0.authorName', 'Daniel Draebing')
                        ->has('creators.0.authorOrcid')
                        ->has('creators.0.authorScopus')
                        ->has('creators.0.authorAffiliation')
                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')
                        ->where('downloads.0.fileName', 'Original data')
                        ->where('downloads.0.downloadLink', 'https://geo.public.data.uu.nl:443/vault-frost-shared/research-frost-shared[1697635081]/original/Original data/')
                        ->has('researchAspects')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /geochemistry API endpoint based on mocked CKAN request
     */
    public function test_geochemistry_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V1/package_search_datapublications_geochemistry.txt'));

            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/geochemistry');
        // Check for 200 status response
        $response->assertStatus(200);

        // Verify response body contents
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', true)
                ->where('result.count', 273)
                ->where('result.resultCount', 10)
                ->has(
                    'result.results.0',
                    fn(AssertableJson $json) => $json
                        ->where('title', 'Data supplement to "A Study in Blue: Secondary Copper-rich Minerals and their Associated Bacterial Diversity in Icelandic Lava Tubes"')
                        ->where('name', '40fd65d78aec4ff7ee7231a5c2230ce2')
                        ->where('portalLink', config('app.url') . '/data-publication/40fd65d78aec4ff7ee7231a5c2230ce2')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'https://public.yoda.uu.nl/geo/UU01/I32Z95.html')
                        ->where('doi', '10.24416/uu01-i32z95')
                        ->has('handle')
                        ->where('publisher', '1d22b8b4-b362-4d37-97ed-4886cdc465b1')
                        ->count('subdomain', 2)
                        ->where('subdomain.0', 'geochemistry')
                        ->where('citation', 'Kopacz, N. (2022). <i>Data supplement to "A Study in Blue: Secondary Copper-rich Minerals and their Associated Bacterial Diversity in Icelandic Lava Tubes"</i> (Version 1.0) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-I32Z95')
                        ->count('creators', 1)
                        ->where('creators.0.authorName', 'Nina Kopacz')
                        ->has('creators.0.authorOrcid')
                        ->has('creators.0.authorScopus')
                        ->has('creators.0.authorAffiliation')
                        ->count('contributors', 17)
                        ->where('contributors.0.contributorName', 'Kopacz, Nina')
                        ->where('contributors.0.contributorRole', 'ContactPerson')
                        ->has('contributors.0.contributorOrcid')
                        ->has('contributors.0.contributorScopus')
                        ->has('contributors.0.contributorAffiliation')
                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')
                        ->has('researchAspects')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /geoenergy API endpoint based on mocked CKAN request
     */
    public function test_geoenergy_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V1/package_search_datapublications_geoenergy.txt'));

            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/geoenergy');

        // Check for 200 status response
        $response->assertStatus(200);

        // Verify response body contents
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', true)
                ->where('result.count', 116)
                ->where('result.resultCount', 10)
                ->has(
                    'result.results.0',
                    fn(AssertableJson $json) => $json
                        ->where('title', 'The database of ground-motion recordings, site profiles and amplification factors used for the development of the Groningen Ground-Motion Prediction Models')
                        ->where('name', 'c244d12fef1aba659598ded01699d46e')
                        ->where('portalLink', config('app.url') . '/data-publication/c244d12fef1aba659598ded01699d46e')
                        ->has('license')
                        ->has('version')
                        ->where('source', 'https://public.yoda.uu.nl/geo/UU01/KC2ZHQ.html')
                        ->where('doi', '10.24416/uu01-kc2zhq')
                        ->has('handle')
                        ->where('publisher', '1d22b8b4-b362-4d37-97ed-4886cdc465b1')
                        ->where('subdomain.0', 'geo-energy test beds')
                        ->where('description', 'Induced earthquakes have occurred in the Groningen gas field in the Netherlands, since 1991, almost three decades after production began in 1963. As part of the Hazard and Risk Assment for the Groningen gas field, the field operator NAM (Nederlandse Aardolie Maatschappij BV) developed the Ground-Motion Model or Ground-Motion Prediction Model (GMM/GMPM) for spectral accelerations (Bommer et al., 2022a and b). The model translates seismicity to forces applied to buildings. With the model already published in said research articles, this data publication contains the data that were used to develop the model. This includes: (1) ground-motion recordings, (2) a field-wide shear-wave velocity and lithology model, (3) site characterization data at recording stations and (4) supplementary files, such as MATLAB code for the GMPM as well as key reports and papers. The published database is the result of an unprecedented data acquisition programme, lasting nearly 10 years, and is now provided openly by NAM BV, through EPOS-NL, the Dutch infrastructure for solid Earth sciences. Contact person for this dataset: Michail Ntinalexis - michail.ntinalexis10@alumni.imperial.ac.uk.   ')
                        ->where('citation', 'Ntinalexis, M., Kruiver, P., Bommer, J., Ruigrok, E., Rodriguez-Marek, A., Edwards, B., Pinho, R., Spetzler, J., Obando Hernandez, E., Pefkos, M., Bahrampouri, M., van Onselen, E., Dost, B., &amp; van Elk, J. (2024). <i>The database of ground-motion recordings, site profiles and amplification factors used for the development of the Groningen Ground-Motion Prediction Models</i> (Version 1.0) [Data set]. Utrecht University. https://doi.org/10.24416/UU01-KC2ZHQ')
                        ->count('creators', 14)
                        ->where('creators.0.authorName', 'Michail Ntinalexis')
                        ->has('creators.0.authorOrcid')
                        ->has('creators.0.authorScopus') // most cases (99%) do have orcid instead
                        ->has('creators.0.authorAffiliation')
                        ->has('laboratories')
                        ->has('materials')
                        ->has('spatial')
                        ->has('locations')
                        ->has('coveredPeriods')
                        ->has('collectionPeriods')
                        ->has('maintainer')
                        ->count('downloads', 8)
                        ->where('downloads.0.fileName', 'Field-wide shear-wave velocity and lithology model')
                        ->where('downloads.0.downloadLink', 'https://geo.public.data.uu.nl:443/vault-nam-groningen-gas-field-ground-motion-model-complete/research-nam-groningen-gas-field-ground-motion-model-complete[1712039436]/original/Field-wide shear-wave velocity and lithology model/')
                        ->has('researchAspects')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /all endpoint with error received from CKAN
     */
    public function test_all_error_ckan(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V1/package_search_error.txt'));

            $mock = new MockHandler([
                new Response(400, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/all');

        // Check for 500 status response
        $response->assertStatus(500);

        // Verify response body contents
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', false)
                ->where('message', 'Error received from CKAN api.')
                ->etc()
        );
    }

    /**
     * Test /all endpoint with empty resultset received from CKAN
     */
    public function test_all_success_empty(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V1/package_search_datapublications_noresults.txt'));

            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/all');

        // Check for 500 status response
        $response->assertStatus(200);

        // Verify response body contents
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', true)
                ->has('message')
                ->has(
                    'result',
                    fn(AssertableJson $json) => $json
                        ->has('count')
                        ->has('resultCount')
                        ->has('results')
                )
        );
    }

    /**
     * Test /all endpoint with Exception returned by GuzzleClient
     */
    public function test_all_guzzle_exception(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function ($app) {
            $mock = new MockHandler([
                new RequestException('Error Communicating with Server', new Request('GET', 'test')),
            ]);

            $handler = HandlerStack::create($mock);

            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/all');

        // Check for 500 status response
        $response->assertStatus(500);

        // Verify response body contents
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', false)
                ->where('message', 'Malformed request to CKAN.')
                ->etc()
        );
    }

    /**
     * Test /facilities API endpoint based on mocked CKAN request
     */
    public function test_facilities_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V1/package_search_facilities_117.txt'));
            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('webservice/api/facilities');

        // Check for 200 status response
        $response->assertStatus(200);

        // Verify response body contents
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', true)
                ->where('result.count', 117)
                ->where('result.resultCount', 10)
                ->has(
                    'result.results.0',
                    fn(AssertableJson $json) => $json
                        ->where('name', 'HelLabs - Geophysical laboratory')
                        ->where('description', 'Paleomagnetism, rock magnetism and petrophysics')
                        ->where('descriptionHtml', "<p>Paleomagnetism, rock magnetism and petrophysics</p>\n")
                        ->where('domain', 'Paleomagnetism')
                        ->where('latitude', '60.2026')
                        ->where('longitude', '24.9576')
                        ->has('altitude')
                        ->where('portalLink', 'http://localhost/lab/fa7cdfad1a5aaf8370ebeda47a1ff1c3')
                        ->where('organization', 'University of Helsinki')
                        ->where('equipment.0.title', '2G cryogenic magnetometer')
                        ->where('equipment.0.description', 'cryogenic magnetometer for discrete samples, 2G Model 755 DC,')
                        ->where('equipment.0.descriptionHtml', "<p>cryogenic magnetometer for discrete samples, 2G Model 755 DC,</p>\n")
                        ->where('equipment.0.domain', 'Paleomagnetism')
                        ->where('equipment.0.category', 'Permanent')
                        ->where('equipment.0.group', 'Cryogenic Magnetometer')
                        ->where('equipment.0.type', 'Magnetometer')
                        ->where('equipment.0.brand', '2G')
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * Test /facilities API endpoint for malformed bounding box errors
     */
    public function test_facilities_bounding_box_errors(): void
    {
        // over the bounds of +/-90 or +/-180
        $responseBoundingBox = $this->get('webservice/api/facilities?boundingBox=180,-90,180,91');
        $responseBoundingBox->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', false)
                ->where('message', 'Malformed request to CKAN. "boundingBox" not in correct format or values exceeding bounds. Use "." for decimals. E.g: 12.4 instead of 12,4')
                ->etc()
        );

        // comma instead of dot for decimal
        $responseBoundingBox = $this->get('webservice/api/facilities?boundingBox=180,-90,180,85,5');
        $responseBoundingBox->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', false)
                ->where('message', 'Malformed request to CKAN. "boundingBox" not in correct format or values exceeding bounds. Use "." for decimals. E.g: 12.4 instead of 12,4')
                ->etc()
        );

        // switch first two inputs
        $responseBoundingBox = $this->get('webservice/api/facilities?boundingBox=-90,180,180,85');
        $responseBoundingBox->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', false)
                ->where('message', 'Malformed request to CKAN. "boundingBox" not in correct format or values exceeding bounds. Use "." for decimals. E.g: 12.4 instead of 12,4')
                ->etc()
        );

        // 3 inputs
        $responseBoundingBox = $this->get('webservice/api/facilities?boundingBox=-90,180,180');
        $responseBoundingBox->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', false)
                ->where('message', 'Malformed request to CKAN. "boundingBox" not in correct format or values exceeding bounds. Use "." for decimals. E.g: 12.4 instead of 12,4')
                ->etc()
        );

        // 5 inputs
        $responseBoundingBox = $this->get('webservice/api/facilities?boundingBox=-90,180,180,55');
        $responseBoundingBox->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', false)
                ->where('message', 'Malformed request to CKAN. "boundingBox" not in correct format or values exceeding bounds. Use "." for decimals. E.g: 12.4 instead of 12,4')
                ->etc()
        );

        // string input
        $responseBoundingBox = $this->get('webservice/api/facilities?boundingBox=180,90,180,nine');
        $responseBoundingBox->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', false)
                ->where('message', 'Malformed request to CKAN. "boundingBox" not in correct format or values exceeding bounds. Use "." for decimals. E.g: 12.4 instead of 12,4')
                ->etc()
        );
    }

    /**
     * Test /facilities API endpoint corect bounding box
     */
    public function test_facilities_bounding_box_success(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(ApiController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V1/package_search_facilities_boundingbox.txt'));
            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new ApiController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('http://localhost:8000/webservice/api/facilities?boundingBox=14.05,54.08,45.25,63.86');

        // Check for 200 status response
        $response->assertStatus(200);

        // Verify response body contents
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', true)
                ->where('result.count', 2)
                ->etc()
        );
    }
}
