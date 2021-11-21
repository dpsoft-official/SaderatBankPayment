<?php
include __DIR__."/../vendor/autoload.php";
session_start();

if (isset($_POST['terminal_id'])){
    try {
        $pay = new \Dpsoft\Saderat\Saderat($_POST['terminal_id']);
        $_SESSION['invoice_id'] = $pay->request($_POST['callback_url'],$_POST['amount']);
        $_SESSION['amount']=$_POST['amount'];

        echo $pay->getRedirectScript();
        exit();
    }catch (Throwable $exception){

    }
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

    <title>Saderat V3 Request Sample</title>
</head>
<body class="container">

<h1>Saderat V3 Sample</h1>
<blockquote>
    <p><em><?=isset($exception)?'Exception':'' ?></em></p>
    <p class="button"><em><?=isset($exception)?$exception->getMessage():null ?></em></p>
</blockquote>
<form action="" method="post">
    <label for="terminal_id">Terminal ID</label><input type="text" name="terminal_id" id="terminal_id" value="<?= $_POST['terminal_id']??'test' ?>" >
    <label for="amount">Amounts In Rial</label><input type="number" name="amount" id="amount" value="<?= $_POST['amount']??null ?>">
    <label for="callbackUrl">Callback URL</label><input type="url" name="callback_url" id="callbackUrl" value="<?= $_POST['callback_url']??"http://{$_SERVER['HTTP_HOST']}/callback.php" ?>">
    <input type="submit" value="submit">
</form>

</body>
</html>