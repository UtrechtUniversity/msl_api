@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Geoview Labs</h1>
        	<div class="card">
        		<div class="card-body">
        			
        			<div id="map" style="height: 500px;"></div>
        			
        			
        			<script type="text/javascript">
        				function onEachFeature(feature, layer) {
                            // does this feature have a property named popupContent?
                            if (feature.properties && feature.properties.name) {
                                layer.bindPopup(feature.properties.name);
                            }
                        }
        			
        				var features = {!! $features !!};
        				
        				//console.log(features);        				        				
        			
        				var map = L.map('map').setView([51.505, -0.09], 3);
        				
        				L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                        }).addTo(map);
                        
                        for (feature of features) {
							L.geoJSON(feature, {
								onEachFeature: onEachFeature
							}).addTo(map);        					
        				}
                        
        			</script>
        		</div>
        	</div>        
        </div>
	</div>
</div>
@endsection
