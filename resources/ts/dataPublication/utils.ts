import { EXCLUSIVE, INCLUSIVE, type MappingOnTabs } from "../types/map.js";

export function getMappingOnTabsObj<T>(factory: () => T): MappingOnTabs<T> {
    return { [EXCLUSIVE]: factory(), [INCLUSIVE]: factory() }
}



export const TAB_CONFIG =
    {
        [EXCLUSIVE]: { label: 'Exclusive results', active: true },
        [INCLUSIVE]: { label: 'Inclusive results', active: false }
    } as const