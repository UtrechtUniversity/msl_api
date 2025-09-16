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
            "Charlie is in the lab setting up their new model on Thrust wedge deformation of the Alps. The past models did not consider the detailed crustal deformation and they are planning a new setup. The initial setup is made of a Plexiglas box with a conveyor belt system.",
            "The first model failed because she used big grained coarse quartz sand. She did not sieve the sand properly and assumed that that might be the reason. The follow a strict protocol setting up the environment.",
            "This also fails, and she realizes that she needs a different material altogether. On top of that, there is only that sand available in the lab and the procurement would require careful decisions. She would need a fine-grained material to obtain more detailed structures.",
            "Charlie is frustrated reading incomplete info available in published papers, and she is trying to move randomly on ISI Web of Knowledge and Google Scholar to find a solution, without success. She is searching for suggestions, asking her supervisor and colleagues. After several disappointing passages, an external collaborator suggests looking into the EPOS MSL portal.",
            "Charlie opens the portal and is searching on the first page listing the analogue materials, where she can select between a variety of materials with properties. Charlie selects the granular material and the sand.",
            "The tool suggests a set of properties for her search. She selects those that are interesting to her.",
            "She selects the proper ranges of the values available and the table view gets updated accordingly.",
            "Based on the filters, she finds the material “fine feldspar” and orders it from her suppliers list. Here she follows the same protocol as from the beginning and the experiment is a success.",
            ),
        'titleBold' => true
    ])
</div>