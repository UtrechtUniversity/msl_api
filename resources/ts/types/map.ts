export const OVERLAPPING = "overlapping";
export type Overlapping = typeof OVERLAPPING;

export const INSIDE = "inside";
export type Inside = typeof INSIDE;

export type GeoFeatureResultSet = Overlapping | Inside;

export type GeoFeatureResultSetMapping<T> = {
    [OVERLAPPING]: T;
    [INSIDE]: T;
};
