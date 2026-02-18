export const EXCLUSIVE = 'exclusive'
export type Exclusive = typeof EXCLUSIVE

export const INCLUSIVE = 'inclusive'
export type Inclusive = typeof INCLUSIVE

export type InclusiveOrExclusive = Exclusive | Inclusive

export const TAB_CONFIG =
    {
        [EXCLUSIVE]: { label: 'Exclusive results', active: true },
        [INCLUSIVE]: { label: 'Inclusive results', active: false }
    } as const


export type MappingOnTabs<T> = {
    [EXCLUSIVE]: T,
    [INCLUSIVE]: T
}