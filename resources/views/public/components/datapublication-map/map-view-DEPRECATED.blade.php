<head>
    @vite(['resources/css/leafletMapStyles/leaflet-sidebar.css', 'resources/css/leafletMapStyles/datapublications-list.css', 'resources/css/leafletMapStyles/in-map-styles.css'])
</head>

<body>
    <div id="sidebar" class="sidebar collapsed">

    </div>

    <div id="map" class="z-0 h-170"></div>

    @vite(['resources/ts/dataPublication/mapDeprecated.ts'])

</body>
