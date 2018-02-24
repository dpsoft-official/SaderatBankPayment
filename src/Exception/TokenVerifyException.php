<?php
/**
 * Created by PhpStorm.
 * User: sadeghpm
 * Date: 2/21/18
 * Time: 7:04 PM
 */

namespace Dpsoft\Saderat\Exception;


class TokenVerifyException extends \Exception
{

    /**
     * TokenVerifyException constructor.
     */
    public function __construct()
    {
        $this->message = 'Token verify failed!';
    }
}