import type { Entries } from "../../types/entries";
import {
    INSIDE,
    OVERLAPPING,
    type GeoFeatureResultSet,
} from "../../types/map/resultSet";
import { EXCLUSIVE_ICON, INCLUSIVE_ICON } from "./icons";

export const RESULT_SET_CONFIG = {
    [OVERLAPPING]: {
        label: OVERLAPPING,
        icon: EXCLUSIVE_ICON,
        active: true,
    },
    [INSIDE]: {
        label: INSIDE,
        icon: INCLUSIVE_ICON,
        active: false,
    },
} as const;

export function getDefaultTab(): GeoFeatureResultSet {
    for (const [_, resultSetInfo] of Object.entries(
        RESULT_SET_CONFIG,
    ) as Entries<typeof RESULT_SET_CONFIG>) {
        if (resultSetInfo.active) {
            return resultSetInfo.label;
        }
    }
    throw new Error("No default active result set in config. This is a bug.");
}

export const LAT_LONG_RANGE = {
    MAX: { LAT: 90, LONG: 180 },
    MIN: { LAT: -90, LONG: -180 },
} as const;
