import type { CircleMarkerOptions, PathOptions } from "leaflet"

function getCssVar(varName: string): string {
    return getComputedStyle(document.documentElement).getPropertyValue(varName).trim();
}

export const DEFAULT_MARKER_OPTIONS: PathOptions =
{
    color: getCssVar('--color-primary-500'),
    fillColor: getCssVar('--color-primary-500'),
    weight: 2,
    fillOpacity: 0.6
}
export const DEFAULT_CIRCLE_MARKER_OPTIONS: CircleMarkerOptions = {
    radius: 10,
    ...DEFAULT_MARKER_OPTIONS
}
export const HIGHLIGHT_MARKER_OPTIONS: PathOptions = {
    color: getCssVar('--color-primary-700'),
    fillColor: getCssVar('--color-primary-600'),
    weight: 4,
    fillOpacity: 0.9
}