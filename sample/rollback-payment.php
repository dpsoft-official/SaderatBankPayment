<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mabna Payment</title>
</head>
<body>
<h1>Mabna Payment Test Page</h1>
<form method="post" action="/soltani-tmp//sample/request-payment.php">
    <fieldset>
        <legend>Sendeing Data</legend>
        <input type="hidden" name="callbackurl"
               value="http://dpe.ac/soltani-tmp/sample/rollback-payment.php"/>
        <div>
            <label for="Amount">Amount:</label>
            <input type="text" name="amount" value="1000"/>
        </div>
        <div>
            <label for="Payload">Payload(Description):</label>
            <input type="text" name="payload" value="test"/>
        </div>
        <br>
        <div>
            <label>&nbsp;</label>
            <input type="submit" value="Send" class="submit"/>
        </div>
    </fieldset>
</form>

<br>

</body>
</html>

<?php

use Dpsoft\Mabna\MabnaPayment;

require "../vendor/autoload.php";


try {
    if ($_POST) {
        var_dump($_POST);
    }

    /**
     * @var int $terminalid
     */
    $payment = new MabnaPayment(61000063);

    if ($_POST['digitalreceipt']) {
        /**
         * @method $verifyPayment return class of all response value and you can conver to array by toArray() method
         */
        $payment = $payment->rollbackPayment($_POST['digitalreceipt']);

        echo "<br>Successful rollback transaction ...";
    }

} catch (\Throwable $exception) {
    echo $exception->getMessage();
}
?>
