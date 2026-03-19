import { LatLng, Rectangle, Map, MarkerClusterGroup, Layer, Path } from "leaflet";
import type { LeafletMouseEvent, CircleMarkerOptions, LeafletEvent, LeafletEventHandlerFn } from 'leaflet';
import type { Feature } from 'geojson'
import type { GeoFeature, InclusiveExclusiveGeoJsonDataPublications } from "../types/datapublication.js";
import { sideBar } from './sidebar.js'
import type { Sidebar } from "../types/sidebar.js";
import { DEFAULT_CIRCLE_MARKER_OPTIONS, DEFAULT_MARKER_OPTIONS, HIGHLIGHT_MARKER_OPTIONS } from "./markerStyling.js";
import { assertNotNull } from "../helpers.js";
import type { ResultSet, ResultSetMapping } from "../types/map.js";
import { EXCLUSIVE, INCLUSIVE } from "../types/map.js";
import { getResultSetMappingObj, TAB_CONFIG, type Entries } from "./utils.js";
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
type ElementLayer = Layer & {
    getElement?: () => Element | undefined
}
type MarkerClusterGroupWithVisibleParent = MarkerClusterGroup & {
    getVisibleParent(layer: Layer): Layer | undefined
}

class DataPublicationMap {
    map: Map;
    markers: MarkerMapping;
    sideBar: Sidebar;
    groupedMarkers: GroupedLayerMapping = getResultSetMappingObj<GroupedLayer>(() => { return {} })
    defaultOptions = DEFAULT_MARKER_OPTIONS
    circleMarkerDefaultOptions: CircleMarkerOptions = DEFAULT_CIRCLE_MARKER_OPTIONS
    highlightedOptions = HIGHLIGHT_MARKER_OPTIONS
    popupOptions = DEFAULT_POPUP_OPTIONS


    constructor() {
        this.map = L.map('map')
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
            attribution: '&copy; OpenStreetMap'
        }).addTo(this.map);
        this.resetMapView()
        return;
    }

    private setMarkersStyle(
        { doi, resultSet, highlightOrReset }:
            { doi: string, resultSet: ResultSet, highlightOrReset: 'highlight' | 'reset' }) {

        const geoFeatures = this.groupedMarkers[resultSet][doi]
        assertNotNull(geoFeatures, `Geofeatures should be populated for a datapublication with doi '${doi}'. This is a bug.`)
        
        const targets = this.getHighlightTargets(geoFeatures, resultSet, doi)
        targets.forEach(element => {
            element.classList.toggle(this.highlightedOptions.className, (highlightOrReset === 'highlight'));
        })
    }
    private getHighlightTargets(geoFeatures: Layer[], resultSet: ResultSet, doi: string): Element[] {
        const targets = new globalThis.Map<string, Element>()

        geoFeatures.forEach(geoFeature => {
            assertIsPath(geoFeature)
            const visibleLayer = this.getVisibleHighlightLayer(geoFeature, resultSet)
            const element = this.getLayerElement(visibleLayer)
            assertIsPathElement(
                element,
                doi
            );
            targets.set(String(L.stamp(visibleLayer)), element)
        })

        return Array.from(targets.values())
    }

    private getVisibleHighlightLayer(layer: Layer, resultSet: ResultSet): Layer {
        const markerGroup = this.markers[resultSet] as MarkerClusterGroupWithVisibleParent
        return markerGroup.getVisibleParent(layer) ?? layer
    }

    private getLayerElement(layer: Layer): Element | undefined {
        return (layer as ElementLayer).getElement?.()
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
            const geoFeaturesForDoi: Layer[] | undefined =
                this.groupedMarkers[resultSet][geoFeatureWithInfo.data_publication_doi]

            this.groupedMarkers[resultSet][geoFeatureWithInfo.data_publication_doi] =
                geoFeaturesForDoi ? [...geoFeaturesForDoi, layer] : [layer]


            // When hover over a geo feature
            layer.on("mouseover", () => {
                this.setMarkersStyle({
                    doi: geoFeatureWithInfo.data_publication_doi,
                    resultSet: resultSet,
                    highlightOrReset: 'highlight'
                })
                this.sideBar.highlight(geoFeatureWithInfo.data_publication_doi)
            });
            layer.on("mouseout", () => {
                this.setMarkersStyle({
                    doi: geoFeatureWithInfo.data_publication_doi,
                    resultSet: resultSet,
                    highlightOrReset: 'reset'
                })
                this.sideBar.removeHighlight(geoFeatureWithInfo.data_publication_doi)
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

                this.removeLayers();
                this.resetMapView();
                this.sideBar.resetList()
                return;
            }

            // If the click is on the middle button,
            // then do nothing
            if (button !== 0) return;


            // If the click is on the left button,

            drawing = true;
            startPoint = latlng;

            // If a rectangle already existed,
            // clear the layers, and start again
            if (rectangle) {
                this.map.removeLayer(rectangle);
                rectangle = null;
                this.removeLayers()
                this.sideBar.resetList()
            }
            this.map.dragging.disable();

            const onMouseMove = (ev: LeafletMouseEvent) => {
                // We need the line below, because, as the user draws,
                // they create a lot of small rectangles
                // from which we want to keep only the last one.
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
                rectangle = L.rectangle(bounds, { className: "bbox-selection", interactive: false, pane: 'bboxPane' }).addTo(this.map);
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

                this.addFeaturesAndSidebarInMap(boundingBox)

            };


            this.map.on("mousemove", onMouseMove);
            this.map.on("mouseup", onMouseUp);


        });
        
    }

    private removeLayers() {
        Object.values(this.markers).forEach((layer) => {
            layer.clearLayers()
            this.map.removeLayer(layer)
        })
        this.resetGroupedMarkers()
    }
    private async addFeaturesAndSidebarInMap(boundingBox: string) {

        const geo = await this.getJsonFromRequest(boundingBox);
        await this.drawResponse(geo);
        this.sideBar.populate(geo);

    }
    private resetGroupedMarkers() {
        this.groupedMarkers = getResultSetMappingObj<GroupedLayer>(() => ({}));
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