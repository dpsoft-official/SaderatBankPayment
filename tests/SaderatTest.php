<?php
/**
 * Created by PhpStorm.
 * User: sadeghpm
 * Date: 2/22/18
 * Time: 4:19 PM
 */

namespace Tests;

use Dpsoft\Saderat\Exception\VerifyException;
use Dpsoft\Saderat\Request;
use Dpsoft\Saderat\Saderat;

class SaderatTest extends Base
{
    /**
     * @var Saderat
     */
    private $saderat;

    /**
     * @test
     * @expectedException \Respect\Validation\Exceptions\ValidationException
     */
    public function testPayRequest_it_should_throw_validation_exception()
    {
        $this->saderat->payRequest(20, 'http://sample.com');
    }

    /**
     * @test                  it_should_throw_ReservationException
     * @expectedException \Dpsoft\Saderat\Exception\ReservationException
     * @expectedExceptionCode 3
     */
    public function testPayRequest_it_should_throw_ReservationException()
    {
        $this->saderat->payRequest(1000, 'http://sample.com');
    }

    /**
     * @test
     * @expectedException \Dpsoft\Saderat\Exception\TokenVerifyException
     */
    public function testPayRequest_it_should_throw_tokenVerifyException()
    {
        $this->saderat->setRequest((new Request())->setClient($this->wsdlMock(0)));
        $this->saderat->payRequest(1000, 'http://sample.com');
    }

    /**
     * @test
     */
    public function testPayRequest_it_should_return_token_and_crn()
    {
        $req = (new Request());
        $req->setClient($this->wsdlMock(0));
        $this->saderat->setRequest($req);
        $this->saderat->setSsl($this->sslMock(true));
        $result = $this->saderat->payRequest(1000, 'http://sample.com');
        $this->assertArrayHasKey('token', $result);
        $redirect_script = $req->getRedirectScript($result['token']);
        self::assertContains($result['token'], $redirect_script);
    }

    /**
     * @test
     * @expectedException \Respect\Validation\Exceptions\ValidationException
     */
    public function testVerifyResponse_it_should_throw_validation_exception()
    {
        $result = $this->saderat->verify([]);
    }

    /**
     * @test
     */
    public function testVerifyResponse_it_should_throw_VerifyException()
    {
        $this->expectException(VerifyException::class);
        $this->expectExceptionCode(3);
        $this->saderat->verify($this->verifyData(3));
    }

    /**
     * @test
     */
    public function testVerifyResponse_it_should_throw_VerifyException_with_invalid_verify()
    {
        $this->expectException(VerifyException::class);
        $this->saderat->setRequest((new Request())->setClient($this->wsdlVerifyMock(0)));
        $this->saderat->verify($this->verifyData());
    }

    /**
     * @test
     */
    public function testVerifyResponse_it_should_throw_VerifyException_with_valid_verify()
    {
        $this->saderat->setRequest((new Request())->setClient($this->wsdlVerifyMock('00', true)));
        $this->saderat->setSsl($this->sslMock(true));
        $result = $this->saderat->verify($this->verifyData());
        self::assertArrayHasKey('TRN', $result);
    }

    protected function setUp()
    {
        $this->saderat = new Saderat('123', '123', 'pubKey', 'priKey');
        $this->saderat->setSsl($this->sslMock());
    }
}
