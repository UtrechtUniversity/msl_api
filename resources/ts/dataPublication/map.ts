import { Map, Layer, Path } from "leaflet";
import { LAT_LONG_RANGE } from "./utils.js";

// If we dont assign L, typescript is complaining about using a UMD global in a module.
const L = window.L;

const southWest = L.latLng(LAT_LONG_RANGE.MIN.LAT, LAT_LONG_RANGE.MIN.LONG)
const northEast = L.latLng(LAT_LONG_RANGE.MAX.LAT, LAT_LONG_RANGE.MAX.LONG)

class DataPublicationMap {
    map: Map;
    maxBounds = L.latLngBounds(southWest, northEast);

    constructor() {
        this.map = L.map('map', {
            maxBounds: this.maxBounds, maxBoundsViscosity: 1
        })

        this.drawMap();
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

    private resetMapView() {
        this.map.setView([51.505, -0.09], 4);
    }

}





const app = new DataPublicationMap();


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