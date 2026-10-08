# Changelog

## 1.1.0
- [BUGFIX] The payloads and results of the handlers are no longer registered as services. A container that makes its services public failed on them
- [CHORE] Replace Codeception with Pest and `open-dxp/test-foundation`
- [CHORE] Require `open-dxp/opendxp` ^1.5

## 1.0.2
- OpenDxp / Admin-Bundle ^1.4 support added

## 1.0.1
- PHP 8.5 support added

### Migrating from `pimcore/newsletter-bundle:1.3` to `open-dxp/newsletter-bundle:1.x`
- PHP namespace changed to `OpenDXP\Bundle\NewsletterBundle`
- top-level configuration node changed to `opendxp_newsletter`
- Bundle name changed to `OpenDxpNewsletterBundle`
