
export function assertNotNull<T>(
    value: T | null | undefined,
    message: string
): asserts value is T {
    if (value == null) {
        throw new Error(message);
    }
}

export const COORDINATE_BOUNDARIES = {
    MIN_LAT: -90,
    MAX_LAT: 90,
    MIN_LNG: -180,
    MAX_LNG: 180
} as const