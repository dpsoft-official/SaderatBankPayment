<?php
/**
 * Created by PhpStorm.
 * User: sadeghpm
 * Date: 2/22/18
 * Time: 7:16 PM
 */

namespace Tests;


use Dpsoft\Saderat\OpenSsl;
use Dpsoft\Saderat\Request;
use PHPUnit\Framework\TestCase;

class Base extends TestCase
{
    public function sslMock($verifyOk = false)
    {
        $ssl = $this->createMock(OpenSsl::class);
        $ssl->method('publicEncrypt')->willReturn('pcrypt');
        $ssl->method('privateEncrypt')->willReturn('prcrypt');
        $ssl->method('verify')->willReturn($verifyOk);
        return $ssl;
    }

    public function wsdlMock($resultCode = 0)
    {
        $fromWsdl = $this->getMockFromWsdl(Request::WSDL_REQUEST);
        $result = new \stdClass();
        $return = new \stdClass();
        $return->result = $resultCode;
        $return->token = uniqid();
        $return->signature = 'yyyxxxxyyy';
        $result->return = $return;
        $fromWsdl->method('reservation')->will($this->returnValue($result));

        return $fromWsdl;
    }

    public function wsdlVerifyMock($resultCode = 0, $successful = false)
    {
        $fromWsdl = $this->getMockFromWsdl(Request::WSDL_VERIFY);

        $conf = new \stdClass();
        $conf->RESCODE = $resultCode;
        $conf->REPETETIVE = 'xx';
        $conf->AMOUNT = 'xx';
        $conf->DATE = 'xx';
        $conf->TIME = 'xx';
        $conf->TRN = 'xx';
        $conf->STAN = 'xx';
        $conf->successful = $successful;
        $conf->SIGNATURE = 'xx';
        $result = new \stdClass();
        $result->return = $conf;
        $fromWsdl->method('sendConfirmation')->will($this->returnValue($result));

        return $fromWsdl;
    }

    public function verifyData($result = '00')
    {
        return ['RESCODE' => $result, 'CRN' => 'xxx', 'TRN' => 'yyy'];
    }
}