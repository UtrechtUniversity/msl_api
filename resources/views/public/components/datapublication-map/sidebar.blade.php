<div class=" bg-base-200 flex flex-col flex-1">
    @include('public.components.datapublication-map.tabs', [
        'tabs' => [
            [
                'name' => 'Keywords',
                'component' => 'public.components.datapublication-map.keyword-tree',
                'default' => false,
                'disabled' => true,
            ],
            [
                'name' => 'Results',
                'component' => 'public.components.datapublication-map.results',
                'default' => true,
            ],
        ],
    ])
</div>
