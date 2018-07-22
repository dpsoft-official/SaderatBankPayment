# Mabna Card Aria Payment Package - v2

[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)

Mabna Cart Aria is a iranian company work at bank payment and allow you to handle checkout in your website with iranian payment cart.

# Is right for me?
If you need integration your website with Mabna cart payment or need to now Mabna cart how to work, you are in the right place.

# Steps of payment with Mabna cart v2
<ol>
<li> Customer choose sevices or products in website</li>
<li> Redirect customer to gateway with payment params(with post method)</li>
<li> Customer enter cart data in Mabna gateway and pay amount</li>
<li> Mabna cart redirect customer to callback url with post method and transaction response parameters</li>
<li> Post response parameter to Mabna cart verify url </li>
<li> Receive response of verify url and handle transaction </li>
</ol>

# Installation
``` bash
$ composer require dpsoft/mabna
```

# Implementation
Attention: The Mabna cart webservice just available with IP that allowed with Mabna cart(by contract with Mabna cart company).

#### Redirect customer with parameters to Mabna gateway
```php
<?php use Dpsoft\Mabna\MabnaPayment;

try{
    /**
    * @param int $terminalId The Mabna cart terminal id assign to you 
    */
    $request = new MabnaPayment($terminalId);
	
    /**
     * @param string $callbackUrl The url that customer redirect to after payment
     * @param int $amount The amount that customer must pay
     * @param string $payload Optional addition data
	 *
	 * @method payRequest Return invoice id and you can save in your DB
	 *
     */
    $invoiceId = $request->payRequest($callbackUrl, $amount, $payload);
	
    echo $request->getRedirectScript();
   
}catch (\Throwable $exception){
    echo $exception->getMessage();
}
```
#### Verify transaction or maybe rollback transaction in conditions
Customer redirect to callback url with all transaction data and you must verify or rollback transaction.
<br>If you don't call verifyPayment(), after 30 min transaction rollback by system.
#### verify:
```php
<?php
use Dpsoft\Mabna\MabnaPayment;

try{
    /**
      * @var int $terminalId
      */
    $response = new MabnaPayment($terminalId);
	
        /**
          * @method $verifyPayment return class of all response value and you can convert to array by toArray() method
          */
        $verifyData = $response->verifyPayment();
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
use Dpsoft\Mabna\MabnaPayment;

try{
    /**
      * @var int $terminalId
      */
    $response = new MabnaPayment($terminalId);
	
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




  


