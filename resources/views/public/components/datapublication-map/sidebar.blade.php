<div class="w-80 bg-base-200 flex flex-col place-items-center justify-self-center overflow-auto">
    @include('public.components.datapublication-map.tabs', [
        'tabs' => [
            ['name' => 'Results', 'component' => 'bla', 'default' => false],
            ['name' => 'Tree', 'component' => 'bli', 'default' => true],
        ],
    ])
</div>
