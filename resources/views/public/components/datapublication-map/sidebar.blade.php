<div class="w-80 bg-base-200 flex flex-col place-items-center justify-self-center overflow-auto">
    <div class="bg-primary-100 w-full">
        @include('public.components.datapublication-map.tabs', [
            'tabs' => [
                ['name' => 'Keywords', 'component' => 'bli', 'default' => true],
                ['name' => 'Results', 'component' => 'bla', 'default' => false],
            ],
        ])
    </div>
</div>
