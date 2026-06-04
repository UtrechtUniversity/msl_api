import { EXCLUSIVE, INCLUSIVE, type ResultSetMapping } from "../types/map.js";

export function getResultSetMappingObj<T>(factory: () => T): ResultSetMapping<T> {
    return { [EXCLUSIVE]: factory(), [INCLUSIVE]: factory() }
}


export const TAB_CONFIG =
    {
        [EXCLUSIVE]: { label: 'Overlapping', active: true, buttonId: 'overlapping-filter-btn' },
        [INCLUSIVE]: { label: 'Inside', active: false, buttonId: 'inside-filter-btn' }
    } as const

export type Entries<T> = Array<
    {
        [K in keyof T]: [K, T[K]]
    }[keyof T]>

export const LAT_LONG_RANGE = { MAX: { LAT: 90, LONG: 180 }, MIN: { LAT: -90, LONG: -180 } } as const

export function assertSingleArray<T>(arr: ArrayLike<T>, message: string): asserts arr is ArrayLike<T> & { 0: T; length: 1 } {
    if (arr.length !== 1) {
        throw new Error(message);
    }
}


export function throwWhenCallBackNotInitialized() {
    throw new Error('Initialization of a callback did not happen. This is a bug.')
}