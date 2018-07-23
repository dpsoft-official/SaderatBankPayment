<?php namespace Dpsoft\Saderat;


use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class ResponseData
{
    /**
     * @vars
     */
    private $respCode, $amount, $invoiceId, $payload, $terminalId,
        $traceNumber, $RRN, $DatePaid, $digitalReceipt, $issuerBank, $respMsg, $cardNumber;


    /**
     * validate and set Saderat response code
     *
     * @param int $respCode
     *
     * @throws ValidationException
     *
     */
    public function setRespCode($respCode)
    {
        v::numeric()->assert($respCode);
        $this->respCode = $respCode;
    }


    /**
     * Validate and set response Amount
     *
     * @param int $amount
     *
     * @throws ValidationException
     */
    public function setAmount($amount)
    {
        v::numeric()->assert($amount);
        $this->amount = $amount;
    }

    /**
     * Validate and set invoice id
     *
     * @param int $invoiceId
     *
     * @throws ValidationException
     */
    public function setInvoiceId($invoiceId)
    {
        v::numeric()->assert($invoiceId);
        $this->invoiceId = $invoiceId;
    }

    /**
     * Validate and set payload(additional user data)
     *
     * @param string $payload
     *
     * @throws ValidationException
     */
    public function setPayload($payload)
    {
        v::stringType()->assert($payload);
        $this->payload = $payload;
    }

    /**
     * Validate and set terminal id
     *
     * @param int $terminalId
     *
     * @throws ValidationException
     */
    public function setTerminalId($terminalId)
    {
        v::numeric()->assert($terminalId);
        $this->terminalId = $terminalId;
    }

    /**
     * Validate and set trace number(transaction tracking code)
     *
     * @param int $traceNumber
     *
     * @throws ValidationException
     */
    public function setTraceNumber($traceNumber)
    {
        if (isset($traceNumber)) {
            v::numeric()->assert($traceNumber);
            $this->traceNumber = $traceNumber;
        }
    }

    /**
     * Validation and set RRN(Uniqe Bank report number)
     *
     * @param int $RRN
     *
     * @throws ValidationException
     *
     */
    public function setRRN($RRN)
    {
        if (isset($RRN)) {
            v::numeric()->assert($RRN);
            $this->RRN = $RRN;
        }
    }

    /**
     * Validate and set DatePaid
     *
     * @param string $DatePaid
     *
     * @throws ValidationException
     *
     */
    public function setDatePaid($DatePaid)
    {
        if (isset($DatePaid)) {
            v::stringType()->assert($DatePaid);
            $this->DatePaid = $DatePaid;
        }
    }

    /**
     * Validate and  set Digital Receipt
     *
     * @param string $digitalReceipt
     *
     * @throws ValidationException
     *
     */
    public function setDigitalReceipt($digitalReceipt)
    {
        if (isset($digitalReceipt)) {
            v::stringType()->assert($digitalReceipt);
            $this->digitalReceipt = $digitalReceipt;
        }
    }

    /**
     * Validate and set issuer bank
     *
     * @param string $issuerBank
     *
     * @throws ValidationException
     */
    public function setIssuerBank($issuerBank)
    {
        if (isset($issuerBank)) {
            v::stringType()->assert($issuerBank);
            $this->issuerBank = $issuerBank;
        }
    }


    /**
     * Validate and set issuer bank
     *
     * @param string $cardNumber
     *
     * @throws ValidationException
     */
    public function setCardNumber($cardNumber)
    {
        if (isset($cardNumber)) {
            v::stringType()->assert($cardNumber);
            $this->cardNumber = $cardNumber;
        }
    }


    /**
     * Validate and set issuer bank
     *
     * @param string $respMsg
     *
     * @throws ValidationException
     */
    public function setRespMsg($respMsg)
    {
        if (isset($respMsg)) {
            v::stringType()->assert($respMsg);
            $this->respMsg = $respMsg;
        }
    }


    /**
     * @return array
     */
    public function toArray()
    {
        $result = [
            'respcode' => $this->getRespCode(),
            'amount' => $this->getAmount(),
            'invoiceid' => $this->getInvoiceId(),
            'payload' => $this->getPayload(),
            'terminalid' => $this->getTerminalId(),
            'tracenumber' => $this->getTraceNumber(),
            'rrn' => $this->getRRN(),
            'datepaid' => $this->getDatePaid(),
            'digitalreceipt' => $this->getDigitalReceipt(),
            'issuerbank' => $this->getIssuerBank(),
            'cardnumber' => $this->getCardNumber(),
            'respmsg' => $this->getRespMsg()
        ];

        return $result;
    }

    /**
     * Get Saderat response code
     *
     * @return int
     */
    public function getRespCode()
    {
        return $this->respCode;
    }


    /**
     * Get response Amount
     *
     * @return int $amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get invoice id
     *
     * @return int $invoiceId
     */
    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

    /**
     * Get payload
     *
     * @return string $payload
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Get terminal id
     *
     * @return int $terminalId
     */
    public function getTerminalId()
    {
        return $this->terminalId;
    }

    /**
     * Get trace number(transaction tracking code)
     *
     * @return int $traceNumber
     */
    public function getTraceNumber()
    {
        return $this->traceNumber;
    }

    /**
     * Get RRN(Uniqe Bank report number)
     *
     * @return int $RRN
     */
    public function getRRN()
    {
        return $this->RRN;
    }

    /**
     * Get DatePaid
     *
     * @return string $datePaid
     */
    public function getDatePaid()
    {
        return $this->DatePaid;
    }

    /**
     * Get Digital Receipt
     *
     * @return string $digitalReceipt
     */
    public function getDigitalReceipt()
    {
        return $this->digitalReceipt;
    }

    /**
     * Get issuer bank
     *
     * @return string $issuerBank
     */
    public function getIssuerBank()
    {
        return $this->issuerBank;
    }

    /**
     * Get card number
     *
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Get response message
     *
     * @return string
     */
    public function getRespMsg()
    {
        return $this->respMsg;
    }
}
