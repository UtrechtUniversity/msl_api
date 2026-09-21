export function assertNotNull<T>(
    value: T | null,
    message: string,
): asserts value is T {
    if (value === null) {
        throw new Error(message);
    }
}

export function assertNotUndefined<T>(
    value: T | undefined,
    message: string,
): asserts value is T {
    if (value === undefined) {
        throw new Error(message);
    }
}

export function getElementOrThrow(elementId: string) {
    const element = document.getElementById(elementId);
    assertNotNull(
        element,
        ` Element '${elementId}'could not be found. This is a bug.`,
    );
    return element;
}
