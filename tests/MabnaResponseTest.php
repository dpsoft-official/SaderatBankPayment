<?php


use Dpsoft\Mabna\Exception\MabnaException;
use Dpsoft\Mabna\MabnaResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ValidationException;

class MabnaResponseTest extends TestCase
{
    /**
     * @var $mabnaResponse MabnaResponse
     */
    private $mabnaResponse;

    /**
     * @var int
     */
    private $randStr;
    private $invoiceid;
    private $randNumber;

    public function testResponseData_will_return_validation_exeption_of_response_data(
    )
    {
        $this->expectException(ValidationException::class);
        $post = [
            'respcode'       => 0,
            'amount'         => 1000,
            'invoiceid'      => $this->invoiceid,
            'payload'        => 123,
            'terminalid'     => 61000063,
            'tracenumber'    => 123,
            'rrn'            => 0,
            'datepaid'       => '2018-05-22',
            'digitalreceipt' => $this->randStr,
            'issuerbank'     => 'test bank',
            'respmsg'        => 'test message',
            'cardnumber'     => '6104-33***0244'
        ];
        $this->mabnaResponse->getPostVariables($post);
    }

    /**
     * @test
     *
     * @throws MabnaException
     * @throws RequestException
     */
    public function testVerifyPayment_will_return_mabna_exeption_respcode_not_valid(
    )
    {
        $this->expectException(MabnaException::class);
        $this->mabnaResponse->setRespCode(-1);
        $this->mabnaResponse->verifyPayment();
    }

    /**
     * @test
     *
     * @throws MabnaException
     * @throws RequestException
     */
    public function testVerifyPayment_will_return_mabna_exeption_respcode_unknow(
    )
    {
        $this->expectException(MabnaException::class);
        $this->mabnaResponse->setRespCode(-9);
        $this->mabnaResponse->verifyPayment();
    }

    /**
     * @test
     *
     * @throws MabnaException
     * @throws RequestException
     */
    public function testVerifyPayment_will_return_mabna_exeption()
    {
        $this->expectException(MabnaException::class);
        $this->mabnaResponse->setClient($this->clientMock('NOK', -4));
        $this->mabnaResponse->verifyPayment();
    }

    /**
     * @test
     *
     * @throws MabnaException
     * @throws RequestException
     */
    public function testVerifyPayment_will_return_value_in_class()
    {
        $this->mabnaResponse->setClient($this->clientMock('Ok', 1000));
        $response = $this->mabnaResponse->verifyPayment();
        $this->assertEquals(1000, $response->getAmount());
        $this->assertEquals(0, $response->getRespCode());
    }


    /**
     * @test
     *
     * @throws MabnaException
     * @throws RequestException
     */
    public function testVerifyPayment_will_return_value_in_array()
    {
        $this->mabnaResponse->setClient($this->clientMock('Ok', 1000));
        $response = $this->mabnaResponse->verifyPayment();
        $this->assertArrayHasKey('amount', $response->toArray());
    }


    /**
     * @test
     *
     * @throws MabnaException
     * @throws RequestException
     *
     */
    public function testRollbackPayment_will_return_mabna_exeption()
    {
        $this->expectException(MabnaException::class);
        $this->mabnaResponse->setClient($this->clientMock('NOK', -8));
        $this->mabnaResponse->rollbackPayment('sdf');
    }


    /**
     * @test
     *
     * @throws MabnaException
     * @throws RequestException
     */
    public function testRollbackPayment_will_return_true_response()
    {
        $this->mabnaResponse->setClient($this->clientMock('Ok', 0));
        $result = $this->mabnaResponse->rollbackPayment('sdf');
        $this->assertTrue($result);
    }


    /**
     * @test
     *
     * @throws MabnaException
     * @throws RequestException
     */
    public function testRollbackPayment_will_return_request_exception()
    {
        $this->expectException(RequestException::class);
        $this->mabnaResponse->setClient($this->clientMockError());
        $this->mabnaResponse->rollbackPayment('sdf');
    }


    /**
     * @param string $status
     * @param int    $returnId
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
            'respcode'       => 0,
            'amount'         => 1000,
            'invoiceid'      => $this->invoiceid,
            'payload'        => $this->randStr,
            'terminalid'     => $this->randNumber,
            'tracenumber'    => $this->randNumber,
            'rrn'            => $this->randNumber,
            'datepaid'       => '2018-05-22',
            'digitalreceipt' => $this->randStr,
            'issuerbank'     => $this->randStr,
            'respmsg'        => $this->randStr,
            'cardnumber'     => $this->randStr
        ];
        $this->mabnaResponse = new MabnaResponse(12345678, $post);
    }
}
