<div>
    @include('components.gallery', [
        'title' => $bladeVars['title'],
        'images' => $bladeVars['imageLinks'],
        'descriptions' => $bladeVars['imageDescriptions'],
        'titleBold' => true
    ])
</div>