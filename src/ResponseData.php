<?php namespace Dpsoft\Saderat;

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
     * @param  int  $respCode
     */
    public function setRespCode($respCode)
    {
        $this->respCode = $respCode;
    }


    /**
     * Validate and set response Amount
     *
     * @param  int  $amount
     *
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Validate and set invoice id
     *
     * @param  int  $invoiceId
     *
     */
    public function setInvoiceId($invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }

    /**
     * Validate and set payload(additional user data)
     *
     * @param  string  $payload
     *
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Validate and set terminal id
     *
     * @param  int  $terminalId
     *
     */
    public function setTerminalId($terminalId)
    {
        $this->terminalId = $terminalId;
    }

    /**
     * Validate and set trace number(transaction tracking code)
     *
     * @param  int  $traceNumber
     */
    public function setTraceNumber($traceNumber)
    {
        $this->traceNumber = $traceNumber;
    }

    /**
     * Validation and set RRN(Uniqe Bank report number)
     *
     * @param  int  $RRN
     *
     */
    public function setRRN($RRN)
    {
        $this->RRN = $RRN;
    }

    /**
     * Validate and set DatePaid
     *
     * @param  string  $DatePaid
     */
    public function setDatePaid($DatePaid)
    {
        $this->DatePaid = $DatePaid;
    }

    /**
     * Validate and  set Digital Receipt
     *
     * @param  string  $digitalReceipt
     */
    public function setDigitalReceipt($digitalReceipt)
    {
        $this->digitalReceipt = $digitalReceipt;
    }

    /**
     * Validate and set issuer bank
     *
     * @param  string  $issuerBank
     *
     */
    public function setIssuerBank($issuerBank)
    {
        $this->issuerBank = $issuerBank;
    }


    /**
     * Validate and set issuer bank
     *
     * @param  string  $cardNumber
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
    }


    /**
     * Validate and set issuer bank
     *
     * @param  string  $respMsg
     */
    public function setRespMsg($respMsg)
    {
        $this->respMsg = $respMsg;
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
            'respmsg' => $this->getRespMsg(),
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
