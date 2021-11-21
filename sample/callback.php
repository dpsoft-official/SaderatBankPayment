<?php
include __DIR__."/../vendor/autoload.php";
session_start();

    try {
        $pay = new \Dpsoft\Saderat\Saderat($_POST['terminalid']);
        $result = $pay->verify();

    }catch (Throwable $exception){

    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">

    <title>Saderat V3 Callback Sample</title>
</head>
<body class="container">

<h1>Saderat V3 Callback Sample</h1>
<blockquote>
    <p><b><?=isset($exception)?'Exception':'' ?></b></p>
    <p><em><?=isset($exception)?"Message: ".$exception->getMessage():null ?></em></p>
    <div>
        Post Data: <code>
            <?=var_export($_POST,true) ?>
        </code>
    </div>
    <?php if(!empty($result)) {?>
        <p><em>Token = <?= $result->getDigitalReceipt() ?></em></p>
        <p><em>Card Number = <?= $result->getCardNumber() ?></em></p>
        <p><em>Invoice Id = = <?= $result->getInvoiceId() ?></em></p>
        <p><em>Amount = <?= $result->getAmount() ?></em></p>

    <?php } ?>
</blockquote>

</body>
</html>