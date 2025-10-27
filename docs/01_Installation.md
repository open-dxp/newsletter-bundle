# Installation

#### Minimum Requirements
* open-dxp/opendxp:^1.0

## Installation Process

Install bundle via composer:
```bash
composer require open-dxp/newsletter-bundle
```

Enable bundle in `config/bundles.php`:
```php
return [
    ...
    OpenDxp\Bundle\NewsletterBundle\OpenDxpNewsletterBundle::class => ['all' => true],
    ...
];
```

Install bundle via console:
```bash
php bin/console opendxp:bundle:install OpenDxpNewsletterBundle
```

Check if the bundle has been installed:
```bash
php bin/console opendxp:bundle:list
+---------------------------------+---------+-----------+----+-----+-----+
| Bundle                          | Enabled | Installed | I? | UI? | UP? |
+---------------------------------+---------+-----------+----+-----+-----+
| OpenDxpNewsletterBundle         | ✔      | ✔       | ❌  | ✔  | ❌   |
+---------------------------------+---------+-----------+----+-----+-----+
```


#### Config options

```yaml
#### SYMFONY MAILER TRANSPORTS
framework:
    mailer:
        enabled: true
        transports:
            opendxp_newsletter: smtp://user:pass@smtp.example.com:port
    messenger:
        routing:
            'OpenDxp\Bundle\NewsletterBundle\Messenger\SendNewsletterMessage': opendxp_core
```

```yaml
opendxp_newsletter:
    source_adapters:
        defaultAdapter: opendxp_newsletter.document.newsletter.factory.default
        csvList: opendxp_newsletter.document.newsletter.factory.csv
    sender:
        name: 'Han Solo'
        email: 'han.solo@opendxp.com'
    return:
        name: 'Luke Skywalker'
        email: 'luke.skywalker@opendxp.com'
    debug:
        email_addresses: 'han.solo@opendxp.com,luke.skywalker@opendxp.com'
    use_specific: true
    default_url_prefix: 'https://my-host.com'
```


## Uninstallation
Uninstalling the bundle does not clean up `newsletter` documents only the predefined document types. Before uninstalling make sure to remove or archive all dependent documents.
You can also use the following command to clean up you database. Create a backup before executing the command. All data will be lost.

```bash
 bin/console opendxp:document:cleanup newsletter
```


## Best Practice and Example

See [Newsletter Config](./19_Newsletter_Config.md) for a complete example and how to set up your newsletter.

## Document Types
This bundle introduces a new document type:

| Type                                           | Description                                   |
|------------------------------------------------|-----------------------------------------------|
| [Newsletter](./05_Newsletter_Documents.md) | Like an email but specialized for newsletter |

## OpenDxp Twig Extensions
This bundle also adds a new twig extension. For more information checkout the main documentation

| Test                      | Description                                                                      |
|---------------------------|----------------------------------------------------------------------------------|
| `opendxp_document_newsletter`          | Checks if object is instanceof Newsletter                  |
