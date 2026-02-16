import { LatLng, Rectangle, Map, MarkerClusterGroup, Layer, Path } from "leaflet";
import type { LeafletMouseEvent, CircleMarkerOptions, PathOptions, LeafletEvent, LeafletEventHandlerFn, Control } from 'leaflet';
import type { Feature } from 'geojson'
import type { DataPublication, GeoFeature, InclusiveExclusiveGeoJsonDataPublications } from "../types/datapublication.js";
import { sideBar } from './sidebar.js'
import type { Sidebar } from "../types/sidebar.js";
import { DEFAULT_CIRCLE_MARKER_OPTIONS, DEFAULT_MARKER_OPTIONS, HIGHLIGHT_MARKER_OPTIONS } from "./markerStyling.js";
import { assertNotNull } from "../helpers.js";

interface SidebarHoverEvent extends LeafletEvent {
    id: string;
    exclusiveOrInclusive: 'exclusive' | 'inclusive'
}
interface SidebarTabClickEvent extends LeafletEvent {
    id: 'exclusive' | 'inclusive'
}

// If we dont assign L, typescript is complaining about using a UMD global in a module.
const L = window.L;
type GroupedLayer = { [groupedId: string]: Layer[] }
type InclusiveExclusiveGroupedLayer = {
    'exclusive': GroupedLayer,
    'inclusive': GroupedLayer
}
class MapApp {
    map: Map;
    markers: { 'exclusive': MarkerClusterGroup, 'inclusive': MarkerClusterGroup };
    sideBar: Sidebar;
    groupedMarkers: InclusiveExclusiveGroupedLayer = {
        'exclusive': {},
        'inclusive': {}
    }
    defaultOptions = DEFAULT_MARKER_OPTIONS
    circleMarkerDefaultOptions: CircleMarkerOptions = DEFAULT_CIRCLE_MARKER_OPTIONS
    highlightedOptions: PathOptions = HIGHLIGHT_MARKER_OPTIONS
    constructor() {
        this.map = L.map('map')
        this.markers = {
            'exclusive': L.markerClusterGroup({
                zoomToBoundsOnClick: true,
                showCoverageOnHover: false
            }), 'inclusive': L.markerClusterGroup({
                zoomToBoundsOnClick: true,
                showCoverageOnHover: false
            })
        };
        this.drawMap();
        this.sideBar = new sideBar().addTo(this.map);
    }


    // Create the map in the beginning
    async init() {
        await this.mouseEventHandling();
        this.sideBarEventHandling();


    }


    private drawMap() {
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(this.map);
        this.resetMapView()
        return;
    }

    private highLightMarkersFromADataPublication(doi: string, exclusiveOrInclusive: 'inclusive' | 'exclusive') {


        const geoFeatures = this.groupedMarkers[exclusiveOrInclusive][doi]
        assertNotNull(geoFeatures, `Geofeatures should be populated for a datapublication with doi '${doi}'. This is a bug.`)
        geoFeatures.forEach(geoFeature => {
            assertIsPath(geoFeature)
            geoFeature.setStyle(this.highlightedOptions);

        })
    }

    private removeHighLightMarkersFromADataPublication(doi: string, exclusiveOrInclusive: 'inclusive' | 'exclusive') {

        const geoFeatures = this.groupedMarkers[exclusiveOrInclusive][doi]
        if (!geoFeatures) throw new Error(`Geofeatures should be populated for a datapublication with doi '${doi}'. This is a bug.`)
        geoFeatures.forEach(geoFeature => {
            assertIsPath(geoFeature)
            geoFeature.setStyle(this.defaultOptions);
        })
    }
    async getJsonFromRequest(boundingBox: string): Promise<InclusiveExclusiveGeoJsonDataPublications> {
        const parameters = { boundingBox, limit: '10' }
        const params = new URLSearchParams(parameters);

        const route = '/api/geoJsonDataPublications?' + params;

        const response: Response = await fetch(route, {
            method: "GET",
        });
        if (!response.ok) {
            throw new Error('The response failed with status: ' + response.status + ' - ' + response.statusText);
        }
        const data = (await response.json()).data

        return data;
    }

    async drawResponse(geoList: InclusiveExclusiveGeoJsonDataPublications) {

        // We want to be able to pass information of the publication inside each feature of the geo collection
        const getOnEachFeaturePerPublication = (geoFeatureWithInfo: GeoFeature, exclusiveOrInclusive: 'inclusive' | 'exclusive') =>
            (_: Feature, layer: Layer) => {
                const popupContent = `<h5>${geoFeatureWithInfo.title}</h5>`;
                layer.bindPopup(popupContent);

                // Store reference
                const geoFeaturesForDoi: Layer[] | undefined = this.groupedMarkers[exclusiveOrInclusive][geoFeatureWithInfo.data_publication_doi]
                this.groupedMarkers[exclusiveOrInclusive][geoFeatureWithInfo.data_publication_doi] = geoFeaturesForDoi ? [...geoFeaturesForDoi, layer] : [layer]
                // When hover over a geo feature
                layer.on("mouseover", () => {
                    this.highLightMarkersFromADataPublication(geoFeatureWithInfo.data_publication_doi, exclusiveOrInclusive)
                    this.sideBar.highlight(geoFeatureWithInfo.data_publication_doi)
                });
                layer.on("mouseout", () => {
                    this.removeHighLightMarkersFromADataPublication(geoFeatureWithInfo.data_publication_doi, exclusiveOrInclusive)
                    this.sideBar.removeHighlight(geoFeatureWithInfo.data_publication_doi)
                });
            };
        const pointToLayer = (_: Feature, latlng: LatLng) => {
            return L.circleMarker(latlng, this.circleMarkerDefaultOptions)
        }
        //TODO change
        const exclusiveFeatures = geoList.exclusive.geojson;

        for (const excl of exclusiveFeatures) {

            L.geoJSON(excl.feature, {
                pointToLayer,
                onEachFeature: getOnEachFeaturePerPublication(excl, 'exclusive'),
                style: this.defaultOptions
            }).addTo(this.markers['exclusive']);
        }


        const inclusiveFeatures = geoList.inclusive.geojson;

        for (const incl of inclusiveFeatures) {

            L.geoJSON(incl.feature, {
                pointToLayer,
                onEachFeature: getOnEachFeaturePerPublication(incl, 'inclusive'),
                style: this.defaultOptions
            }).addTo(this.markers['inclusive']);
        }

        //TODO
        this.map.addLayer(this.markers['exclusive']);
    }



    resetMapView() {
        this.map.setView([51.505, -0.09], 4);
    }

    sideBarEventHandling() {

        this.map.on('sidebar-hover', ((e: SidebarHoverEvent) => {
            this.highLightMarkersFromADataPublication(e.id, e.exclusiveOrInclusive);
            this.sideBar.highlight(e.id)
        }) as LeafletEventHandlerFn); // We have to cast because typing in Leaflet is incorrect. 


        this.map.on('sidebar-leave', ((e: SidebarHoverEvent) => {
            this.removeHighLightMarkersFromADataPublication(e.id, e.exclusiveOrInclusive)
            this.sideBar.removeHighlight(e.id)
        }) as LeafletEventHandlerFn); // We have to cast because typing in Leaflet is incorrect. 



        this.map.on('tab-click', ((e: SidebarTabClickEvent) => {
            if (e.id === 'exclusive') {
                this.sideBar.handleActivationOfTab('exclusive')
                this.map.removeLayer(this.markers['inclusive'])
                this.map.addLayer(this.markers['exclusive'])

                return;
            }
            this.sideBar.handleActivationOfTab('inclusive')
            this.map.removeLayer(this.markers['exclusive'])
            this.map.addLayer(this.markers['inclusive'])

        }) as LeafletEventHandlerFn);

    }
    async mouseEventHandling() {
        let rectangle: Rectangle | null = null;
        let startPoint: LatLng;
        let drawing = false;

        const container = this.map.getContainer();

        container.addEventListener(
            'contextmenu',
            (e: MouseEvent) => {
                if (!e.ctrlKey) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();
            },
        );

        // On pressing a button on the mouse
        this.map.on("mousedown", async (e: LeafletMouseEvent) => {

            // This is about the browser
            const { ctrlKey, button } = e.originalEvent;
            // This is about the leaflet event
            const latlng = e.latlng;

            // Only proceed if ctrl is held
            if (!ctrlKey) return;

            // If the click is on the right button,
            // reset the map and remove layers.
            if (button === 2) {
                if (rectangle) {
                    this.map.removeLayer(rectangle);
                    rectangle = null;
                }
                // todo are they never not populated?
                Object.values(this.markers).forEach((layer) => layer.clearLayers());

                this.resetMapView();

                this.sideBar.resetList()
                return;
            }

            // If the click is on the middle button,
            // then do nothing
            if (button !== 0) return;


            // If the click is on the left button,
            // then do nothing

            drawing = true;
            startPoint = latlng;

            // If a rectangle already existed,
            // clear the layers, and start again
            if (rectangle) {
                this.map.removeLayer(rectangle);
                Object.values(this.markers).forEach((layer) => this.map.removeLayer(layer))
                rectangle = null;
            }

            this.map.dragging.disable();

            const onMouseMove = (ev: LeafletMouseEvent) => {
                if (rectangle) this.map.removeLayer(rectangle);

                const bounds = L.latLngBounds(startPoint, ev.latlng);
                // Create a new pane and add the bounding box layer there, 
                // so that the bbox is drawn always on top of geo layers but below 
                // pop ups
                //See https://leafletjs.com/examples/map-panes/
                const bboxPane = this.map.createPane('bboxPane');
                // > 'Looking at the defaults ( https://github.com/Leaflet/Leaflet/blob/v1.0.0/dist/leaflet.css#L87_),
                // > a value of 650 will make the TileLayer
                // > with the labels show on top of markers but below pop-ups.'
                bboxPane.style.zIndex = '650';
                rectangle = L.rectangle(bounds, { color: "red", interactive: false, pane: 'bboxPane' }).addTo(this.map);
            };


            // On releasing the button of the mouse
            const onMouseUp = async (ev: LeafletMouseEvent) => {
                if (!drawing) return;
                // We stop drawing
                drawing = false;
                // Remove listeners
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
                this.map.fitBounds(bounds);
                // Clear markers
                // todo are they never not populated? 
                // todo does that mean that we delete clustering???
                Object.values(this.markers).forEach((layer) => layer.clearLayers());

                this.addFeaturesAndSidebarInMap(boundingBox)

            };


            this.map.on("mousemove", onMouseMove);
            this.map.on("mouseup", onMouseUp);


        });
    }

    private async addFeaturesAndSidebarInMap(boundingBox: string) {

        const geo = await this.getJsonFromRequest(boundingBox);
        await this.drawResponse(geo);
        this.sideBar.populate(geo);

    }
}





const app = new MapApp();
app.init();


// Path: An abstract class that contains options and constants shared between vector overlays 
function assertIsPath(layer: Layer): asserts layer is Path {
    if (!(layer instanceof Path)) throw new Error(`Geofeature should be instance of a path, but it is not. This is a bug.`);

}

