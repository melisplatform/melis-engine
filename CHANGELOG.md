# Changelog

## v6.0.3 - 2026-09-23
### Security
* **security:** parameterised SQL for date filters, ORDER BY and hand-quoted values (audit item 11.0)

## v6.0.2 - 2026-08-20
### Docs
* **melisai:** React back-office AI documentation for MelisEngine

## v6.0.1 - 2026-08-10
### Dependencies & build
* **composer:** update docs/homepage links, swap zf2 keyword for laminas, bump php constraint to ^8.3|^8.5

## v6.0.0 - 2026-08-10
### Security
* **security:** add SECURITY.md (private vulnerability reporting policy)
### Added
* **webservices:** expose read-only services + i18n descriptions; fix(i18n): follow session locale instead of always en_EN
* **melis-engine:** add developer code examples to the technical reference
* Add MelisAI module documentation for AI consumption
* Add explicit nullable param types (PHP 8.4 deprecation)
### Fixed
* **security:** harden legacy file/dir creation & output escaping
* Fix(webservices) + redesign microservice API pages (ticket 0010821)
* **melis-engine:** restore prominent §0 'the trio' section at the top
### Changed
* Page menu type url
### Dependencies & build
* **composer:** bump melis-core/melis-front constraint to ^6.0
### Docs
* **melisengine:** rewrite as two-part doc (functional guide + technical reference)

## v5.3.5 - 2025-07-15
### Changed
* 101 updates

## v5.3.4 - 2025-07-09
### Added
* Added option to remove dynamic dnd
### Fixed
* Fixed dnd warnings
* Fixed undefined melisDragDropZOne
* Fixed problem creating dnd on savedpage
### Changed
* Update MelisEngineSendMailService.php

## v5.3.3 - 2025-02-14
### Changed
* Render viewmodel using viewrenderer srv

## v5.3.2 - 2024-12-02
### Added
* Add db key in the event to fetch in the db
* Added events to modify plugin parameters
* Added listener to update/get plugin parameters
* Added listener to override/update plugin parameters

## v5.3.1 - 2024-10-23
### Added
* Added flagged_template table

## v5.3.0 - 2024-09-25
* Maintenance release.

## v5.2.0 - 2024-06-06
### Dependencies & build
* Readded front as dependency

## v5.1.2 - 2024-02-21
### Added
* Updated composer depedencies
### Dependencies & build
* Forking laminas cache to update its dependencies

## v5.1.1 - 2024-02-13
### Changed
* Removed memcached

## v5.1.0 - 2024-02-13
### Added
* Added memory cache
### Fixed
* Fixed cache problem
* Fixed problem requiring storage-cache-implementation
* Fixed problem deprecated dynamic property
### Changed
* Remove psr
* Set alias to psr/cache
* Str_replace error
* Update cache config structure
* Change version
* Change minimum stability
* Temp update front req version
### Dependencies & build
* Memcached in composer.json

## v5.0.1 - 2023-05-24
### Added
* Added script to update tables to utf8mb4
### Changed
* Renamed file
* Commented sql

## v5.0.0 - 2022-06-22
### Fixed
* Resolve deprecation
* Fixed problem on getting data after sending event
### Changed
* Changed deprecated ArraySerializable to ArraySerializableHydrator
* Used ObjectPropertyHydrator instead of deprecated ObjectProperty
