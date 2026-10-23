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