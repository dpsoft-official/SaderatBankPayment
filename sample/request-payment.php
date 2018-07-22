<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mabna Payment</title>
</head>
<body>

</body>
</html>

<?php

use Dpsoft\Saderat\MabnaPayment;

require "../vendor/autoload.php";

$request = new MabnaPayment(61000063);

$invoiceId = $request->payRequest($_POST['callbackurl'], $_POST['amount'], $_POST['payload']);

echo $request->getRedirectScript();

?>
