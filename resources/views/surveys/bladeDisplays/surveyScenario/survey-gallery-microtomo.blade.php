<div>
    @include('components.gallery', [
        'title' => 'Please read the scenario below to complete this survey:',
        'images' => array(
            "images/surveys/scenario/microtomo/MicroTomo_1_fine.jpg",
            "images/surveys/scenario/microtomo/MicroTomo_2_fine.jpg",
            "images/surveys/scenario/microtomo/MicroTomo_3_fine.jpg",
            "images/surveys/scenario/microtomo/MicroTomo_4_fine.jpg",
            "images/surveys/scenario/microtomo/MicroTomo_5_fine.jpg",
            "images/surveys/scenario/microtomo/MicroTomo_6_fine.jpg",
            "images/surveys/scenario/microtomo/MicroTomo_7_fine.jpg",
            "images/surveys/scenario/microtomo/MicroTomo_8_fine.jpg",
            ),
        'descriptions' => array(
            "Isaac is a researcher at Cambridge UK. He is currently researching a pressure solution in Mesozoic limestones in one specific location and developed a specific understanding of the deformation mechanisms involved. Which is based on his personal dataset, but has to be tested using a much wider set of data.",
            "He realizes that he needs good quality data from other locations to validate his hypothesis. He has a few locations in mind which would provide the needed environment for the data.",
            "He checks the map where other locations are, but he is not able to get the funding to travel there, and his time is constrained.",
            "He reaches out to his contacts, but most don’t have the data anymore and he gets informed that they are partly archived online. In one conversation, he is recommended to visit the EPOS MSL data platform.",
            "On the EPOS MSL data platform there is a microscopy and tomography data publication finder tool. He selects 'microscopy', 'limestones' and 'mesozoic', combined with text search. The tool enables previewing images from available data publications, which makes it easy to judge the relevance of a publication",
            "He selects a dataset and gets to the data publication view. There he sees all the different image sets produced in this publication. He gains confidence in the provenance of the samples and the quality of data and description. Then he proceeds to download datasets from publications which suit his research.",
            "He analyzes the new data the obtained and includes these with his own to validate his own hypothesis.",
            "He writes up his article, presents it to an audience and publishes his own underlying dataset on EPOS MSL.",
),
        'titleBold' => true
    ])
</div>