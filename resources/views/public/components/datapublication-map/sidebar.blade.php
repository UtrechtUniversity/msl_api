<div class="w-80 bg-base-200 flex flex-col place-items-center justify-self-center overflow-auto">
    <div class="bg-primary-100 w-full">
        @include('public.components.datapublication-map.tabs', [
            'tabs' => [
                [
                    'name' => 'Keywords',
                    'component' => 'public.components.datapublication-map.keyword-tree',
                    'default' => true,
                ],
                [
                    'name' => 'Results',
                    'component' => 'public.components.datapublication-map.results',
                    'default' => false,
                ],
            ],
        ])
    </div>
</div>
