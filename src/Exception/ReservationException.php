<?php
/**
 * Created by PhpStorm.
 * User: sadeghpm
 * Date: 2/21/18
 * Time: 7:06 PM
 */

namespace Dpsoft\Saderat\Exception;


class ReservationException extends VerifyException
{

    /**
     * ReservationException constructor.
     *
     * @param $result
     */
    public function __construct(int $result)
    {
        parent::__construct($result);
    }
}