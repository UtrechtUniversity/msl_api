import { LatLng, Rectangle, Map, MarkerClusterGroup, Layer, Path } from "leaflet";
import type { LeafletMouseEvent, CircleMarkerOptions, LeafletEvent, LeafletEventHandlerFn, LatLngBounds } from 'leaflet';
import type { Feature } from 'geojson'
import type { GeoFeature, InclusiveExclusiveGeoJsonDataPublications } from "../types/datapublication.js";
import { sideBar } from './sidebar.js'
import type { Sidebar } from "../types/sidebar.js";
import { DEFAULT_CIRCLE_MARKER_OPTIONS, DEFAULT_MARKER_OPTIONS, HIGHLIGHT_MARKER_OPTIONS } from "./markerStyling.js";
import { assertNotUndefined } from "../helpers.js";
import type { ResultSet, ResultSetMapping } from "../types/map.js";
import { EXCLUSIVE, INCLUSIVE } from "../types/map.js";
import { getResultSetMappingObj, LAT_LONG_RANGE, TAB_CONFIG, type Entries } from "./utils.js";
import { DEFAULT_POPUP_OPTIONS } from "./popupStyling.js";


interface SidebarHoverEvent extends LeafletEvent {
    id: string;
    resultSet: ResultSet
}
interface SidebarTabClickEvent extends LeafletEvent {
    id: ResultSet
}

// If we dont assign L, typescript is complaining about using a UMD global in a module.
const L = window.L;

type GroupedLayer = { [groupedId: string]: Layer[] }
type GroupedLayerMapping = ResultSetMapping<GroupedLayer>

type MarkerMapping = ResultSetMapping<MarkerClusterGroup>

const southWest = L.latLng(LAT_LONG_RANGE.MIN.LAT, LAT_LONG_RANGE.MIN.LONG)
const northEast = L.latLng(LAT_LONG_RANGE.MAX.LAT, LAT_LONG_RANGE.MAX.LONG)

class DataPublicationMap {
    map: Map;
    markers: MarkerMapping;
    sideBar: Sidebar;
    groupedMarkers: GroupedLayerMapping = getResultSetMappingObj<GroupedLayer>(() => { return {} })
    defaultOptions = DEFAULT_MARKER_OPTIONS
    circleMarkerDefaultOptions: CircleMarkerOptions = DEFAULT_CIRCLE_MARKER_OPTIONS
    highlightedOptions = HIGHLIGHT_MARKER_OPTIONS
    popupOptions = DEFAULT_POPUP_OPTIONS
    maxBounds = L.latLngBounds(southWest, northEast);


    constructor() {
        this.map = L.map('map', {
            maxBounds: this.maxBounds, maxBoundsViscosity: 1
        })

        this.markers = getResultSetMappingObj(() => L.markerClusterGroup({
            zoomToBoundsOnClick: true,
            showCoverageOnHover: false
        }));
        this.drawMap();
        this.sideBar = new sideBar().addTo(this.map);
    }


    // Create the map in the beginning
    public async init() {
        await this.mouseEventHandling();
        this.sideBarEventHandling();


    }



    private drawMap() {
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap',
            noWrap: true,
            minZoom: 2
        }).addTo(this.map);
        this.resetMapView()
        return;
    }

    private setMarkersStyle(
        { doi, resultSet, highlightOrReset }:
            { doi: string, resultSet: ResultSet, highlightOrReset: 'highlight' | 'reset' }) {

        const geoFeatures = this.groupedMarkers[resultSet][doi]
        assertNotUndefined(geoFeatures, `Geofeatures should be populated for a datapublication with doi '${doi}'. This is a bug.`)
        geoFeatures.forEach(geoFeature => {
            assertIsPath(geoFeature)
            const element = geoFeature.getElement();
            assertIsPathElement(
                element,
                doi
            );
            element.classList.toggle(this.highlightedOptions.className, (highlightOrReset === 'highlight'));

        })
    }

    private async getJsonFromRequest(boundingBox: string): Promise<InclusiveExclusiveGeoJsonDataPublications> {
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


    private async drawResponse(geoList: InclusiveExclusiveGeoJsonDataPublications) {


        for (const [tabName, tabInfo] of Object.entries(TAB_CONFIG) as Entries<typeof TAB_CONFIG>) {
            this.addFeaturesInMarkers(geoList, { resultSet: tabName })
            if (tabInfo.active) this.map.addLayer(this.markers[tabName]);

        }

    }


    private addFeaturesInMarkers(geoList: InclusiveExclusiveGeoJsonDataPublications,
        { resultSet }: { resultSet: ResultSet }) {

        const features = geoList[resultSet].geojson;

        for (const feature of features) {

            L.geoJSON(feature.feature, {
                pointToLayer: this.pointToLayer,
                onEachFeature: this.getOnEachFeaturePerPublication(feature, resultSet),
                style: this.defaultOptions
            }).addTo(this.markers[resultSet]);
        }
    }

    private pointToLayer = (_: Feature, latlng: LatLng) => {
        return L.circleMarker(latlng, this.circleMarkerDefaultOptions)
    }
    // We want to be able to pass information of the publication inside each feature of the geo collection
    private getOnEachFeaturePerPublication = (geoFeatureWithInfo: GeoFeature, resultSet: ResultSet) =>
        (_: Feature, layer: Layer) => {
            const popupContent = `
                <div class="${this.popupOptions.classNameContent}">
                    <h6 class="${this.popupOptions.classNameTitle}">${geoFeatureWithInfo.title}</h6>
                    <a href="${geoFeatureWithInfo.portalLink}" target="_blank">
                    <button class="btn btn-primary">View Publication</button>
                    </a>
                </div>
                `;
            layer.bindPopup(popupContent);

            // Store reference
            const doi = geoFeatureWithInfo.data_publication_doi
            const geoFeaturesForDoi: Layer[] | undefined = this.groupedMarkers[resultSet][doi]

            this.groupedMarkers[resultSet][doi] =
                geoFeaturesForDoi ? [...geoFeaturesForDoi, layer] : [layer]


            // When hover over a geo feature
            layer.on("mouseover", () => {
                this.setMarkersStyle({
                    doi,
                    resultSet,
                    highlightOrReset: 'highlight'
                })
                this.sideBar.highlight(doi)
            });
            layer.on("mouseout", () => {
                this.setMarkersStyle({
                    doi,
                    resultSet,
                    highlightOrReset: 'reset'
                })
                this.sideBar.removeHighlight(doi)
            });
        };

    private resetMapView() {
        this.map.setView([51.505, -0.09], 4);
    }

    private sideBarEventHandling() {

        this.map.on('sidebar-hover', ((e: SidebarHoverEvent) => {
            this.setMarkersStyle({
                doi: e.id,
                resultSet: e.resultSet,
                highlightOrReset: 'highlight'
            });
            this.sideBar.highlight(e.id)
        }) as LeafletEventHandlerFn); // We have to cast because typing in Leaflet is incorrect. 


        this.map.on('sidebar-leave', ((e: SidebarHoverEvent) => {
            this.setMarkersStyle({
                doi: e.id,
                resultSet: e.resultSet,
                highlightOrReset: 'reset'
            })
            this.sideBar.removeHighlight(e.id)
        }) as LeafletEventHandlerFn); // We have to cast because typing in Leaflet is incorrect. 



        this.map.on('tab-click', ((e: SidebarTabClickEvent) => {
            this.handleSidebarTab(e.id)
        }) as LeafletEventHandlerFn);

    }

    private handleSidebarTab(activatedTab: ResultSet) {
        const deactivateTab = (activatedTab === EXCLUSIVE) ? INCLUSIVE : EXCLUSIVE
        this.sideBar.handleActivationOfTab(activatedTab)
        this.map.addLayer(this.markers[activatedTab])
        this.map.removeLayer(this.markers[deactivateTab])
    }
    private async mouseEventHandling() {
        let rectangle: Rectangle | null = null;
        let startPoint: LatLng | undefined = undefined;
        let drawing: boolean = false;
        let drawingBounds: LatLngBounds | undefined = undefined

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
                    this.removeLayers();
                    this.resetMapView();
                    this.sideBar.resetList()
                }
                return;
            }

            // If the click is on the middle button,
            // then do nothing
            if (button !== 0) return;


            // If the click is on the left button,


            // If a rectangle already existed,
            // clear the layers, and start again
            if (rectangle) {
                this.map.removeLayer(rectangle);
                rectangle = null;
                this.removeLayers()
                this.sideBar.resetList()
            }

            drawing = true;
            startPoint = this.restrictLatLng(latlng);

            this.map.dragging.disable();



            const onMouseMove = (ev: LeafletMouseEvent) => {
                assertNotUndefined(startPoint, 'StartPoint should have a value. This is a bug.')
                // We need the line below, because, as the user draws,
                // they create a lot of small rectangles
                // from which we want to keep only the last one.
                if (rectangle) this.map.removeLayer(rectangle);

                drawingBounds = L.latLngBounds(startPoint, this.restrictLatLng(ev.latlng));
                // Create a new pane and add the bounding box layer there, 
                // so that the bbox is drawn always on top of geo layers but below 
                // pop ups
                //See https://leafletjs.com/examples/map-panes/
                const bboxPane = this.map.createPane('bboxPane');
                // > 'Looking at the defaults ( https://github.com/Leaflet/Leaflet/blob/v1.0.0/dist/leaflet.css#L87_),
                // > a value of 650 will make the TileLayer
                // > with the labels show on top of markers but below pop-ups.'
                bboxPane.style.zIndex = '650';
                rectangle = L.rectangle(drawingBounds, { className: "bbox-selection", interactive: false, pane: 'bboxPane' })
                rectangle.addTo(this.map);
            };



            // On releasing the button of the mouse
            const onMouseUp = async (e: Event) => {

                if (!drawing) return;
                // We stop drawing
                drawing = false;
                // Remove listeners
                this.map.off("mousemove", onMouseMove);
                document.removeEventListener("mouseup", onMouseUp);
                this.map.dragging.enable();
                assertNotUndefined(drawingBounds, 'Bounds should not have been undefined. This is a bug.')
                const sw = drawingBounds.getSouthWest();
                const ne = drawingBounds.getNorthEast();
                const boundingBox = JSON.stringify([
                    sw.lng,
                    sw.lat,
                    ne.lng,
                    ne.lat
                ]);
                this.map.fitBounds(drawingBounds);

                this.addFeaturesAndSidebarInMap(boundingBox)

            };


            this.map.on("mousemove", onMouseMove);
            // Use document event rather than leaflet mouse event,
            // since the later seems to go into a weird state in some cases.
            document.addEventListener("mouseup", onMouseUp);

        });
    }
    private restrictLatLng(latlng: LatLng) {
        const lat = Math.max(this.maxBounds.getSouth(), Math.min(this.maxBounds.getNorth(), latlng.lat));
        const lng = Math.max(this.maxBounds.getWest(), Math.min(this.maxBounds.getEast(), latlng.lng));
        return L.latLng(lat, lng);
    }
    private removeLayers() {
        Object.values(this.markers).forEach((layer) => {
            layer.clearLayers()
            this.map.removeLayer(layer)
        })
    }
    private async addFeaturesAndSidebarInMap(boundingBox: string) {

        const geo = await this.getJsonFromRequest(boundingBox);
        await this.drawResponse(geo);
        this.sideBar.populate(geo);

    }
}





const app = new DataPublicationMap();
app.init();


// Path: An abstract class that contains options and constants shared between vector overlays 
function assertIsPath(layer: Layer): asserts layer is Path {
    if (!(layer instanceof Path)) throw new Error(`Geofeature should be instance of a path, but it is not. This is a bug.`);

}


function assertIsPathElement(
    element: Element | undefined,
    doi: string
): asserts element is Element {
    if (!element) throw new Error(`Geofeature element for datapublication '${doi}' should not have been undefined. This is a bug.`);
}