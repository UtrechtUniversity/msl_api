import type { CircleMarkerOptions } from "leaflet"

type MarkerOptions = {
    className: string
}

// Base style options for all non-point regions drawn on the map.
// The visual appearance is defined in CSS by the `.map-region` class.
export const DEFAULT_MARKER_OPTIONS: MarkerOptions = {
    className: "map-region",
}

// Base style options for point regions rendered as circle markers.
// Reuses the same CSS class as polygons/lines and sets the default radius.
export const DEFAULT_CIRCLE_MARKER_OPTIONS: CircleMarkerOptions = {
    radius: 10,
    className: "map-region",
}

// Style options used when a region is highlighted on hover.
// The visual appearance is defined in CSS by the `.map-region-highlight` class.
export const HIGHLIGHT_MARKER_OPTIONS: MarkerOptions = {
    className: "map-region-highlight",
}