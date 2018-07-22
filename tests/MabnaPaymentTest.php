<?php


use Dpsoft\Saderat\MabnaPayment;
use PHPUnit\Framework\TestCase;

class MabnaPaymentTest extends TestCase
{

    /**
     * @var MabnaPayment
     */
    private $mabnaPayment;


    public function testPayRequest_it_should_throw_validation_exception()
    {
        $this->expectException(
            \Respect\Validation\Exceptions\ValidationException::class
        );
        $this->mabnaPayment->payRequest('http://google.com', 100, 'test');
    }


    public function testPayRequest_will_return_invoice_id_and_redirect_to_gateway()
    {
        $callbackUrl = 'http://www.example.com';
        $invoiceId = $this->mabnaPayment->payRequest(
            $callbackUrl, 1000, 'test'
        );
        $redirectScriptString = $this->mabnaPayment->getRedirectScript();
        $this->assertContains((string)$invoiceId, $redirectScriptString);
        $this->assertContains($callbackUrl, $redirectScriptString);
        $this->assertContains('1000', $redirectScriptString);
        $this->assertContains('test', $redirectScriptString);
    }


    public function setUp()
    {
        $this->mabnaPayment = new MabnaPayment(123);
    }

}
