# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.7.5] - 2025-06-13
### Added
- Validation for empty item VAT percentage
- Network token to getTokenResponse

## [2.7.4] - 2025-02-03
### Added
- Support for PHP 8.4

## [2.7.3] - 2024-07-29
### Added
- Support the new VAT in Finland

## [2.7.2] - 2024-06-06
### Changed
- Updated validation to support negative item rows

## [2.7.1] - 2024-04-16
### Added
- Run tests on PHP 8.3
### Fixed
- Fix access to possibly undefined property
- Validate too long item descriptions on client side

## [2.7.0] - 2024-03-21
### Added
- Added customProviders to createPayment() response

## [2.6.0] - 2023-10-12
### Added
- Add pay and add card endpoint
### Fixed
- Fix payment provider unit test

## [2.5.2] - 2023-05-05
### Fixed
- Improved refundRequest Validation
- Fix to allow zero amount items

## [2.5.1] - 2023-04-19
### Fixed
- Fixed requestSettlements url parameters
- Fixed item validation

## [2.5.0] - 2023-04-11
### Added
- You can now request payment report by settlement ID
- Added new improved way for requesting payment reports: requestSettlements()
### Fixed
- Fixed date validation on report requests
- Code cleanup for request classes

## [2.3.2] - 2023-02-22
### Added
- PHPStan (static analyser) added in workflow
- Tests are now run also on PHP 8.2
### Fixed
- Code quality improvements and other fixes

## [2.4.1] - 2023-01-11
- Add missing transaction property to activateInvoice request
- Use PSR-12 standard

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
