# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

## [1.7.3] - 2025-09-25
- bugfix: resolve issue with gfz data services logo change
- bugfix: prevent possible error in new survey section
- bugfix: prevent error when msl_publisher is missing on detail files pages
- bugfix: remove e-mail validators used in survey section depending on not present PHP extensions

## [1.7.2] - 2025-09-24
- bugfix: resolve issue with commented code in surveys section

## [1.7.1] - 2025-09-22
- bugfix: resolve composer issues

## [1.7.0] - 2025-09-22
- Rework of vocabulary management
- Add survey system used in new data tooling - surveys section

## [1.6.0] - 2025-06-26
- Add base organization used by CKAN to organization seeder to ease initial setup
- Add vocabulary display names to database, seeding, vocabulary api and exports
- Add new tag field to datapublication schema to work with matched vocabulary terms
- Add child uri to enriched keywords for displaying connections in frontend 
- Add new lab data management to msl_api;
  - Setup facility/equipment and origanization models as setup in FAST
  - Create update function to synch data from fast to msl_api
  - Populate facility organization data using ROR identifier as set in FAST
  - Add function to ass keywords to facilities based on vocabularies and facility description/title
  - Add option to serialise facility data to RDF/turtle for data exange with EPOS
- Frontend of data catalogue moved to msl_api replacing CKAN frontend. Large set of improvements included in this change

## [1.5.2] - 2024-03-04

- Bugfix: resolve issue in API response objects

## [1.5.1] - 2024-02-28

- Bugfix: fix keyword processing in API

## [1.5.0] - 2024-02-24

- Add (CKAN)seeding functions from admin interface. Create/Update organizations described in JSON file using queue system
- Only send lowest level vocabulary terms to CKAN for originally assigned keyword fields/facets.
- BGS data harvesting. Initial setup for reviewing.
- Rework of keyword structure. Vocabulary specific keywords are repalced with generic original and enriched fields within 
CKAN data publication schema. Data models changed in harvesting backend, API and tree exports. 
- Update BaseDateset to reflect schema changes in CKAN
- Add API endpoint to retrieve keyword information by uri
- Add text annotation for title and notes/description fields to keyword processor, mappers updated to use this function
- Add match source information to (enriched)keyword information
- Add exports published exports of vocabulary versions 1.1
- Add and publish vocabulary versions 1.2
- Include Nerc/BGS harvesting based on Datacite queries
- Include second GFZ importer based on Datacite queries
- Add Magic harvesting based on fixed list scraped from website
- Add results from Datacite queries to Yoda importer
- Add results from Datacite queries to Csic importer
- Add results from Datacite queries to 4TU importer
- Add function to export dois per organization to admin panel

## [1.4.0] - 2023-03-29

- Update 4TU import to not use specific version doi references
- Add parameter to APIs to exclude results without downloadlinks
- Add new version of vocabularies and change code to work with specific versions
- Exclude parts of vocabs from sub-domain matching

## [1.3.2] - 2023-03-08

- Adjust migrationscript to work with partial database update

## [1.3.1] - 2023-03-08

- Bugfix: remove databasename from query

## [1.3.0] - 2023-03-08

- Geochemistry vocab now uses two top levels
- Update microscopy vocab
- URI generation for vocabularies and specific terms
- Specific vocabulary versions
- Several export formats for vocabularies: xlsx, json, ttl and and xml (linked data)
- Keyword mapping no longer removes matched keywords from tag_string field
- Change sorting of specific nodes in filter tree export used by frontend
- Include 4TU importer/harvesting
- Replace FTP based download harvester for GFZ with web crawling method
- Add seperate keyword section in data structure to indicate original and interpreted keywords
- Split JSON tree export for use in frontend into interpreted and original types
- Enlarge databasefields to store larger response objects
- Add turtle file and mockup webservice for EPOS TNA pitch

## [1.2.0] - 2022-11-11

- API documentation in API.md
- Add changelog in CHANGELOG.md
- Update GFZ and yoda importers to work with new data-publication schema
- Update API responses to work with new data-publication schema
- Csic data importing
- Implement 4 new vocabularies: geological age, geological setting, paleomagnetism and geochemistry
- Add geological setting to researchaspects part of each API endpoint
- Add functions to view and export unmatched keyword terms
- Add functions to view and export matching of keywords in abstract and title
- Add keyword mapping based on free text to keyword helpers used by importers
- Update all importers to use new keyword mapping features
- Update all vocabularies
- RDF/Turtle file used by EPOS ICS-C integration implemented within repo

## [1.1.0] - 2022-05-24

- First release