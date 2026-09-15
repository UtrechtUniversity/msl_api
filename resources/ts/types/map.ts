export const OVERLAPPING = "overlapping";
export type Overlapping = typeof OVERLAPPING;

export const INSIDE = "inside";
export type Inside = typeof INSIDE;

export type GeoFeatureResultSet = Overlapping | Inside;

export type GeoFeatureResultSetMapping<T> = {
    [OVERLAPPING]: T;
    [INSIDE]: T;
};
export const EXCLUSIVE = "exclusive";
export type Exclusive = typeof EXCLUSIVE;

export const INCLUSIVE = "inclusive";
export type Inclusive = typeof INCLUSIVE;
