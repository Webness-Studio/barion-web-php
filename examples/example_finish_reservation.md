```php
<?php

/*
*  Barion PHP library usage example
*  
*  Starting a reservation Payment with two products
*  
*  � 2015 Barion Payment Inc.
*/

use Bencurio\Barion\BarionClient;
use Bencurio\Barion\Enum\BarionEnvironment;
use Bencurio\Barion\Models\Common\ItemModel;
use Bencurio\Barion\Models\Payment\FinishReservationRequestModel;
use Bencurio\Barion\Models\Payment\TransactionToFinishModel;

require_once '../library/BarionClient.php';

$myPosKey = "11111111-1111-1111-1111-111111111111"; // <-- Replace this with your POSKey!
$paymentId = "22222222-2222-2222-2222-222222222222"; // <-- Replace this with the ID of the Payment!

// Barion Client that connects to the TEST environment
$BC = new BarionClient($myPosKey, 2, BarionEnvironment::Test);

// create the item Models
$item1 = new ItemModel();
$item1->Name = "TestItem"; // no more than 250 characters
$item1->Description = "A test item for Payment"; // no more than 500 characters
$item1->Quantity = 1;
$item1->Unit = "piece"; // no more than 50 characters
$item1->UnitPrice = 1000;
$item1->ItemTotal = 1000;
$item1->SKU = "ITEM-01"; // no more than 100 characters

$item2 = new ItemModel();
$item2->Name = "AnotherTestItem"; // no more than 250 characters
$item2->Description = "Another test item for Payment"; // no more than 500 characters
$item2->Quantity = 2;
$item2->Unit = "piece"; // no more than 50 characters
$item2->UnitPrice = 250;
$item2->ItemTotal = 250;
$item2->SKU = "ITEM-02"; // no more than 100 characters

// create the transaction model
$trans = new TransactionToFinishModel();
$trans->TransactionId = "33333333-3333-3333-3333-333333333333"; // <-- Replace this with the original transaction ID!
$trans->Total = 1500;
$trans->Comment = "Reservation complete"; // no more than 640 characters
$trans->AddItem($item1); // add the items to the transaction
$trans->AddItem($item2);

// create the request object
$frrm = new FinishReservationRequestModel($paymentId);
$frrm->AddTransaction($trans); // add the transaction to the request

// send the request
$finishReservationResult = $BC->FinishReservation($frrm);

if ($finishReservationResult->RequestSuccessful) {
    // TODO: process the information contained in $finishReservationResult
}
```