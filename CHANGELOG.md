# Changelog

All notable changes to `php-attribute-reader` will be documented in this file.

## 1.2.0 - 2026-11-06

### Requirements

- PHP **8.4 or higher** is now required. Support for PHP 8.0, 8.1, 8.2, and 8.3 has been dropped under the maintainer's compatibility policy.
- Public reflection and attribute-reading behavior is unchanged. The package still has no runtime dependencies beyond PHP itself.

### What's Changed

- Upgrade the development test suite to Pest 5 (`^5.3.4`) and migrate the PHPUnit configuration schema to 13.4 in https://github.com/spatie/php-attribute-reader/pull/6.
- Align the CI matrix with PHP 8.4+, retain PHP 8.5 and 8.6 coverage from https://github.com/spatie/php-attribute-reader/pull/7, and test lowest/latest dependencies on Linux and Windows without fail-fast cancellations.
- Validate Composer configuration, platform requirements, and security advisories in every matrix job. The existing suite executes all 63 tests and 128 assertions without skips.
- Add regression coverage for converting variadic attribute arguments with `AttributeTarget::toArray()`.
- Update GitHub Actions dependencies, harden the Dependabot auto-merge actor check, and refresh README badges and Spatie-hosted ad assets.

**Full Changelog**: https://github.com/spatie/php-attribute-reader/compare/1.1.0...1.2.0

## 1.1.0 - 2026-02-23

### What's Changed

* Add `toArray()` method to `AttributeTarget` for getting all attribute properties as a keyed array in #2

**Full Changelog**: https://github.com/spatie/php-attribute-reader/compare/1.0.0...1.1.0

## 1.0.0 - 2026-02-23

**Full Changelog**: https://github.com/spatie/php-attribute-reader/compare/0.0.1...1.0.0

## 0.0.1 - 2026-02-22

### What's Changed

* Bump actions/checkout from 4 to 6 by @dependabot[bot] in https://github.com/spatie/php-attribute-reader/pull/1

### New Contributors

* @dependabot[bot] made their first contribution in https://github.com/spatie/php-attribute-reader/pull/1

**Full Changelog**: https://github.com/spatie/php-attribute-reader/commits/0.0.1
