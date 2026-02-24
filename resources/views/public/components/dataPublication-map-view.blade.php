<head>
    @vite([
        'resources/css/leafletMapStyles/leaflet-sidebar.css', 
        'resources/css/leafletMapStyles/datapublications-list.css',
        'resources/css/leafletMapStyles/in-map-styles.css'])
</head>

<div class="w-full h-full">
    <div id="sidebar" class="sidebar collapsed">

    </div>

    <div id="map" class="z-0 h-full w-full"></div>

    @vite(['resources/ts/dataPublication/map.ts'])

</div>
