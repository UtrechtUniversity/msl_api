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
            "Isaac is a professor emeritus in Cambridge UK. He is currently working on researching a pressure solution in Mesozoic limestones in one specific location. Has developed a specific understanding of the deformation mechanisms involved. Which is based on his personal dataset, but has to be tested using a much wider set of data.",
            "He realizes that he needs good quality data from other locations to validate his hypothesis. He has a few locations in mind which would provide the needed environment for the data.",
            "He checks the map where other locations are, but he is not able to get the funding to travel there, and his time is constrained.",
            "Calling somebody, his contacts to figure out the data. Most don’t have the data anymore and he gets informed that they are partly archived online. In one conversation, he is recommended to visit the EPOS MSL platform.",
            "On the page there is a microscopy tool. He selects 'microscopy', 'limestones' and 'Mesozoic', combined with text search, and ends up with 30 datasets in the Galery view. The type of images in the publications are already visible, which makes it easy to skim through the results.",
            "He clicks on a dataset in the Gallery view and gets to the Data publication view. He gains confidence in the provenance of the samples and the quality of data and description. Proceeds to download datasets from publications which suit his research.",
            "He is now including the data of others to validate his own hypothesis.",
            "He writes up his book and decides to publish his own underlying dataset on EPOS MSL.",
            ),
        'titleBold' => true
    ])
</div>