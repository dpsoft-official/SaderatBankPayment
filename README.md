# Mabna Card Aria Payment Package(Saderat Bank) - v3

[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)

Mabna Cart Aria is a iranian company work at bank payment and allow you to handle checkout in your website with iranian payment cart(Saderat Bank).

## Why new version every year?!
بانک صادرات هر ساله ویرایش جدیدی از آستین بیرون میاره. فقط میشه گفت شرم‌آوره. 

# Installation
``` bash
composer require dpsoft/saderat
```

# Implementation
Attention: The Saderat Bank webservice just available with IP that allowed with Saderat Bank(by contract with Mabna cart company).
<br><br>[استفاده از درگاه بانک صادرات - مبنا - در زبان Php](https://dpsoft.ir/news/27/%D8%A7%D8%B3%D8%AA%D9%81%D8%A7%D8%AF%D9%87-%D8%A7%D8%B2-%D8%AF%D8%B1%DA%AF%D8%A7%D9%87-%D8%A8%D8%A7%D9%86%DA%A9-%D8%B5%D8%A7%D8%AF%D8%B1%D8%A7%D8%AA-%D9%85%D8%A8%D9%86%D8%A7-%D8%AF%D8%B1-%D8%B2%D8%A8%D8%A7%D9%86-php)
#### Redirect customer with parameters to Saderat gateway
```php
<?php use Dpsoft\Saderat\Saderat;

try{
    /**
    * @param int $terminalId The Saderat cart terminal id assign to you 
    */
    $request = new Saderat($terminalId);
	
    /**
     * @param string $callbackUrl The url that customer redirect to after payment
     * @param int $amount The amount that customer must pay
     * @param string $payload Optional addition data
	 *
	 * @method payRequest Return invoice id and you can save in your DB
	 *
     */
    $invoiceId = $request->request($callbackUrl, $amount, $payload);
	
    echo $request->getRedirectScript();
   
}catch (\Throwable $exception){
    echo $exception->getMessage();
}
```
#### Verify transaction or maybe rollback transaction in conditions
Customer redirect to callback url with all transaction data and you must verify or rollback transaction.
<br>If you don't call verify(), after 30 min transaction rollback by system.
#### verify:
```php
<?php
use Dpsoft\Saderat\Saderat;

try{
    /**
      * @var int $terminalId
      */
    $response = new Saderat($terminalId);
	
        /**
          * @method $verify return class of all response value and you can convert to array by toArray() method
          */
        $verifyData = $response->verify();
        /**
          * Check your amount with response amount
          */
        echo "Successful payment ...";
       
}catch (\Throwable $exception){
    echo $exception->getMessage();
}
```
#### Rollback transaction
Need access to rollback payment with Mabna Cart Company
```php
<?php
use Dpsoft\Saderat\Saderat;

try{
    /**
      * @var int $terminalId
      */
    $response = new Saderat($terminalId);
	
    $response->rollbackPayment($digitalReceipt);
    	
    echo "Successful rollback transaction ...";
       
}catch (\Throwable $exception){
    echo $exception->getMessage();
}
```
##### Transaction data or response contains:
<li>respcode: The code of transaction result, if this code equals 0, transaction is success and we can send verify or rollback request if this code equals -1 the customer cancelled payment</li>
<li>amount: Amount laid out of customer card(you must compare this amount with your amount for security reasons)</li>
<li>invoiceid: The invoice number of transaction</li>
<li>payload: The adition data we send with customer to Mabna cart gateway</li>
<li>terminalid: Terminal number</li>
<li>tracenumber: Tracking number of transaction</li>
<li>rrn: Bank document numer, this number generate by gateway and must save for tracking if it was needed</li>
<li>datepaid: Time of transaction</li>
<li>digitalreceipt: Digital Receipt for verify or rollback payment</li>
<li>issuerbank(string): The bank's name of customer cart</li>
<li>cardnumber: customer card number</li>
<li>respmsg: Message of transaction</li>

<br>You can access response data with get method of returned class and if you want access params by array you can call toArray() method.
<br>
Get value by class object:
 ```php
 $digitalReceipt = $verifyData->getDigitalReceipt();
 ```
Get value by array:
```php
$verifyDataArray = $verifyData->toArray();
$digitalReceipt = $verifyDataArray['digitalreceipt'];
```




  


