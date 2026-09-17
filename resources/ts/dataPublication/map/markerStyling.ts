import type { CircleMarkerOptions, PathOptions } from "leaflet";

function getCssVar(varName: string): string {
    return getComputedStyle(document.documentElement)
        .getPropertyValue(varName)
        .trim();
}

export const DEFAULT_MARKER_OPTIONS: PathOptions = {
    stroke: true,
    color: getCssVar("--color-primary-500"),
    fillColor: getCssVar("--color-primary-500"),
    weight: 2,
    fillOpacity: 0.2,
};
export const DEFAULT_CIRCLE_MARKER_OPTIONS: CircleMarkerOptions = {
    radius: 10,
    ...DEFAULT_MARKER_OPTIONS,
};
export const HIGHLIGHT_MARKER_OPTIONS: PathOptions = {
    // We need to remove the stroke, because
    // if we don't, the click listeners pick up events right on the stroke,
    // where coordinates are outside of our layer.
    // This can lead to having no information for our popup,
    // even though the user clicked a layer correctly!
    stroke: false,
    color: getCssVar("--color-secondary-800"),
    fillColor: getCssVar("--color-secondary-200"),
    fillOpacity: 0.4,
};
