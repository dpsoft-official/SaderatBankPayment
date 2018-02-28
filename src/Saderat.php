<?php
/**
 * Created by PhpStorm.
 * User: sadeghpm
 * Date: 2/21/18
 * Time: 5:38 PM
 */

namespace Dpsoft\Saderat;


use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class Saderat
{
    /**
     * @var int
     */
    private $terminalId;
    /**
     * @var int
     */
    private $merchantId;

    /**
     * @var OpenSsl
     */
    private $ssl;
    /**
     * @var Request
     */
    private $request;

    public function __construct(int $terminalId, int $merchantId, string $publicKey, string $privateKey)
    {

        $this->terminalId = $terminalId;
        $this->merchantId = $merchantId;
        $this->ssl = new OpenSsl($publicKey, $privateKey);
        $this->request = new Request();
    }

    /**
     * Request payment
     *
     * @param int    $amount
     * @param string $callbackUrl redirect user after transaction to this url
     *
     * @return array
     * @throws Exception\ReservationException
     * @throws Exception\TokenVerifyException
     * @throws ValidationException
     */
    public function payRequest(int $amount, string $callbackUrl)
    {
        $payParams = $this->makePayRequestParam($amount, $callbackUrl);
        $result = $this->request->reservation($payParams['params']);
        if ($result->result === 0) {
            if ($this->ssl->verify($result->token, $result->signature)) {
                return ['token' => $result->token, 'CRN' => $payParams['CRN']];
            } else {
                throw new Exception\TokenVerifyException();
            }
        } else {
            throw new Exception\ReservationException($result->result);
        }
    }

    /**
     * @param int    $amount
     * @param string $callbackUrl
     *
     * @return array
     * @throws ValidationException
     */
    private function makePayRequestParam(int $amount, string $callbackUrl)
    {
        v::url()->assert($callbackUrl);
        v::numeric()->min(1000)->assert($amount);
        $CRN = substr(str_pad(str_replace('.', '', microtime(true)), 12, 0), 0, 12);
        $payParam = array(
            "Token_param" =>
                [
                    "AMOUNT"        => $this->ssl->publicEncrypt($amount),
                    "CRN"           => $this->ssl->publicEncrypt($CRN),
                    "MID"           => $this->ssl->publicEncrypt($this->merchantId),
                    "REFERALADRESS" => $this->ssl->publicEncrypt($callbackUrl),
                    "SIGNATURE"     => $this->ssl->privateEncrypt(
                        $amount . $CRN . $this->merchantId . $callbackUrl . $this->terminalId
                    ),
                    "TID"           => $this->ssl->publicEncrypt($this->terminalId),
                ],
        );
        return ['params' => $payParam, 'CRN' => $CRN];
    }

    /**
     * @param array $response
     *
     * @return mixed
     * @throws Exception\VerifyException
     */
    public function verify(array $response)
    {
        $this->validateVerifyData($response);
        if ($response['RESCODE'] === '00') {
            $result = $this->responseConfirm($response);
            if ($this->validateWithSsl($result)) {
                if (!empty($result->RESCODE) AND ($result->RESCODE == '00')
                    && ($result->successful == true)
                ) {
                    return array_merge((array)$result, $response);
                } else {
                    throw new Exception\VerifyException($result->RESCODE);
                }
            } else {
                throw new Exception\VerifyException($result->RESCODE ?? -3);
            }
        } else {
            throw new Exception\VerifyException($response['RESCODE']);
        }
    }

    /**
     * Generate redirect script to use in web page
     *
     * @param $token
     *
     * @return string just echo return
     */
    public function getRedirectScript($token)
    {
        return $this->request->getRedirectScript($token);
    }

    /**
     * @param array $response
     *
     * @throws ValidationException
     */
    private function validateVerifyData(array $response)
    {
        v::key('RESCODE', v::numeric())->key('CRN', v::numeric())->key('TRN', v::optional(v::notEmpty()))
            ->setName('saderat bank response')->assert($response);
    }

    /**
     * @param array $response
     *
     * @return mixed
     */
    private function responseConfirm(array $response)
    {
        $confReq = ["SaleConf_req" =>
                        [
                            "MID"       => $this->ssl->publicEncrypt($this->merchantId),
                            "CRN"       => $this->ssl->publicEncrypt($response['CRN']),
                            "TRN"       => $this->ssl->publicEncrypt($response['TRN']),
                            "SIGNATURE" => $this->ssl->privateEncrypt(
                                $this->merchantId . $response['TRN'] . $response['CRN']
                            ),
                        ],
        ];
        $result = $this->request->sendConfirmation($confReq);
        return $result;
    }

    private function validateWithSsl($result)
    {
        $DATA = $result->RESCODE . $result->REPETETIVE . $result->AMOUNT . $result->DATE
            . $result->TIME . $result->TRN . $result->STAN;
        return $result->successful AND $this->ssl->verify($DATA, $result->SIGNATURE);
    }

    /**
     * @param OpenSsl $ssl
     */
    public function setSsl(OpenSsl $ssl)
    {
        $this->ssl = $ssl;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

}