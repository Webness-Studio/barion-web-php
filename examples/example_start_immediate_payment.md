```php
<?php

/*
*  Barion PHP library usage example
*  
*  Starting an immediate Payment with one product
*  
*  � 2015 Barion Payment Inc.
*/

use Bencurio\Barion\BarionClient;
use Bencurio\Barion\Enum\BarionConstants;
use Bencurio\Barion\Enum\BarionEnvironment;
use Bencurio\Barion\Enum\Currency;
use Bencurio\Barion\Enum\FundingSourceType;
use Bencurio\Barion\Enum\PaymentType;
use Bencurio\Barion\Enum\UILocale;
use Bencurio\Barion\Models\Common\ItemModel;
use Bencurio\Barion\Models\Payment\PaymentTransactionModel;
use Bencurio\Barion\Models\Payment\PreparePaymentRequestModel;
use Bencurio\Barion\Models\Secure3D\ShippingAddressModel;

$myPosKey = "11111111-1111-1111-1111-111111111111"; // <-- Replace this with your POSKey!
$myEmailAddress = "mywebshop@example.com"; // <-- Replace this with your e-mail address in Barion!

// Barion Client that connects to the TEST environment
$BC = new BarionClient($myPosKey, 2, BarionEnvironment::Test);

// create the item model
$item = new ItemModel();
$item->Name = "TestItem"; // no more than 250 characters
$item->Description = "A test item for Payment"; // no more than 500 characters
$item->Quantity = 1;
$item->Unit = "piece"; // no more than 50 characters
$item->UnitPrice = 1000;
$item->ItemTotal = 1000;
$item->SKU = "ITEM-01"; // no more than 100 characters

// create the transaction
$trans = new PaymentTransactionModel();
$trans->POSTransactionId = "TRANS-01";
$trans->Payee = $myEmailAddress; // no more than 256 characters
$trans->Total = 1000;
$trans->Comment = "Test Transaction"; // no more than 640 characters
$trans->AddItem($item); // add the item to the transaction

// create the shipping address
$shippingAddress = new ShippingAddressModel();
$shippingAddress->Country = "HU";
$shippingAddress->Region = null;
$shippingAddress->City = "Budapest";
$shippingAddress->Zip = "1111";
$shippingAddress->Street = "Teszt utca 1.";
$shippingAddress->Street2 = "1. emelet 1. ajto";
$shippingAddress->Street3 = "";
$shippingAddress->FullName = "Teszt Tibor";

// create the request model
$psr = new PreparePaymentRequestModel();
$psr->GuestCheckout = true; // we allow guest checkout
$psr->PaymentType = PaymentType::Immediate; // we want an immediate Payment
$psr->FundingSources = array(FundingSourceType::All); // both Barion wallet and bank card accepted
$psr->PaymentRequestId = "TESTPAY-01"; // no more than 100 characters
$psr->PayerHint = "user@example.com"; // no more than 256 characters
$psr->Locale = UILocale::EN; // the UI language will be English 
$psr->Currency = Currency::HUF;
$psr->OrderNumber = "ORDER-0001"; // no more than 100 characters
$psr->ShippingAddress = $shippingAddress;
$psr->AddTransaction($trans); // add the transaction to the Payment

// send the request
$myPayment = $BC->PreparePayment($psr);

if ($myPayment->RequestSuccessful === true) {
  // redirect the user to the Barion Smart Gateway
  header("Location: " . BarionConstants::BARION_WEB_URL_TEST . "?id=" . $myPayment->PaymentId);
  exit;
}
```