
export function assertNotNull<T>(
    value: T | null,
    message: string
): asserts value is T {
    if (value === null) {
        throw new Error(message);
    }
}



export function assertNotUndefined<T>(
    value: T | undefined,
    message: string
): asserts value is T {
    if (value === undefined) {
        throw new Error(message);
    }
}