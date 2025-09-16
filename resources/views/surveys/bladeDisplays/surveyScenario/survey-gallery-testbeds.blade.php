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
            "She is browsing through publications and available datasets. Then she reaches out to people requesting the datasets or tries to connect to them through colleagues.",
            "Not many are coming back to her. The few datasets she gets access to are missing essential information to understand and to interpret the data, such as the experimental setup, sensor specification and exact location. She is also out of people to connect with and reaches out to her supervisor.",
            "Her supervisor points her to the MSL website because there she might find some missing information and maybe even more datasets.",
            "She visits the MSL catalog and goes to the Geo-Energy-Test-Beds tool which shows all field laboratories on a map. She filters labs by the topic “geothermal” and on “hydraulic stimulation.” Finds some and selects one to display lab details.",
            "She chose the Bedretto site and is specifically interested in boreholes with sensors which collect the following data: “pressure”, “temperature” and “flow”. The sensors she is interested in are listed in the instrumentation section. On the page she can see a list of datasets on the already filtered topic of hydraulic stimulation experiments. She follows the link to the source of one dataset to investigate if it suits her research.",
            "She chooses one data publication and downloads a dataset to her computer. She investigates if the data is worthwhile.",
            "The data is indeed useful, but she needs to know more about the experimental setup. On the page she can see a 3D model of the tunnels and boreholes. She proceeds to click on a more detailed view of the 3D model, to see the distribution of the injection points, sensor location and stimulation protocols.",
            "Presents to the supervisor the results to consult about the next steps in her research.",
            ),
        'titleBold' => true
    ])
</div>