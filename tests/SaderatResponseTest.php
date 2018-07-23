<?php


use Dpsoft\Saderat\Exception\SaderatException;
use Dpsoft\Saderat\SaderatResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ValidationException;

class SaderatResponseTest extends TestCase
{
    /**
     * @var $SaderatResponse SaderatResponse
     */
    private $SaderatResponse;

    /**
     * @var int
     */
    private $randStr;
    private $invoiceid;
    private $randNumber;

    public function testResponseData_will_return_validation_exeption_of_response_data()
    {
        $this->expectException(ValidationException::class);
        $post = [
            'respcode' => 0,
            'amount' => 1000,
            'invoiceid' => $this->invoiceid,
            'payload' => 123,
            'terminalid' => 61000063,
            'tracenumber' => 123,
            'rrn' => 0,
            'datepaid' => '2018-05-22',
            'digitalreceipt' => $this->randStr,
            'issuerbank' => 'test bank',
            'respmsg' => 'test message',
            'cardnumber' => '6104-33***0244'
        ];
        $this->SaderatResponse->getPostVariables($post);
    }

    /**
     * @test
     *
     * @throws SaderatException
     * @throws RequestException
     */
    public function testverify_will_return_Saderat_exeption_respcode_not_valid()
    {
        $this->expectException(SaderatException::class);
        $this->SaderatResponse->setRespCode(-1);
        $this->SaderatResponse->verify();
    }

    /**
     * @test
     *
     * @throws SaderatException
     * @throws RequestException
     */
    public function testverify_will_return_Saderat_exeption_respcode_unknow()
    {
        $this->expectException(SaderatException::class);
        $this->SaderatResponse->setRespCode(-9);
        $this->SaderatResponse->verify();
    }

    /**
     * @test
     *
     * @throws SaderatException
     * @throws RequestException
     */
    public function testverify_will_return_Saderat_exeption()
    {
        $this->expectException(SaderatException::class);
        $this->SaderatResponse->setClient($this->clientMock('NOK', -4));
        $this->SaderatResponse->verify();
    }

    /**
     * @test
     *
     * @throws SaderatException
     * @throws RequestException
     */
    public function testverify_will_return_value_in_class()
    {
        $this->SaderatResponse->setClient($this->clientMock('Ok', 1000));
        $response = $this->SaderatResponse->verify();
        $this->assertEquals(1000, $response->getAmount());
        $this->assertEquals(0, $response->getRespCode());
    }


    /**
     * @test
     *
     * @throws SaderatException
     * @throws RequestException
     */
    public function testverify_will_return_value_in_array()
    {
        $this->SaderatResponse->setClient($this->clientMock('Ok', 1000));
        $response = $this->SaderatResponse->verify();
        $this->assertArrayHasKey('amount', $response->toArray());
    }


    /**
     * @test
     *
     * @throws SaderatException
     * @throws RequestException
     *
     */
    public function testRollbackPayment_will_return_Saderat_exeption()
    {
        $this->expectException(SaderatException::class);
        $this->SaderatResponse->setClient($this->clientMock('NOK', -8));
        $this->SaderatResponse->rollbackPayment('sdf');
    }


    /**
     * @test
     *
     * @throws SaderatException
     * @throws RequestException
     */
    public function testRollbackPayment_will_return_true_response()
    {
        $this->SaderatResponse->setClient($this->clientMock('Ok', 0));
        $result = $this->SaderatResponse->rollbackPayment('sdf');
        $this->assertTrue($result);
    }


    /**
     * @test
     *
     * @throws SaderatException
     * @throws RequestException
     */
    public function testRollbackPayment_will_return_request_exception()
    {
        $this->expectException(RequestException::class);
        $this->SaderatResponse->setClient($this->clientMockError());
        $this->SaderatResponse->rollbackPayment('sdf');
    }


    /**
     * @param string $status
     * @param int $returnId
     *
     * @return Client
     */
    public function clientMock($status, $returnId)
    {
        $mock = new MockHandler(
            [
                new Response(
                    200, [],
                    json_encode(['Status' => $status, 'ReturnId' => $returnId])
                ), new RequestException(
                "Error Communicating with Server",
                new Request('GET', 'test')
            )
            ]
        );

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return $client;
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


    public function setUp()
    {
        $this->randStr = uniqid();
        $this->randNumber = rand();
        $this->invoiceid = rand(000000000, 999999999);
        $post = [
            'respcode' => 0,
            'amount' => 1000,
            'invoiceid' => $this->invoiceid,
            'payload' => $this->randStr,
            'terminalid' => $this->randNumber,
            'tracenumber' => $this->randNumber,
            'rrn' => $this->randNumber,
            'datepaid' => '2018-05-22',
            'digitalreceipt' => $this->randStr,
            'issuerbank' => $this->randStr,
            'respmsg' => $this->randStr,
            'cardnumber' => $this->randStr
        ];
        $this->SaderatResponse = new SaderatResponse(12345678, $post);
    }
}
