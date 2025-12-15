
import { FeatureCollection, GeoJsonObject } from 'geojson'

export type GeoJsonDataPublication = {
    'geojson': FeatureCollection,
    'data_publication': DataPublication
}[]


export type DataPublication = { 'title': string }