# HBM Messenger Bundle

## Team

### Developers
Malene Klit - malene.klit@burda.com
Christian Puchinger - christian.puchinger@burda.com

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require burdadirect/symfony-bundle-messenger
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

With Symfony 4 the bundle is enabled automatically for all environments (see `config/bundles.php`). 


### Step 3: Configuration

```yml
hbm_messenger:
    mailsPerSecond: 1

```

## Usage

### Middleware configuration

```yaml
// config/packages/messenger.yaml

framework:
    messenger:
        buses:
            messenger.bus.default:
                middleware:
                    - HBM\MessengerBundle\Messenger\Middleware\EmailThrottleMiddleware
                    - HBM\MessengerBundle\Messenger\Middleware\RestoreEntitiesMiddleware

```
