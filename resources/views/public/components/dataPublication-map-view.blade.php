<head>
    @vite(['resources/css/leaflet-sidebar.css', 'resources/css/datapublications-list.css'])
</head>

<body>
    <div id="sidebar" class="sidebar collapsed">

    </div>

    <div id="map" class="z-0 h-170"></div>

    @vite(['resources/ts/dataPublication/map.ts'])

</body>
