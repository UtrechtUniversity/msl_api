<head>

    @vite(['resources/css/datapublicationMap/datapublications-list.css', 'resources/css/datapublicationMap/dp-pagination.css', 'resources/css/datapublicationMap/in-map-styles.css', 'resources/css/datapublicationMap/top-menu.css'])
</head>

<body>
    <div id="map-wrapper" class="w-full h-full relative overflow-hidden">

        <div id="map" class="z-0 h-170">
            @vite(['resources/ts/dataPublication/mapController.ts'])

        </div>

    </div>

</body>
