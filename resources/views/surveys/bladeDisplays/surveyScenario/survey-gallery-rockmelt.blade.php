<div>
    @include('components.gallery', [
        'title' => 'Please read the scenario below to complete this survey:',
        'images' => array(
            "images/surveys/scenario/rockmelt/RockMeltPhy_1_fine.jpg",
            "images/surveys/scenario/rockmelt/RockMeltPhy_2_fine.jpg",
            "images/surveys/scenario/rockmelt/RockMeltPhy_3_fine.jpg",
            "images/surveys/scenario/rockmelt/RockMeltPhy_4_fine.jpg",
            "images/surveys/scenario/rockmelt/RockMeltPhy_5_fine.jpg",
            "images/surveys/scenario/rockmelt/RockMeltPhy_6_fine.jpg",
            "images/surveys/scenario/rockmelt/RockMeltPhy_7_fine.jpg",
            "images/surveys/scenario/rockmelt/RockMeltPhy_8_fine.jpg",
            "images/surveys/scenario/rockmelt/RockMeltPhy_9_fine.jpg",
            ),
        'descriptions' => array(
            "Andrea is working on a design experiment protocol, which needs to be presented to her supervisor",            
            "She needs existing data on sandstone friction at specific stress and temperature conditions. What should be used? Which data is reliable? ",            
            "She is overwhelmed by existing literature and has trouble finding suitable data in the right format. She reaches out to her supervisor for advice.",           
            "Her supervisor is not really available for her and recommends her to try out the Rock and Melt physics tool on the EPOS Multi-Scale Laboratory platform. There she will find trustworthy data.",           
            "Andrea visits the website and selects “sandstone” with the property category “shear” in the filter tool. Rename property to property set.",          
            "The tool suggests a set of properties for her search. She selects those that are interesting for her.",            
            "With the fine filter feature, she selects ranges in T and σ(n) to find the μ values of interest. Then proceeds to download the table.",           
            "She analyzes the data and finds a gap for which she can produce research",            
            "That obtained data is used to adapt her experiment protocol and to present it to her supervisor.", 
            ),
        'titleBold' => true
    ])
</div>