import { EXCLUSIVE, INCLUSIVE, type MappingOnTabs } from "../types/map.js";

export function getMappingOnTabsObj<T>(t: T): MappingOnTabs<T> {
    return { [EXCLUSIVE]: t, [INCLUSIVE]: t }
}


export const TAB_CONFIG =
    {
        [EXCLUSIVE]: { label: 'Exclusive results', active: true },
        [INCLUSIVE]: { label: 'Inclusive results', active: false }
    } as const