<div>
    @include('components.gallery', [
        'title' => 'Please read the scenario below to complete this survey:',
        'images' => array(
            "images/surveys/scenario/analogue/analogue_1_fine.jpg",
            "images/surveys/scenario/analogue/analogue_2_fine.jpg",
            "images/surveys/scenario/analogue/analogue_3_fine.jpg",
            "images/surveys/scenario/analogue/analogue_4_fine.jpg",
            "images/surveys/scenario/analogue/analogue_5_fine.jpg",
            "images/surveys/scenario/analogue/analogue_6_fine.jpg",
            "images/surveys/scenario/analogue/analogue_7_fine.jpg",
            "images/surveys/scenario/analogue/analogue_8_fine.jpg",
            ),
        'descriptions' => array(
            "Charlie is in the lab setting up a new model on thrust wedge deformation of the Alps. The previous models did not consider the detailed crustal deformation. The initial setup is made of a Plexiglas box with a conveyor belt system.",
            "The first model failed because by accident she used big grained coarse quartz sand due to inproper sieving. This time she follows a strict protocol setting up the environment.",
            "Because this also fails, she realizes that she needs a different material altogether. On top of that, there are no other sand types available in the lab and the procurement would require careful decisions. She would need a fine-grained material to obtain more detailed structures. For this she starts researching possible material choices",
            "Charlie grows frustrated reading incomplete info available in published papers, and she is trying to move randomly on ISI Web of Knowledge and Google Scholar to find a solution, without success. Her supervisor suggests looking into the EPOS MSL data portal.",
            "On the EPOS MSL data platform she opens the analogue modeling tool and is searching on the first page listing the analogue materials, where she can select between a variety of materials with properties, and selects the granular material and the sand.",
            "The tool suggests a set of properties for her search from which she selects those that are interesting to her.",
            "The proper ranges of the values available are selected and the table view gets updated accordingly.",
            "Based on the filters, she finds the material “fine feldspar” and orders it from her suppliers list. With this she follows the same protocol as from the beginning and the experiment is a success.",
),
        'titleBold' => true
    ])
</div>