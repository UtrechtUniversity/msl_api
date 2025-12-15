import { LatLng, Rectangle, Map, MarkerClusterGroup, LeafletMouseEvent, Layer } from "leaflet";
import { Feature } from 'geojson'
import { DataPublication, GeoJsonDataPublication } from "../types/geojson";

// If we dont assign L, typescript is complaining about using a UMD global in a module.
const L = window.L;

class MapApp {
    map: Map;
    markers: MarkerClusterGroup;

    constructor() {
        this.map = L.map('map')
        this.markers = L.markerClusterGroup({
            zoomToBoundsOnClick: true,
            showCoverageOnHover: false
        });
        this.drawMap();

    }
    // Create the map in the beginning
    async init() {
        await this.mouseEventHandling();
    }


    drawMap() {
        this.resetMapView()
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(this.map);
        this.resetMapView()
        return;
    }


    async getResponse(boundingBox: string): Promise<GeoJsonDataPublication> {
        const parameters = { boundingBox, limit: '10' }
        const params = new URLSearchParams(parameters);

        const route = '/api/geoJsonDataPublications?' + params;


        let response: Response | undefined;
        try {
            response = await fetch(route, {
                method: "GET",
            });
        } catch (e) {
            throw new Error("Something went wrong internally. Please contact MSL.");
        }
        if (!response || response.status !== 200) {
            throw new Error("Something went wrong internally. Please contact MSL.");
        }
        const data = (await response.json()).data


        return data;
    }

    async getAndDrawResponse(geoList: GeoJsonDataPublication) {
        // We want to be able to pass information of the publication inside each feature of the geo collection
        const getOnEachFeaturePerPublication = (datapublication: DataPublication) =>
            (feature: Feature, layer: Layer) => {
                const popupContent = `<h5>${datapublication.title}</h5>`;
                layer.bindPopup(popupContent);
            };

        geoList.forEach(geoElement => {
            const features = geoElement.geojson;
            for (const feature of features.features) {
                L.geoJSON(feature, {
                    onEachFeature: getOnEachFeaturePerPublication(geoElement["data_publication"])
                }).addTo(this.markers);
            }
        });

        this.map.addLayer(this.markers);
    }

    resetMapView() {
        this.map.setView([51.505, -0.09], 4);
    }
    async mouseEventHandling() {
        let rectangle: Rectangle | null = null;
        let startPoint: LatLng;
        let drawing = false;

        this.map.getContainer().addEventListener("contextmenu", (e: MouseEvent) => {
            if (e.shiftKey) {
                // TODO fix this. it doesn't see to work
                e.preventDefault(); // Only prevent default if Shift is held
            }
        });
        this.map.on("mousedown", async (e: any) => {
            // This is about the browser
            const { shiftKey, button } = e.originalEvent;
            // This is about the leaflet event
            const latlng = e.latlng;

            // Only proceed if Shift is held
            if (!shiftKey) return;

            // If the click is on the right button,
            // reset the map and remove layers.
            if (button === 2) {
                if (rectangle) {
                    this.map.removeLayer(rectangle);
                    rectangle = null;
                }
                if (this.markers) {
                    this.markers.clearLayers();
                }
                this.resetMapView();
                return;
            }

            // If the click is on the middle button,
            // then do nothing
            if (button !== 0) return;


            drawing = true;
            startPoint = latlng;

            // If a rectangle already existed,
            // clear the layers, and start again
            if (rectangle) {
                this.map.removeLayer(rectangle);
                this.map.removeLayer(this.markers)
                rectangle = null;
            }

            this.map.dragging.disable();

            const onMouseMove = (ev: LeafletMouseEvent) => {
                if (rectangle) this.map.removeLayer(rectangle);

                const bounds = L.latLngBounds(startPoint, ev.latlng);
                rectangle = L.rectangle(bounds, { color: "red" }).addTo(this.map);
            };

            const onMouseUp = async (ev: LeafletMouseEvent) => {
                if (!drawing) return;
                drawing = false;

                this.map.off("mousemove", onMouseMove);
                this.map.off("mouseup", onMouseUp);
                this.map.dragging.enable();

                const bounds = L.latLngBounds(startPoint, ev.latlng);

                const sw = bounds.getSouthWest();
                const ne = bounds.getNorthEast();
                const boundingBox = JSON.stringify([
                    sw.lng,
                    sw.lat,
                    ne.lng,
                    ne.lat
                ]);

                // Clear markers
                this.markers.clearLayers();

                const geo = await this.getResponse(boundingBox);
                await this.getAndDrawResponse(geo);
            };

            this.map.on("mousemove", onMouseMove);
            this.map.on("mouseup", onMouseUp);
        });
    }
}





const app = new MapApp();
app.init();