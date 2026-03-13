<div>
    @include('public.components.gallery', [
        'title' => 'Please read the scenario below to complete this survey:',
        'images' => [
            'images/surveys/scenario/fieldscalelabs/FieldScaleLabs_1_fine.jpg',
            'images/surveys/scenario/fieldscalelabs/FieldScaleLabs_2_fine.jpg',
            'images/surveys/scenario/fieldscalelabs/FieldScaleLabs_3_fine.jpg',
            'images/surveys/scenario/fieldscalelabs/FieldScaleLabs_4_fine.jpg',
            'images/surveys/scenario/fieldscalelabs/FieldScaleLabs_5_fine.jpg',
            'images/surveys/scenario/fieldscalelabs/FieldScaleLabs_6_fine.jpg',
            'images/surveys/scenario/fieldscalelabs/FieldScaleLabs_7_fine.jpg',
            'images/surveys/scenario/fieldscalelabs/FieldScaleLabs_8_fine.jpg',
            'images/surveys/scenario/fieldscalelabs/FieldScaleLabs_9_fine.jpg',
            'images/surveys/scenario/fieldscalelabs/FieldScaleLabs_10_fine.jpg',
        ],
        'descriptions' => [
            'Stefania is a researcher with a focus on large scale experiments and wants to compare her research with data from other monitoring sites.',
            'She starts by searching for online and publication-related datasets, hoping to find open data and metadata. She also reaches out directly to the authors to request the datasets or tries to connect to them through colleagues.',
            'Sadly, not many are responding. The few datasets she gets access to are missing essential information to understand and to interpret the data, such as the experimental setup, sensor specification and exact location. Frustrated she reaches out to her supervisor.',
            'Her supervisor points her to the EPOS MSL data platform where she might find some missing information and maybe even more datasets.',
            'She visits the EPOS MSL data platform and goes to the Field-Scale-Labs tool, which shows all field-scale laboratories in Europe on a map. She filters labs based on keywords and sensors or instrumentation.',
            'On the lab page she can see the map overview of the site including a 3D model. On the bottom left she can access the data from the sensors and instrumentation of the site.',
            'The button redirects her to another page. She selects the types of sensors and instruments she is interested in and provides a time frame in which the data has been collected.',
            'She then downloads all relevant datasets as indicated in the tool.',
            'She uses software on her computer to analyze the data.',
            'Based on her analysis she finishes a major step in her research and stays motivated to share her data back on MSL.',
        ],
        'titleBold' => true,
    ])
</div>
