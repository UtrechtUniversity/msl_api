<div id="map" style="height: 700px;"></div>

<script>
    function onEachFeature(feature, layer) {
        if (feature.properties) {                                
            var popupContent = `<h5>${feature.properties.title}</h5>
            <p>${feature.properties.msl_organization_name}</p>
            <p>${feature.properties.msl_domain_name}</p>
            <a href="/lab/${feature.properties.name}"><button class="btn btn-primary btn-sm font-medium">View lab information</button></a>`;

            layer.bindPopup(popupContent);
        }
    }

    var features = <?php echo json_encode($locations); ?>;        				

    var map = L.map('map').setView([51.505, -0.09], 4);
    
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var markers = L.markerClusterGroup({
        zoomToBoundsOnClick: true,
        showCoverageOnHover: false
    });
    
    var geoJsonLayer = L.layerGroup();
    
    var extraPopupLayer = L.layerGroup();
    
    for (feature of features) {
        L.geoJSON(feature, {
            onEachFeature: onEachFeature
        }).addTo(geoJsonLayer);        					
    }

    markers.addLayer(geoJsonLayer);
        				
    map.addLayer(markers);
    
</script>