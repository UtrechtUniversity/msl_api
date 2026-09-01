<head>

    @vite(['resources/css/generalStyles/popupWithDirection.css', 'resources/css/dataPublicationMap/datapublications-list.css', 'resources/css/dataPublicationMap/metadata.css', 'resources/css/dataPublicationMap/pagination.css', 'resources/css/dataPublicationMap/in-map-styles.css', 'resources/css/dataPublicationMap/top-menu.css'])
</head>

<body>

    <div id="map-wrapper" class="w-full h-full relative overflow-hidden">

        <div id="map" class="z-0 h-170 relative">
            @include('public.components.datapublication-map.start-screen-overlay')
            @vite(['resources/ts/dataPublication/mapController.ts'])

        </div>

    </div>

</body>
