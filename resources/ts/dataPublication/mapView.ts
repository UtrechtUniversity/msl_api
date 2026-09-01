import {
    type LeafletMouseEvent,
    type CircleMarkerOptions,
    type LatLngBounds,
    FeatureGroup,
    CircleMarker,
    Polygon,
} from "leaflet";
import type {
    FeatureWithExtraInfo,
    GeoFeatureDataPublications,
} from "../types/datapublication";
import {
    INSIDE,
    OVERLAPPING,
    type GeoFeatureResultSet,
    type GeoFeatureResultSetMapping,
} from "../types/map";
import { LatLng, Rectangle, Map, Layer, Path } from "leaflet";
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
import { PopupWithDirection } from "../popupWithDirection";

// If we dont assign L, typescript is complaining about using a UMD global in a module.
const L = window.L;

type GroupedLayer = { [groupedId: string]: Layer[] };
type GroupedLayerMapping = GeoFeatureResultSetMapping<GroupedLayer>;
type MarkerMapping = GeoFeatureResultSetMapping<FeatureGroup>;
const southWest = L.latLng(LAT_LONG_RANGE.MIN.LAT, LAT_LONG_RANGE.MIN.LONG);
const northEast = L.latLng(LAT_LONG_RANGE.MAX.LAT, LAT_LONG_RANGE.MAX.LONG);

export class MapView {
    map: Map;
    // Drawing in map properties
    markers: MarkerMapping = getGeoFeatureResultSetMappingObj(
        () => new FeatureGroup(),
    );
    /**
     * Grouped markers per doi.
     */
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

    public init() {
        this.mouseEventHandling();
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
        for (const [_, tabInfo] of Object.entries(TAB_CONFIG) as Entries<
            typeof TAB_CONFIG
        >) {
            const resultSet = tabInfo.label;
            this.addFeaturesInMarkers(geoList, { resultSet: tabInfo.label });
            this.addClickListenerPerFeatureGroup({ resultSet });
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
                onEachFeature: this.getOnEachFeaturePerPublication(resultSet),
                style: this.defaultOptions,
            }).addTo(this.markers[resultSet]);
        }
    }
    /**
     * We add a click listener in a specific feature group belonging to a result set,
     * where we:
     * 1. get the point coordinates of the click
     * 2. gather layers that overlap with this point and their metadata
     * 3. use this gathered information to create an html list
     * 4. populate the pop up and add relevant interactions
     */
    private addClickListenerPerFeatureGroup({
        resultSet,
    }: {
        resultSet: GeoFeatureResultSet;
    }) {
        this.markers[resultSet].on("click", (e) => {
            const clickedPoint = e.latlng;
            const popUpInfoPerDoi: {
                [doi: string]: { title: string; portalLink: string };
            } = {};
            const self = this;
            this.markers[resultSet].eachLayer(function (geoJson: Layer) {
                const infoFromGeoJson = getDataPublicationInfoFromGeoJson({
                    geoJson,
                    map: self.map,
                    clickedPoint,
                });
                if (!infoFromGeoJson) return;
                const { doi, portalLink, title } = infoFromGeoJson;
                // If we have already stored information, we can go on.
                if (popUpInfoPerDoi[doi]) return;
                popUpInfoPerDoi[doi] = {
                    portalLink,
                    title,
                };
            });

            const multipleOverlappingFeatures =
                Object.keys(popUpInfoPerDoi).length > 1;

            const outerDiv = document.createElement("div");
            outerDiv.classList = "list-view";

            for (const [doi, { portalLink, title }] of Object.entries(
                popUpInfoPerDoi,
            )) {
                const dataPublicationPopUpElement =
                    document.createElement("div");
                dataPublicationPopUpElement.classList =
                    this.popupOptions.classNameContent;
                dataPublicationPopUpElement.innerHTML = `   
                       <h6 class='${this.popupOptions.classNameTitle}'>${title}</h6>
                       <a href='${portalLink}' target='_blank'>
                       <button class='btn popup-btn'>View Publication</button>
                     </a>
                `;
                // We want to highlight on hover datapublications only
                // if we have a list of more than one in the popup
                if (multipleOverlappingFeatures) {
                    dataPublicationPopUpElement.addEventListener(
                        "mouseover",
                        () => {
                            dataPublicationPopUpElement.classList.add(
                                "highlight",
                            );
                            this.setMarkersStyle({
                                doi,
                                resultSet,
                                highlightOrReset: "highlight",
                            });
                            this.onFeatureHover(doi);
                        },
                    );
                    dataPublicationPopUpElement.addEventListener(
                        "mouseout",
                        () => {
                            dataPublicationPopUpElement.classList.remove(
                                "highlight",
                            );
                            this.setMarkersStyle({
                                doi,
                                resultSet,
                                highlightOrReset: "reset",
                            });
                            this.onFeatureOut(doi);
                        },
                    );
                }
                outerDiv.append(dataPublicationPopUpElement);
            }
            //
            const popup = new PopupWithDirection({
                closeButton: true,
                maxHeight: multipleOverlappingFeatures ? 200 : undefined,
            })
                .setContent(outerDiv)
                .setLatLng(clickedPoint)
                .openOn(this.map);

            this.markers[resultSet].bindPopup(popup);
            // We have to open the pop up on click, and not only bind.
            // If we don't then the pop up will open only on the second click.
            this.markers[resultSet].openPopup(clickedPoint);
        });
    }

    private pointToLayer = (_: FeatureWithExtraInfo, latlng: LatLng) => {
        return L.circleMarker(latlng, this.circleMarkerDefaultOptions);
    };

    public removeAllLayers(opts?: { except: "rectangle" }) {
        if (opts?.except !== "rectangle" && this.rectangle) {
            this.map.removeLayer(this.rectangle);
            this.rectangle = null;
            this.drawingBounds = null;
        }
        this.removeLayers();
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
        (resultSet: GeoFeatureResultSet) =>
        (feature: FeatureWithExtraInfo, layer: Layer) => {
            assertNotNull(
                feature.properties,
                `Properties of feature '${JSON.stringify(feature)}' should not be null. This is a bug.`,
            );
            // Store reference
            const doi = feature.properties.data_publication.doi;

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
                this.onFeatureHover(doi);
            });
            layer.on("mouseout", () => {
                this.setMarkersStyle({
                    doi,
                    resultSet,
                    highlightOrReset: "reset",
                });
                this.onFeatureOut(doi);
            });
        };

    private mouseEventHandling() {
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

// Inspired by https://github.com/geoman-io/leaflet-geoman/blob/develop/src/js/L.PM.Utils.js
function pxToMeterRadius({
    radiusInPx,
    map,
}: {
    radiusInPx: number;
    map: Map;
}): number {
    const center = map.project(map.getCenter());
    const point = L.point(center.x + radiusInPx, center.y);
    return map.distance(map.unproject(point), map.getCenter());
}

// Path: An abstract class that contains options and constants shared between vector overlays
function assertIsPath(layer: Layer): asserts layer is Path {
    if (!(layer instanceof Path))
        throw new Error(
            `Geofeature should be instance of a path, but it is not. This is a bug.`,
        );
}
// Helper
function getDataPublicationInfoFromGeoJson({
    geoJson,
    map,
    clickedPoint,
}: {
    geoJson: Layer;
    map: Map;
    clickedPoint: LatLng;
}): { doi: string; title: string; portalLink: string } | null {
    if (!(geoJson instanceof L.GeoJSON))
        throw new Error(
            "GeoJson should have been of correct type. This is a bug.",
        );
    const layers = geoJson.getLayers();
    // Each geoJson should have one layer with one feature.
    if (layers.length > 1)
        throw new Error("Layers of GeoJson are more than one. This is a bug.");

    const layer = layers[0];
    if (layer instanceof CircleMarker) {
        assertNotUndefined(
            layer.feature,
            `Layer should have 'feature' property defined. This is a bug.`,
        );

        const center = layer.getLatLng();
        const radius = pxToMeterRadius({
            radiusInPx: layer.getRadius(),
            map,
        });

        if (center.distanceTo(clickedPoint) <= radius) {
            const { doi, title, portalLink } =
                layer.feature.properties.data_publication;
            return {
                doi,
                title,
                portalLink,
            };
        }
        return null;
    }
    if (layer instanceof Polygon) {
        assertNotUndefined(
            layer.feature,
            `Layer should have 'feature' property defined. This is a bug.`,
        );
        const bounds = layer.getBounds();
        if (bounds.contains(clickedPoint)) {
            const { doi, title, portalLink } =
                layer.feature.properties.data_publication;
            return {
                doi,
                title,
                portalLink,
            };
        }
        return null;
    }
    throw new Error("Layer is of incorrect type. This is a bug.");
}
