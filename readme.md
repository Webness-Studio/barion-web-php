﻿
# BarionPHP

[![Latest Stable Version](http://poser.pugx.org/bencurio/barion-web-php/v)](https://packagist.org/packages/bencurio/barion-web-php) [![Total Downloads](http://poser.pugx.org/bencurio/barion-web-php/downloads)](https://packagist.org/packages/bencurio/barion-web-php) [![Latest Unstable Version](http://poser.pugx.org/bencurio/barion-web-php/v/unstable)](https://packagist.org/packages/bencurio/barion-web-php) [![License](http://poser.pugx.org/bencurio/barion-web-php/license)](https://packagist.org/packages/bencurio/barion-web-php) [![Dependents](http://poser.pugx.org/bencurio/barion-web-php/dependents)](https://packagist.org/packages/bencurio/barion-web-php)

**BarionPHP** is a compact PHP library to manage online e-money and card payments via the *Barion Smart Gateway*.
It allows you to accept credit card and e-money payments in just a few lines of code.

<p align="center"><hr><strong>⚠⚠⚠ Breaking changes: ⚠⚠⚠</strong><hr><ul><li>Drop PHP 5.x support, minimum version PHP 7.4</li><li>Namespaces added</li><li>All enumeration and define changed: <code><a href="https://github.com/bencurio/barion-web-php/tree/master/library/Enum">library/Enum</a></code>. If you have built your code on the official version, you will need to update your integration to the appropriate namespaces.</li></ul>This fork was made to support Composer and PSR-4 (autoload). Therefore, those using the original Barion library will not be able to update without breaking, as this codebase uses namespaces and includes minor fixes. I'll try to keep track of changes to the upstream repository.<hr></p>

**BarionPHP** lets you
* Start an online immediate or reservation payment easily
* Get details about a given payment
* Finish an ongoing reservation payment completely or partially, with automatic refund support
* Refund a completed payment transaction completely or partially

All with just a few simple pieces of code!

# Version history

<p align="center"><hr><strong>⚠⚠⚠ Different versioning from the official Barion library: ⚠⚠⚠</strong><hr>The table below shows the current version and which Barion library version it covers.<hr></p>

| Version                        | Covers                            |
|--------------------------------|-----------------------------------|
 | [**1.0.0** (March 18. 2023)](https://github.com/bencurio/barion-web-php/blob/master/changelog.md#v100-2023-03-19) | [**1.4.10** (January 17. 2021)](https://github.com/barion/barion-web-php/releases/tag/v1.4.10) | 

For details about version changes, please refer to the **[changelog.md](https://github.com/bencurio/barion-web-php/blob/master/changelog.md)** file.

# Todo

- [x] Composer support
- [x] PSR-4
- [ ] E2E tests (WIP)
- [ ] Tests (WIP)

# System requirements

* PHP 7.4 or higher
* cURL PHP extension (at least v7.18.1)
* JSON PHP extension
* SSL enabled (systems using OpenSSL with the version of 0.9.8f at least)

# Installation

```shell
$ composer require bencurio/barion-web-php
```

# Basic usage

Include the composer autoloader in your PHP script:

```php
require_once __DIR__ . '/vendor/autoload.php';
```

Then instantiate a Barion client. To achieve this, you must supply three parameters.

First, the secret key of the online store registered in Barion (called POSKey)

```php
$myPosKey = "9c165cfc-cbd1-452f-8307-21a3a9cee664";
```

The API version number (2 by default)

```php
$apiVersion = 2;
```

The environment to connect to. This can be the test system, or the production environment.

```php
// Test environment
$environment = \Bencurio\Barion\Enum\BarionEnvironment::Test;

// Production environment
$environment = \Bencurio\Barion\Enum\BarionEnvironment::Prod;
```

With these parameters you can create an instance of the **BarionClient** class:

```php
use \Bencurio\Barion\BarionClient;

$BC = new BarionClient($myPosKey, $apiVersion, $environment);
```

If you're having problems with the SSL connection then you can set the fourth parameter to true: `$useBundledRootCerts`
This will use the bundled root certificate list instead of the server provided one. 

# Base flow

Using the library, managing a payment process consists of two steps:

## 1. Starting the payment

### 1.1. Creating the request object

To start an online payment, you have to create one or more **Payment Transaction** objects, add transaction **Items** to them, then group these transactions together in a **Payment** object.

First, create an **ItemModel**:

```php
use Bencurio\Barion\Models\Common\ItemModel;

$item = new ItemModel();
$item->Name = "TestItem";
$item->Description = "A test product";
$item->Quantity = 1;
$item->Unit = "piece";
$item->UnitPrice = 1000;
$item->ItemTotal = 1000;
$item->SKU = "ITEM-01";
```

Then create a **PaymentTransactionModel** and add the **Item** mentioned above to it:

```php
use Bencurio\Barion\Models\Payment\PaymentTransactionModel;

$trans = new PaymentTransactionModel();
$trans->POSTransactionId = "TRANS-01";
$trans->Payee = "webshop@example.com";
$trans->Total = 1000;
$trans->Currency = Currency::HUF;
$trans->Comment = "Test transaction containing the product";
$trans->AddItem($item);
```

Finally, create a **PreparePaymentRequestModel** and add the **PaymentTransactionModel** mentioned above to it:

```php
use Bencurio\Barion\Models\Payment\PreparePaymentRequestModel;

$ppr = new PreparePaymentRequestModel();
$ppr->GuestCheckout = true;
$ppr->PaymentType = PaymentType::Immediate;
$ppr->FundingSources = array(FundingSourceType::All);
$ppr->PaymentRequestId = "PAYMENT-01";
$ppr->PayerHint = "user@example.com";
$ppr->Locale = UILocale::EN;
$ppr->OrderNumber = "ORDER-0001";
$ppr->Currency = Currency::HUF;
$ppr->RedirectUrl = "http://webshop.example.com/afterpayment";
$ppr->CallbackUrl = "http://webshop.example.com/processpayment";
$ppr->AddTransaction($trans);
```
**Note:** the secret *POSKey* used for authentication is not part of the request object.
The Barion client class automatically injects this value into every request sent to the Barion API.

### 1.2. Calling the Barion API

Now you can call the **PreparePayment** method of the Barion client with the request model you just created:

```php
$myPayment = $BC->PreparePayment($ppr);
```

The Barion API now prepares a payment entity that can be paid by anyone.

The **$myPayment** variable holds the response received from the Barion API, which is an instance of a **PreparePaymentResponseModel** object.

### 1.3. Redirecting the user to the Barion Smart Gateway

You can use the **PaymentId** value in the response to redirect the user to the Barion Smart Gateway. You have to supply this identifier in the **Id** query string parameter.
The complete redirect URL looks like this:

```
https://secure.barion.com/Pay?id=<your_payment_id>
```

The user can now complete the payment at the Barion Smart Gateway.

## 2. Getting information about a payment

In this example we are going to get detailed information about the payment we just created above.

### 2.1. Creating the request object

To request details about a payment, you only need one parameter: the payment identifier. This is the **PaymentId** we have used earlier to redirect the user.

```
64157032-d3dc-4296-aeda-fd4b0994c64e
```

### 2.2. Calling the Barion API

To request payment details, we call the **GetPaymentState** method of the Barion client class with the identifier above:

```php
$paymentDetails = $BC->GetPaymentState("64157032-d3dc-4296-aeda-fd4b0994c64e");
```

Based on the payment status and parameters received in the response, the shop can now decide whether the payment was successful or not.

# Basic troubleshooting

Here are a few common mistakes you might want to double check for before reaching out to our support:

**1. I get a "User authentication failed" error when sending my request**
- Check if you are sending the correct POSkey to the correct environment, e.g. if you want to call the API in the TEST environment, use the POSkey of the shop that you registered on the TEST website.
- Check if the sent data is actually a valid JSON string, without any special characters, delimiters, line-breaks or invalid encoding.

**2. I get a "Shop is closed" error message in the TEST environment**
- Check if your shop is open after logging in to the Barion Test website. Please note that you must fill out every data of your shop and then send it to approval. After this, approval will automatically be completed and your shop will be in Open status. This only applies to the TEST environment.


# Further examples

To view more examples about the usage of the Barion library, refer to the *docs* and *examples* folders of the repository.

*© 2017 Barion Payment Inc.*
