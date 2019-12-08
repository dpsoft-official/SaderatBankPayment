<?php


use Dpsoft\Saderat\Saderat;
use PHPUnit\Framework\TestCase;

class SaderatTest extends TestCase
{

    /**
     * @var Saderat
     */
    private $Saderat;

    public function testPayRequest_will_return_invoice_id_and_redirect_to_gateway()
    {
        $callbackUrl = 'http://www.example.com';
        $invoiceId = $this->Saderat->request(
            $callbackUrl,
            1000,
            'test'
        );
        $redirectScriptString = $this->Saderat->getRedirectScript();
        $this->assertContains((string)$invoiceId, $redirectScriptString);
        $this->assertContains($callbackUrl, $redirectScriptString);
        $this->assertContains('1000', $redirectScriptString);
        $this->assertContains('test', $redirectScriptString);
    }


    public function setUp()
    {
        $this->Saderat = new Saderat(123);
    }

}
