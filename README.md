# SaderatBankPayment

[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)

پرداخت آنلاین بانک صادرات / Saderat bank onilne payment Library in php

### Install

Install latest version using [composer](https://getcomposer.org/).

``` bash
$ composer require dpsoft-ir/saderat_bank
```

###Usage
Request payment:
```php
<?php
use Dpsoft\Saderat\Saderat;
try{
    $saderat= new Saderat($terminalId,$merchantId,$publicKey,$privateKey);
    $request_params = $saderat->payRequest($amount_rial,$callbackUrl);
    /**
    * $request_params is an array with to key:['token'=>'xx','CRN'] 
    * save this two key and redirect user to payment page
    */
    echo $saderat->getRedirectScript($request_params['token']);
    
}catch (\Throwable $exception){
    echo $exception->getMessage();
}
```

Response verify:
```php
<?php
use Dpsoft\Saderat\Saderat;
try{
    $saderat= new Saderat($terminalId,$merchantId,$publicKey,$privateKey);
    $verifyData = $saderat->verify($_POST);
    /**
    * $verifyData is an array :['CRN'=>'xx','REFERALADRESS'=>'xxx'] 
    * save this two key and redirect user to payment page
    */
    echo "successful payment...thank u.";
    
}catch (\Throwable $exception){
    echo $exception->getMessage();
}
```