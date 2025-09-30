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
            "Andrea is working on designing an experiment, which needs to be presented to her supervisor",            
            "She needs existing data on sandstone friction at specific stress and temperature conditions. What should be used? Which data is reliable? ",            
            "She is overwhelmed by existing literature and has trouble finding suitable data in the right format. She reaches out to her supervisor for advice.",           
            "Her supervisor is not really available for her and recommends her to try out the rock and melt physics tool on the EPOS Multi-Scale Laboratory platform. There she will find trustworthy data.",           
            "Andrea visits the rock and melt physics tool on the website and selects “sandstone” with the property category “shear” in the filters.",          
            "The tool suggests a set of properties and conditions for her search. She selects those that are interesting for her.",            
            "With the fine filter feature, she selects ranges in temperature and normal stress to find the friction coefficient of interest. She then proceeds to download the table.",           
            "She analyzes the data and finds a gap which she can address in her research",            
            "Based this new data she adapts her experimental protocol accordingly and presents it to her supervisors.",
),
        'titleBold' => true
    ])
</div>