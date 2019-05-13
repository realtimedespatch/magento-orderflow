<h1 align="center">Magento 1 OrderFlow Integration</h1>

A Magento extension for integrating with RealtimeDespatch's OrderFlow Software

# Installation

There are two options for installing the extension.

Note that the two approaches cannot be used interchangeably, if Composer is used to install the module all subsequent upgrades should also use Composer.

<h2>Manual</h2>

- Fetch the latest build from http://www.sixbysix.co.uk/orderflow/orderflow.tar.gz and unzip the files under the `orderflow` directory into your Magento installation.


<h2>Composer</h2>

1. Add the following composer.json file to the magento webroot 

```
{
    "repositories": [
        {
            "type": "composer",
            "url": "https://packages.firegento.com"
        }
    ],
    "require": {
        "magento-hackathon/magento-composer-installer": "*",
        "sixbysix/magento-realtime-despatch": "2.0.0",
        "ajbonner/magento-composer-autoload": "*"
    },
    "extra":{
        "magento-root-dir": "./"
    },
    "minimum-stability": "dev"
}
```

2. Execute `composer install` with the appropriate user permissions.

3. If you are installing the module on a version of Magento prior to either 1.9.3.3, or 1.14.3.3 then please enable template symlinks.

```
Advanced > Developer > Template Settings > Allow Symlinks
```

# Compatibility

<h2>Magento CE</h2>

- Magento 1.6.x
- Magento 1.7.x
- Magento 1.8.x
- Magento 1.9.x

<h2>Magento EE</h2>

- Magento 1.11.x
- Magento 1.12.x
- Magento 1.13.x
- Magento 1.14.x

<h2>PHP</h2>

- 5.x (Module Version 1.x)
- 7.x (Module Version 2.x)

# Support

The extension is provided 'as is' and is provided to Magento software houses and technical specialists who may wish to use it in its unmodified form or as a starting point for their own development. Support is available from <a href="http://www.sixbysix.co.uk/support">Six By Six</a>.

# License

The extension is released under the Open Software License v3.0 (OSL-3.0)

# Licensor

The Licensor of the extension is Realtime Despatch.

# Author

<p>The author/maintainer of the extension up to its current release (2.0.0) is <a href="http://www.sixbysix.co.uk">Six By Six</a></p>
