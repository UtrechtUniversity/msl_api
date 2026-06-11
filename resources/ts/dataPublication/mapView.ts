import type {
    LeafletMouseEvent,
    CircleMarkerOptions,
    LatLngBounds,
} from "leaflet";
import type { Feature } from "geojson";
import type {
    GeoFeature,
    GeoFeatureDataPublications,
} from "../types/datapublication";
import {
    INSIDE,
    OVERLAPPING,
    type GeoFeatureResultSet,
    type GeoFeatureResultSetMapping,
} from "../types/map";
import {
    LatLng,
    Rectangle,
    Map,
    MarkerClusterGroup,
    Layer,
    Path,
} from "leaflet";
import {
    DEFAULT_CIRCLE_MARKER_OPTIONS,
    DEFAULT_MARKER_OPTIONS,
    HIGHLIGHT_MARKER_OPTIONS,
} from "./markerStyling.js";
import { assertNotNull, assertNotUndefined } from "../helpers.js";
import {
    getGeoFeatureResultSetMappingObj,
    LAT_LONG_RANGE,
    TAB_CONFIG,
    throwWhenCallBackNotInitialized,
    type Entries,
} from "./utils.js";
import { DEFAULT_POPUP_OPTIONS } from "./popupStyling.js";

// If we dont assign L, typescript is complaining about using a UMD global in a module.
const L = window.L;

type GroupedLayer = { [groupedId: string]: Layer[] };
type GroupedLayerMapping = GeoFeatureResultSetMapping<GroupedLayer>;
type MarkerMapping = GeoFeatureResultSetMapping<MarkerClusterGroup>;
const southWest = L.latLng(LAT_LONG_RANGE.MIN.LAT, LAT_LONG_RANGE.MIN.LONG);
const northEast = L.latLng(LAT_LONG_RANGE.MAX.LAT, LAT_LONG_RANGE.MAX.LONG);

export class MapView {
    map: Map;
    // Drawing in map properties
    markers: MarkerMapping = getGeoFeatureResultSetMappingObj(() =>
        L.markerClusterGroup({
            zoomToBoundsOnClick: true,
            showCoverageOnHover: false,
        }),
    );
    groupedMarkers: GroupedLayerMapping =
        getGeoFeatureResultSetMappingObj<GroupedLayer>(() => {
            return {};
        });
    defaultOptions = DEFAULT_MARKER_OPTIONS;
    circleMarkerDefaultOptions: CircleMarkerOptions =
        DEFAULT_CIRCLE_MARKER_OPTIONS;
    highlightedOptions = HIGHLIGHT_MARKER_OPTIONS;
    popupOptions = DEFAULT_POPUP_OPTIONS;
    maxBounds = L.latLngBounds(southWest, northEast);
    drawingEnabled: boolean = false;
    rectangle: Rectangle | null = null;
    drawingBounds: null | LatLngBounds = null;
    private onFeatureHover: (doi: string) => void =
        throwWhenCallBackNotInitialized;
    private onFeatureOut: (doi: string) => void =
        throwWhenCallBackNotInitialized;
    private onCleanUp: () => void = throwWhenCallBackNotInitialized;

    constructor() {
        this.map = L.map("map", {
            maxBounds: this.maxBounds,
            maxBoundsViscosity: 1,
        });
        this.drawMap();
        this.init();
    }

    public setHandlerfn({
        onFeatureHover,
        onFeatureOut,
        onCleanUp,
    }: {
        onFeatureHover: (doi: string) => void;
        onFeatureOut: (doi: string) => void;
        onCleanUp: () => void;
    }) {
        this.onCleanUp = onCleanUp;
        this.onFeatureHover = onFeatureHover;
        this.onFeatureOut = onFeatureOut;
    }

    public async init() {
        await this.mouseEventHandling();
    }

    public setDrawingEnable(enable: boolean) {
        this.drawingEnabled = enable;
    }

    public setMarkersStyle({
        doi,
        resultSet,
        highlightOrReset,
    }: {
        doi: string;
        resultSet: GeoFeatureResultSet;
        highlightOrReset: "highlight" | "reset";
    }) {
        const geoFeatures = this.groupedMarkers[resultSet][doi];
        assertNotUndefined(
            geoFeatures,
            `Geofeatures should be populated for a datapublication with doi '${doi}'. This is a bug.`,
        );
        geoFeatures.forEach((geoFeature) => {
            assertIsPath(geoFeature);
            geoFeature.setStyle(
                highlightOrReset === "highlight"
                    ? this.highlightedOptions
                    : this.defaultOptions,
            );
        });
    }

    public async drawResponse(geoList: GeoFeatureDataPublications) {
        for (const [tabName, tabInfo] of Object.entries(TAB_CONFIG) as Entries<
            typeof TAB_CONFIG
        >) {
            this.addFeaturesInMarkers(geoList, { resultSet: tabName });
        }
    }

    private addFeaturesInMarkers(
        geoList: GeoFeatureDataPublications,
        { resultSet }: { resultSet: GeoFeatureResultSet },
    ) {
        const features = geoList.geo_features[resultSet];

        for (const feature of features) {
            L.geoJSON(feature.feature, {
                pointToLayer: this.pointToLayer,
                onEachFeature: this.getOnEachFeaturePerPublication(
                    feature,
                    resultSet,
                ),
                style: this.defaultOptions,
            }).addTo(this.markers[resultSet]);
        }
    }

    private pointToLayer = (_: Feature, latlng: LatLng) => {
        return L.circleMarker(latlng, this.circleMarkerDefaultOptions);
    };

    public removeAllLayers() {
        if (this.rectangle) {
            this.map.removeLayer(this.rectangle);
            this.rectangle = null;
            this.drawingBounds = null;
            this.removeLayers();
        }
    }

    public handleActivatedLayers(activatedTab: GeoFeatureResultSet) {
        const deactivateTab =
            activatedTab === OVERLAPPING ? INSIDE : OVERLAPPING;
        this.map.addLayer(this.markers[activatedTab]);
        this.map.removeLayer(this.markers[deactivateTab]);
    }
    public drawBoundingBox(): string {
        if (!this.drawingBounds) return "";
        return this.drawingBoundsInMap();
    }

    // We want to be able to pass information of the publication inside each feature of the geo collection
    private getOnEachFeaturePerPublication =
        (geoFeatureWithInfo: GeoFeature, resultSet: GeoFeatureResultSet) =>
        (_: Feature, layer: Layer) => {
            const popupContent = `
                <div class='${this.popupOptions.classNameContent}'>
                    <h6 class='${this.popupOptions.classNameTitle}'>${geoFeatureWithInfo.title}</h6>
                    <a href='${geoFeatureWithInfo.portalLink}' target='_blank'>
                    <button class='btn btn-primary'>View Publication</button>
                    </a>
                </div>
                `;
            layer.bindPopup(popupContent);

            // Store reference
            const doi = geoFeatureWithInfo.data_publication_doi;
            const geoFeaturesForDoi: Layer[] | undefined =
                this.groupedMarkers[resultSet][doi];

            this.groupedMarkers[resultSet][doi] = geoFeaturesForDoi
                ? [...geoFeaturesForDoi, layer]
                : [layer];

            // When hover over a geo feature
            layer.on("mouseover", () => {
                this.setMarkersStyle({
                    doi,
                    resultSet,
                    highlightOrReset: "highlight",
                });
                this.onFeatureHover ? this.onFeatureHover(doi) : null;
            });
            layer.on("mouseout", () => {
                this.setMarkersStyle({
                    doi,
                    resultSet,
                    highlightOrReset: "reset",
                });
                this.onFeatureOut ? this.onFeatureOut(doi) : null;
            });
        };

    private async mouseEventHandling() {
        let startPoint: LatLng | undefined = undefined;
        let drawing: boolean = false;

        // On pressing a button on the mouse
        this.map.on("mousedown", async (e: LeafletMouseEvent) => {
            // This is about the browser
            const { button } = e.originalEvent;
            // This is about the leaflet event
            const latlng = e.latlng;

            if (!this.drawingEnabled) return;

            // If the click is in the middle of right button,
            // then do nothing
            if (button !== 0) return;

            // If the click is on the left button:
            // If a rectangle already existed,
            // clear the layers, and start again
            if (this.rectangle) {
                this.map.removeLayer(this.rectangle);
                this.rectangle = null;
                this.removeLayers();
                this.onCleanUp ? this.onCleanUp() : null;
            }

            drawing = true;
            startPoint = this.restrictLatLng(latlng);

            this.map.dragging.disable();

            const onMouseMove = (ev: LeafletMouseEvent) => {
                assertNotUndefined(
                    startPoint,
                    "StartPoint should have a value. This is a bug.",
                );

                // We need the line below, because, as the user draws,
                // they create a lot of small rectangles
                // from which we want to keep only the last one.
                if (this.rectangle) this.map.removeLayer(this.rectangle);

                this.drawingBounds = L.latLngBounds(
                    startPoint,
                    this.restrictLatLng(ev.latlng),
                );
                // Create a new pane and add the bounding box layer there,
                // so that the bbox is drawn always on top of geo layers but below
                // pop ups
                //See https://leafletjs.com/examples/map-panes/
                const bboxPane = this.map.createPane("bboxPane");
                // > 'Looking at the defaults ( https://github.com/Leaflet/Leaflet/blob/v1.0.0/dist/leaflet.css#L87_),
                // > a value of 650 will make the TileLayer
                // > with the labels show on top of markers but below pop-ups.'
                bboxPane.style.zIndex = "650";
                this.rectangle = L.rectangle(this.drawingBounds, {
                    className: "bbox-selection",
                    interactive: false,
                    pane: "bboxPane",
                });
                this.rectangle.addTo(this.map);
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
            };

            this.map.on("mousemove", onMouseMove);
            // Use document event rather than leaflet mouse event,
            // since the later seems to go into a weird state in some cases.
            document.addEventListener("mouseup", onMouseUp);
        });
    }

    private drawingBoundsInMap() {
        assertNotNull(
            this.drawingBounds,
            "Bounds should not have been undefined. This is a bug.",
        );

        const sw = this.drawingBounds.getSouthWest();
        const ne = this.drawingBounds.getNorthEast();
        const boundingBox = JSON.stringify([sw.lng, sw.lat, ne.lng, ne.lat]);
        this.map.fitBounds(this.drawingBounds!);

        return boundingBox;
    }
    private restrictLatLng(latlng: LatLng) {
        const lat = Math.max(
            this.maxBounds.getSouth(),
            Math.min(this.maxBounds.getNorth(), latlng.lat),
        );
        const lng = Math.max(
            this.maxBounds.getWest(),
            Math.min(this.maxBounds.getEast(), latlng.lng),
        );
        return L.latLng(lat, lng);
    }

    private drawMap() {
        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: "&copy; OpenStreetMap",
            noWrap: true,
            minZoom: 2,
        }).addTo(this.map);
        this.resetMapView();
        return;
    }
    private resetMapView() {
        this.map.setView([51.505, -0.09], 4);
    }
    private removeLayers() {
        Object.values(this.markers).forEach((layer) => {
            layer.clearLayers();
            this.map.removeLayer(layer);
        });
        this.resetGroupedMarkers();
    }

    private resetGroupedMarkers() {
        this.groupedMarkers = getGeoFeatureResultSetMappingObj<GroupedLayer>(
            () => ({}),
        );
    }
}

// Path: An abstract class that contains options and constants shared between vector overlays
function assertIsPath(layer: Layer): asserts layer is Path {
    if (!(layer instanceof Path))
        throw new Error(
            `Geofeature should be instance of a path, but it is not. This is a bug.`,
        );
}
