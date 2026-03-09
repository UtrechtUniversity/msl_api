import { EXCLUSIVE, INCLUSIVE, type ResultSetMapping } from "../types/map.js";

export function getResultSetMappingObj<T>(factory: () => T): ResultSetMapping<T> {
    return { [EXCLUSIVE]: factory(), [INCLUSIVE]: factory() }
}


export const TAB_CONFIG =
    {
        [EXCLUSIVE]: { label: 'Overlapping with filter', active: true },
        [INCLUSIVE]: { label: 'Inside filter', active: false }
    } as const

export type Entries<T> = Array<
    {
        [K in keyof T]: [K, T[K]]
    }[keyof T]>

export const LAT_LONG_RANGE = { MAX: { LAT: 90, LONG: 180 }, MIN: { LAT: -90, LONG: -180 } } as const