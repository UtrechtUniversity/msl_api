<div>
    @include('components.gallery', [
        'title' => 'Please read the scenario below to complete this survey:',
        'images' => array(
            "images/surveys/scenario/testbeds/TestBeds_1_fine.jpg",
            "images/surveys/scenario/testbeds/TestBeds_2_fine.jpg",
            "images/surveys/scenario/testbeds/TestBeds_3_fine.jpg",
            "images/surveys/scenario/testbeds/TestBeds_4_fine.jpg",
            "images/surveys/scenario/testbeds/TestBeds_5_fine.jpg",
            "images/surveys/scenario/testbeds/TestBeds_6_fine.jpg",
            "images/surveys/scenario/testbeds/TestBeds_7_fine.jpg",
            "images/surveys/scenario/testbeds/TestBeds_8_fine.jpg",
            "images/surveys/scenario/testbeds/TestBeds_9_fine.jpg",
            ),
        'descriptions' => array(
           "Stephanie conducts hydraulic stimulation and fluid circulation experiments. She would like to compare her datasets with results from experiments at different scales.",
            "For that she searches through publications and available datasets. Then she reaches out directly to the creators to request the datasets or tries to connect to them through colleagues.",
            "Sadly, not many are responding. The few datasets she gets access to are missing essential information to understand and to interpret the data, such as the experimental setup, sensor specification and exact location. She is also out of people to connect with and reaches out to her supervisor.",
            "Her supervisor points her to the EPOS MSL data platform where she might find some missing information and maybe even more datasets.",
            "She visits the EPOS MSL data platform and goes to the Geo-Energy-Test-Beds tool which shows all field-scale laboratories on a map. She filters labs by the topic “geothermal” and on “hydraulic stimulation.” Selects one to display its details.",
            "She chose the Bedretto site and is specifically interested in boreholes with sensors which collect the following data: “pressure”, “temperature” and “flow”. The sensors she is interested in are listed in the instrumentation section. On the page she can see a list of datasets on the already filtered topic of hydraulic stimulation experiments. She follows the link to the source of one dataset to investigate if it suits her research.",
            "She chooses one data publication and downloads a dataset to her computer. She investigates if the data can help her with her research question. The data is indeed useful, but she needs to know more about the experimental setup.",
            "Visiting again the Geo-Energy-Test-Beds tool and selecting the detail page of the Bedretto site she can see a 3D model of the tunnels and boreholes. She proceeds to click on a more detailed view of the 3D model, to see the distribution of the injection points, sensor location and stimulation protocols.",
            "She presents her results to the supervisor to consult about the next steps.",
        ),
        'titleBold' => true
    ])
</div>