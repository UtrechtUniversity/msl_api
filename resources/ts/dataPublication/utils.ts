import {
    INSIDE,
    OVERLAPPING,
    type GeoFeatureResultSet,
    type GeoFeatureResultSetMapping,
} from "../types/map.js";

export function getGeoFeatureResultSetMappingObj<T>(
    factory: () => T,
): GeoFeatureResultSetMapping<T> {
    return { [OVERLAPPING]: factory(), [INSIDE]: factory() };
}
export const TAB_CONFIG = {
    [OVERLAPPING]: { label: "Overlapping", active: true },
    [INSIDE]: { label: "Inside", active: false },
} as const;

export type Entries<T> = Array<
    {
        [K in keyof T]: [K, T[K]];
    }[keyof T]
>;

export const LAT_LONG_RANGE = {
    MAX: { LAT: 90, LONG: 180 },
    MIN: { LAT: -90, LONG: -180 },
} as const;

export function assertSingleArray<T>(
    arr: ArrayLike<T>,
    message: string,
): asserts arr is ArrayLike<T> & { 0: T; length: 1 } {
    if (arr.length !== 1) {
        throw new Error(message);
    }
}

export function throwWhenCallBackNotInitialized() {
    throw new Error(
        "Initialization of a callback did not happen. This is a bug.",
    );
}

export function getDefaultTab(): GeoFeatureResultSet {
    for (const [tabName, tabInfo] of Object.entries(TAB_CONFIG) as Entries<
        typeof TAB_CONFIG
    >) {
        if (tabInfo.active) {
            return tabName;
        }
    }
    throw new Error("No default tab in config. This is a bug.");
}

export type Paginator = {
    resultsCount: number;
    totalCount: number;
    currentPage: number;
    lastPage: number;
};
