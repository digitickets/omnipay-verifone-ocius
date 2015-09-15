# Omnipay: Verifone

**Verifone driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/pedanticantic/omnipay-verifone-ocius.png?branch=master)](https://travis-ci.org/omnipay/verifone)
[![Latest Stable Version](https://poser.pugx.org/pedanticantic/omnipay-verifone-ocius/version.png)](https://packagist.org/packages/omnipay/verifone)
[![Total Downloads](https://poser.pugx.org/pedanticantic/omnipay-verifone-ocius/d/total.png)](https://packagist.org/packages/pedanticantic/omnipay-verifone-ocius)

This driver supports the remote Verifone Payment Gateway (Ocious) service. Payment information is sent and received via XML messages. Customers typically stay on the originating website with this method of integration.

## Installation

The Verifone Omnipay driver is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "pedanticantic/omnipay-verifone-ocius": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

This driver supports two transaction types:
 * Purchase (including 3D Secure support if card holder is registered)
 * Refund (you will need to send Verifone's reference from the original transaction as the 'transactionReference' parameter.)

For general Omnipay usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug in this driver, please report it using the [GitHub issue tracker](https://github.com/omnipay/verifone/issues),
or better yet, fork the library and submit a pull request.
