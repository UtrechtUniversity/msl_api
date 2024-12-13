@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Geoview</h1>
        	<div class="card">
        		<div class="card-body">
        			
        			<h2>Box</h2>
        			<div id="map" style="height: 500px;"></div>
        			
        			
        			<script type="text/javascript">
        				var features = {!! $features !!};        					        				
        			
        				var map = L.map('map').setView([51.505, -0.09], 3);
        				
        				L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                        }).addTo(map);
                        
                        for (feature of features) {
                        	parsedFeature = JSON.parse(feature);
        					//console.log(JSON.parse(feature));
							L.geoJSON(parsedFeature).addTo(map);        					
        				}
                        
        			</script>
        			
        			
        			<h2>Point</h2>
        			<div id="map2" style="height: 500px;"></div>
        			
        			
        			<script type="text/javascript">
        				var features = {!! $featuresPoints !!};
        			
        				var map = L.map('map2').setView([51.505, -0.09], 3);
        				
        				L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                        }).addTo(map);
                        
                        for (feature of features) {
                        	parsedFeature = JSON.parse(feature);
        					//console.log(JSON.parse(feature));
							L.geoJSON(parsedFeature).addTo(map);        					
        				}
                        
        			</script>
        			
        			
        			<h2>Points clustered</h2>
        			<div id="map3" style="height: 500px;"></div>
        			
        			
        			<script type="text/javascript">
        				function onEachFeature(feature, layer) {
                            // does this feature have a property named popupContent?
                            if (feature.properties && feature.properties.name) {
                                var popup = layer.bindPopup(feature.properties.name);
                                popup.on("popupopen", function(e) {
                                	extraPopupLayer = L.layerGroup();
                                	L.geoJSON(JSON.parse(feature.properties.area_geojson)).addTo(extraPopupLayer);                                	
                                	map.addLayer(extraPopupLayer);                                	                                	
                                });
                                popup.on("popupclose", function(e) {
                                    map.removeLayer(extraPopupLayer);
                                });
                                
                            }
                            
                            
                        }
        			
        				var features = {!! $featuresPoints !!};
        				        				   				        				        				        			
        				var map = L.map('map3').setView([51.505, -0.09], 3);        				        				
        				
        				L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                        }).addTo(map);
                        
                        var markers = L.markerClusterGroup({
                        	zoomToBoundsOnClick:false,
                        	showCoverageOnHover:false
                        });
                        
                        var geoJsonLayer = L.layerGroup();
                        
                        var extraPopupLayer = L.layerGroup();         		                		
                        
                        for (feature of features) {
                        	parsedFeature = JSON.parse(feature);
							//L.geoJSON(parsedFeature).addTo(geoJsonLayer);
							
							L.geoJSON(parsedFeature, {
								onEachFeature: onEachFeature
							}).addTo(geoJsonLayer);
        				}
        				                        
                        var curPage = 0;
                    	                      	
                    	var popupContentHeader = '<div class="popupcontent">';
                    	
                    	var popupContentFooter = '</div>';                     		
                                                
                        markers.on('clusterclick', function (a) {
                        	var childMarkers = a.layer.getAllChildMarkers();
                        	var popupContent = popupContentHeader;
                        	var first = true;
                        	
                        	var i = 0;
                        	for (childmarker of childMarkers) {
                        		if(first) {
                        			//console.log(childMarkers.length);
                        			                        			
                            		childContent = '<div class="innerPopupContent" id="innerPopupContent-' + i + '">' +
                            			childmarker._popup._content +  
                            			'<div>';
                            			
                        			if(i > 0) {
                        				childContent = childContent + '<a onclick="prevPage(' + childMarkers[i-1]._leaflet_id + ')">prev</a>';
                            		}	
                        			if(i < (childMarkers.length - 1)) {                        			
                        				childContent = childContent + '<a onclick="nextPage(' + childMarkers[i+1]._leaflet_id + ')">next</a>';
                        			}
                            			
                        			childContent = childContent + '</div></div>';                    					
		                            
		                            if(childmarker.feature.properties.area_geojson) {
		                            	if(childmarker.feature.properties.area_geojson.length > 0) {			                            		                            		
                            				extraPopupLayer = L.layerGroup();
                                			L.geoJSON(JSON.parse(childmarker.feature.properties.area_geojson)).addTo(extraPopupLayer);                                	
                                			map.addLayer(extraPopupLayer);
                                		}
                                	}
                                	
                        			first = false;                        			
                    			} else {
                    				childContent = '<div class="innerPopupContent" id="innerPopupContent-' + i + '" style="display: none;">' +
                            			childmarker._popup._content +  
                            			'<div>';
                        			if(i > 0) {
                        				childContent = childContent + '<a onclick="prevPage(' + childMarkers[i-1]._leaflet_id + ')">prev</a>';
                            		}	
                        			if(i < (childMarkers.length - 1)) {
                        				childContent = childContent + '<a onclick="nextPage(' + childMarkers[i+1]._leaflet_id + ')">next</a>'; 
                        			}
                            			
                        			childContent = childContent + '</div></div>';
                    			}
                    			
                        		i = i + 1;	
                    			popupContent = popupContent + childContent;
                        	}
                        	
                        	popupContent = popupContent + popupContentFooter;                        	                        	
                        	
                        	var popup2 = L.popup()
              					.setLatLng(a.layer.getLatLng())
              					.setContent(popupContent)
              					.openOn(map);          					          					          					
                		});
                		
                		map.on('popupclose', function(e) {
                			map.removeLayer(extraPopupLayer);
                			curPage = 0;
                			first = true;
                		});
                		
                		
                		function nextPage(leaflet_id) {                			
                			let elem = document.getElementById("innerPopupContent-" + (curPage + 1));
                			if(elem) {
                				elem = document.getElementById("innerPopupContent-" + curPage);
                    			elem.style.display = 'block';
                    			
                    			elem = document.getElementById("innerPopupContent-" + curPage);
                    			elem.style.display = 'none';
                    			
                    			curPage = curPage + 1;
                    			
                    			elem = document.getElementById("innerPopupContent-" + curPage);
                    			elem.style.display = 'block';
                    			
                    			map.removeLayer(extraPopupLayer);
                    			
                    			if(markers.getLayer(leaflet_id).feature.properties.area_geojson) {
	                            	if(markers.getLayer(leaflet_id).feature.properties.area_geojson.length > 0) {
                            			extraPopupLayer = L.layerGroup();                    			
                                    	L.geoJSON(JSON.parse(markers.getLayer(leaflet_id).feature.properties.area_geojson)).addTo(extraPopupLayer);                                	
                                    	map.addLayer(extraPopupLayer);
                                	}
                            	}
                			}                			                			
                		}
                		
                		function prevPage(leaflet_id) {
                			let elem = document.getElementById("innerPopupContent-" + (curPage - 1));
                			if(elem) {                    			
                    			elem = document.getElementById("innerPopupContent-" + curPage);
                    			elem.style.display = 'none';
                    			
                    			curPage = curPage - 1;
                    			
                    			elem = document.getElementById("innerPopupContent-" + curPage);
                    			elem.style.display = 'block';
                    			
                    			map.removeLayer(extraPopupLayer);
                    			
                    			if(markers.getLayer(leaflet_id).feature.properties.area_geojson) {
	                            	if(markers.getLayer(leaflet_id).feature.properties.area_geojson.length > 0) {
                            			extraPopupLayer = L.layerGroup();                    			
                                    	L.geoJSON(JSON.parse(markers.getLayer(leaflet_id).feature.properties.area_geojson)).addTo(extraPopupLayer);                                	
                                    	map.addLayer(extraPopupLayer);
                                	}
                            	}
                			} 
                		}
                                				
        				markers.addLayer(geoJsonLayer);
        				
        				map.addLayer(markers);
        				
        				
                        
        			</script>
        		</div>
        	</div>        
        </div>
	</div>
</div>
@endsection
