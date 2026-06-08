<head>

    @vite(['resources/css/datapublicationMap/datapublications-list.css', 'resources/css/datapublicationMap/in-map-styles.css', 'resources/css/datapublicationMap/top-menu.css'])

</head>

<body>

    <div id="map" class="z-0 h-170"></div>
    <x-datapublication-map.top-menu />

    @vite(['resources/ts/dataPublication/mapController.ts'])

</body>
