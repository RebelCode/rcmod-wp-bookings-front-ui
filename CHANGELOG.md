# Change log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [[*next-version*]] - YYYY-MM-DD

## [0.1-alpha8] - 2018-07-13
### Added
- Phing build config for building assets.

## [0.1-alpha7] - 2018-07-12
### Changed
- Using a completely new implementation of the booking wizard, built on top of the UI framework.
- Module class now delegates assets and state logic to handlers.

### Added
- Templates for wizard and components.
- Dedicated handlers for assets and logic.
- A block that provides padding between the wizard template and the application, allowing assets and scripts to be enqueued whenever it is used. Includes block factory.

## [0.1-alpha6] - 2018-06-12
### Changed
- Now using version `0.0.10` of `bookings-client`.

## [0.1-alpha5] - 2018-06-11
### Changed 
- Now using version `0.0.9` of `bookings-client`.

## [0.1-alpha4] - 2018-06-07
### Changed 
- Now using version `0.0.7` of `bookings-client`.

### Fixed
- Durations on second wizard step is correct now.
- "Add to cart" button redirects to cart page.
- Passed sessions cannot be selected in wizard.

## [0.1-alpha3] - 2018-05-24
### Changed
- Now using version `0.0.6` of `bookings-client`.

## [0.1-alpha2] - 2018-05-21
### Changed
- Now using version `0.0.5` of `bookings-client`

## [0.1-alpha1] - 2018-05-21
Initial version.
