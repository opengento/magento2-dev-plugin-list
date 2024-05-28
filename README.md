# Module for Magento 2

[![Latest Stable Version](https://img.shields.io/packagist/v/opengento/magento2-dev-plugin-list.svg?style=flat-square)](https://packagist.org/packages/opengento/magento2-dev-plugin-list)
[![License: MIT](https://img.shields.io/github/license/opengento/magento2-dev-plugin-list.svg?style=flat-square)](./LICENSE) 
[![Packagist](https://img.shields.io/packagist/dt/opengento/magento2-dev-plugin-list.svg?style=flat-square)](https://packagist.org/packages/opengento/magento2-dev-plugin-list/stats)
[![Packagist](https://img.shields.io/packagist/dm/opengento/magento2-dev-plugin-list.svg?style=flat-square)](https://packagist.org/packages/opengento/magento2-dev-plugin-list/stats)

This module add a BO entry for viewing all the plugins installed in the Magento 2 instance and their load order when triggered.

 - [Setup](#setup)
   - [Composer installation](#composer-installation)
   - [Setup the module](#setup-the-module)
 - [Features](#features)
 - [Settings](#settings)
 - [Documentation](#documentation)
 - [Support](#support)
 - [Authors](#authors)
 - [License](#license)

## Setup

Magento 2 Open Source or Commerce edition is required.

###  Composer installation

Run the following composer command:

```
composer require opengento/module-dev-plugin-list
```

### Setup the module

Run the following magento command:

```
bin/magento setup:upgrade
```

**If you are in production mode, do not forget to recompile and redeploy the static resources.**

## Features

v0.0.1: Initial push. List all the plugins installed in the Magento 2 instance and their load order when triggered.

## Settings

There is no configuration at this time.

## Documentation

Go to BO > Dev Plugins List.

## Support

Raise a new [request](https://github.com/opengento/magento2-dev-plugin-list/issues) to the issue tracker.

## Authors

- **Opengento Community** - *Lead* - [![Twitter Follow](https://img.shields.io/twitter/follow/opengento.svg?style=social)](https://twitter.com/opengento)
- **Contributors** - *Contributor* - [![GitHub contributors](https://img.shields.io/github/contributors/opengento/magento2-dev-plugin-list.svg?style=flat-square)](https://github.com/opengento/magento2-dev-plugin-list/graphs/contributors)

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) details.

***That's all folks!***
