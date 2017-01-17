# Omnipay: Verifone

**Verifone driver for the Omnipay PHP payment processing library**

NOTE: I started writing this driver, and then the project that I was doing it for was abandoned, so it is not finished (it's about 90% done, though). I am leaving it here in case anyone wants to fork it and complete it. I'm happy for someone else to take ownership of it.

[![Build Status](https://travis-ci.org/digitickets/omnipay-verifone-ocius.png?branch=master)](https://travis-ci.org/digitickets/omnipay-verifone-ocius)
[![Latest Stable Version](https://poser.pugx.org/digitickets/omnipay-verifone-ocius/version.png)](https://packagist.org/packages/omnipay/verifone)
[![Total Downloads](https://poser.pugx.org/digitickets/omnipay-verifone-ocius/d/total.png)](https://packagist.org/packages/digitickets/omnipay-verifone-ocius)

This driver supports the remote Verifone Payment Gateway (Payware Ocius) service. Payment information is sent and received via XML messages. Customers are redirected to the card details page hosted by Verifone.

## Installation

**Important: Driver requires [PHP's Intl extension](http://php.net/manual/en/book.intl.php) to be installed.**

The Verifone Omnipay driver is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "digitickets/omnipay-verifone-ocius": "~1.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

This driver supports two transaction types:
 * Purchase (including 3D Secure support if card holder is registered).
 * Refund (you will need to send Verifone's reference from the original transaction as the 'transactionReference' parameter).

For general Omnipay usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug in this driver, please report it using the [GitHub issue tracker](https://github.com/digitickets/omnipay-verifone-ocius/issues),
or better yet, fork the library and submit a pull request.
