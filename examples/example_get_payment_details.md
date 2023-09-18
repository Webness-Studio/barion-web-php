```php
<?php

/*
*  Barion PHP library usage example
*  
*  Getting detailed information about a Payment
*  
*  � 2015 Barion Payment Inc.
*/

use Bencurio\Barion\BarionClient;
use Bencurio\Barion\Enum\BarionEnvironment;

$myPosKey = "11111111-1111-1111-1111-111111111111"; // <-- Replace this with your POSKey!
$paymentId = "22222222-2222-2222-2222-222222222222"; // <-- Replace this with the ID of the Payment!

// Barion Client that connects to the TEST environment
$BC = new BarionClient($myPosKey, 2, BarionEnvironment::Test);

// send the request
$paymentDetails = $BC->GetPaymentState($paymentId);

// TODO: process the information contained in $paymentDetails
```