<?php
/**
 * Created by PhpStorm.
 * User: sadeghpm
 * Date: 2/21/18
 * Time: 5:56 PM
 */

namespace Dpsoft\Saderat;


class OpenSsl
{
    /**
     * @var string
     */
    private $publicKey;
    /**
     * @var string
     */
    private $privateKey;

    public function __construct(string $publicKey, string $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    public function publicEncrypt($text)
    {
        $CRYPT_TEXT = '';
        openssl_public_encrypt($text, $CRYPT_TEXT, $this->publicKey);
        return base64_encode($CRYPT_TEXT);
    }

    public function privateEncrypt($text)
    {
        $SIGNATURE = '';
        openssl_sign($text, $SIGNATURE, $this->privateKey, OPENSSL_ALGO_SHA1);
        return base64_encode($SIGNATURE);
    }

    public function verify($data, $sign)
    {
        return openssl_verify($data, base64_decode($sign), $this->publicKey);
    }

    public function freeKey()
    {
        openssl_free_key($this->publicKey);
    }
}