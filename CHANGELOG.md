# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.4.0] - 2022-11-15
### Added
- Add missing properties to RefundRequest
- Add missing fields to PaymentResponse model
- Add method for activate-invoice endpoint
### Fixed
- Fix function parameter types

## [2.3.0] - 2022-09-05
### Added
- Payment report endpoint added
- companyName added to Customer model
### Fixed
- Fixed cUrlClient query parameters

## [2.2.0] - 2022-06-15
### Added
- Added PHPUnit test workflow
- Added cUrl fallback option for Guzzle
### Fixed
- Fixed phone input validation in the example code

## [2.1.2] - 2022-06-07
### Fixed
- Fixed street address validation variable

## [2.1.1] - 2022-06-06
### Fixed
- Fixed street address validation in case of null

## [2.1.0] - 2022-06-02
### Added
- Added settlements endpoint
- Added a validation exception if street address is longer then 50 characters
### Fixed
- Fixed exception handling on request timeout

## [2.0] - 2022-05-20
### Added
- Added support for Guzzle 7
- Added signature validation to examples
### Changed
- Removed bundled Guzzle 6 library
- Guzzle version (6 or 7) is selected automatically depending on the environment
- Payment timestamp accuracy increased in examples

## [1.1.0] - 2022-03-23
### Added
- Added native return type to jsonSerialize()
### Changed
- Allow payment request to be made without providing any items
- Validation rules updated to check if a negative value has been provided for an item

## [1.0.1] - 2021-10-29
### Changed
-  setDeliveryDate is now optional

## [1.0] - 2021-10-06
### Added
-  All initial plugin functionalities
