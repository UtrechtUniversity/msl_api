<div>
    @include('components.gallery', [
        'title' => 'Please read the scenario below to complete this survey:',
        'images' => array(
            "images/surveys/scenario/analogue/1.png",
            "images/surveys/scenario/analogue/2.png",
            "images/surveys/scenario/analogue/3.png",
            "images/surveys/scenario/analogue/4.png",
            "images/surveys/scenario/analogue/5.png",
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