# Lyra Base

**Lyra Base** is the core application for the ***Lyra*** software. it is sometimes used as a base for other ***Lyra*** components or
it should be used in combination with other ***Lyra*** components. The application has some basic methods which will help the developer to use different Symfony components. You can define service and parameters configurations in this package which will have the same result as of Symfony.

## Installation

Lyra Base is available in [packagist](https://packagist.org), so you should just add `rzuw/lyra-base` as dependency to your `composer.json` file:

```json
{
    "require": {
        "rzuw/lyra-base" : "*"
    }
}
```
You can also clone or fork this repository in github and work with your own implementation:
```bash
git clone ssh://git@github.com/uniwue-rz/lyra-base.git
```
Then add it as local folder in composer:
```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/path-to-git-clone"
        }
    ],
    "require": {
        "rzuw/lyra-base": "*"
    }
}
```
Or add directly from the git repository:
```json
{
    "require": {
        "rzuw/lyra-base": "*"
    },
    "repositories": [
        {
            "type": "vcs",
            "url":  "ssh://git@github.com/uniwue-rz/lyra-base.git"
        }
    ]
}
```

Then using composer:
```bash
composer update
```

If you dont want to use composer you have to write your own `autoloader` which should load every needed dependency in the package.

## Usage

## Symfony Config
**Lyra Base** has a builtin support of `symfony/config` component. It can automatically read and parse symfony configuration files so
you dont need extra configurations for the applications that have **Lyra Base**. As Every ***Lyra*** Applications should have a `name`
the configurations should be available in the following default location or should be added manually with the help of `paths`
array. The default locations are:

```bash
/etc/[name]/
/$HOME/.[name]/
. # the __DIR__
```
The configuration should be in the following format:
```conf
\S+_[env].[format]
```
`[env]` can be any string. `[format]` should be one of `xml`, `yaml` or `php`. How the configuration is written can be seen in the following examples:

### `config_prod.xml`

```xml
<?xml version="1.0" encoding="utf-8"?>
<container xmlns="http://symfony.com/schema/dic/services" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
  <parameters>
    <parameter key="lastname">smith</parameter>
  </parameters>
</container>
```

### `config_prod.yml`

```yml
parameters:
  firstname : john
```

### `config_prod.php`
```php
<?php
$container->setParameter('occupation', 'known');
```

The precedence for the configuration files are as follows from most significant to lease:
1. *.php
2. *.xml
3. *.yml 

To initiate and read the configuration:

```php
$base = new Base("newApp", array("root_dir"));
$config = $base->getConfigurationContainer();
// This compiles the configuration
$config->compile();
$config->getParameter("lastname"); // smith
$config->getParameter("firstname"); // john
$config->getParameter("occupation"); // known
```

More through Information can be found in following links:
- [How to Create Friendly Configuration for a Bundle](http://symfony.com/doc/current/bundles/configuration.html)
- [How to Load Service Configuration inside a Bundle](https://symfony.com/doc/current/bundles/extension.html)
- [Loading Resources](https://symfony.com/doc/current/components/config/resources.html)
- [Defining and Processing Configuration Values](https://symfony.com/doc/current/components/config/definition.html)
- [Introduction to Parameters](http://symfony.com/doc/current/service_container/parameters.html#parameters-in-configuration-files)

## Testing
To test the **Lyra Base** on the given machine you need to use composer to install the dependencies first.

```bash
composer install
composer update
```

then simply running:
```bash
phpunit
```
will start to test the package.