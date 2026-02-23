export const EXCLUSIVE = 'exclusive'
export type Exclusive = typeof EXCLUSIVE

export const INCLUSIVE = 'inclusive'
export type Inclusive = typeof INCLUSIVE

export type ResultSet = Exclusive | Inclusive



export type ResultSetMapping<T> = {
    [EXCLUSIVE]: T,
    [INCLUSIVE]: T
}
