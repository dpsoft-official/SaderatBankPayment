<?php

use Dpsoft\Saderat\Saderat;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
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
        $this->assertStringContainsString(123, $redirectScriptString);
        $this->assertStringContainsString(456, $redirectScriptString);
    }

    /**
     * @param string $status
     * @param int $Accesstoken
     *
     * @return Client
     */
    public function clientMock($status, $Accesstoken)
    {
        $mock = new MockHandler(
            [
                new Response(
                    200,
                    [],
                    json_encode(['Status' => $status, 'Accesstoken' => $Accesstoken])
                ), new RequestException("Error Communicating with Server", new Request('GET', 'test'))
            ]
        );

        $handler = HandlerStack::create($mock);
        return new Client(['handler' => $handler]);
    }

    /**
     * @return Client
     */
    public function clientMockError()
    {
        $mock = new MockHandler(
            [
                new RequestException(
                    "Error Communicating with Server",
                    new Request('GET', 'test')
                )
            ]
        );

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return $client;
    }

    public function setUp():void
    {
        $this->Saderat = new Saderat(123);
        $this->Saderat->setClient($this->clientMock(0,456));
    }

}
