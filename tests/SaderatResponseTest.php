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

class SaderatResponseTest extends TestCase
{
    /**
     * @var $SaderatResponse SaderatResponse
     */
    private $SaderatResponse;

    /**
     * @test
     *
     * @throws SaderatException
     * @throws RequestException
     */
    public function test_verify_will_return_Saderat_exception_recode_not_valid()
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
    public function test_verify_will_return_Saderat_exception_respcode_unknown()
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
    public function test_verify_will_return_Saderat_exception()
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
    public function test_verify_will_return_value_in_class()
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
    public function test_verify_will_return_value_in_array()
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
    public function test_RollbackPayment_will_return_Saderat_exeption()
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
    public function test_RollbackPayment_will_return_true_response()
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
    public function test_RollbackPayment_will_return_request_exception()
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
