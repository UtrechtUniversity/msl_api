<div>
    @include('components.gallery', [
        'title' => 'Please read the scenario below to complete this survey:',
        'images' => array(
            "images/surveys/scenario/testbeds/1.png",
            "images/surveys/scenario/testbeds/2.png",
            "images/surveys/scenario/testbeds/3.png",
            "images/surveys/scenario/testbeds/4.png",
            "images/surveys/scenario/testbeds/5.png",
            ),
        'descriptions' => array(
            "This is picture 1 in which something is happening",
            "This is picture 2. A lot more exciting",
            "This is picture 3. The story unfolds",
            "This is picture 4. Crisis peak",
            "This is picture 4. Resolution",
            ),
        'titleBold' => true
    ])
</div>