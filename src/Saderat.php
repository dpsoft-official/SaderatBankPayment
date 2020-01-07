<?php namespace Dpsoft\Saderat;


use GuzzleHttp\Exception\RequestException;

class Saderat
{
    /**
     * Saderat action url
     *
     * @var string
     */
    const Saderat_SHAPARAK_URL = "https://Saderat.shaparak.ir:8080/Pay";

    /**
     * Payment options
     *
     * @var array
     */
    public $payParams = [];

    /**
     * Terminal ID
     *
     * @var int
     */
    private $terminalId;


    /**
     * @param  int  $terminalId
     */
    public function __construct(int $terminalId)
    {
        $this->terminalId = $terminalId;

        $this->payParams['TerminalID'] = $terminalId;
    }


    /**
     * Get to gateway with parameters
     *
     * @param  string  $callbackUrl  Redirect url after payment
     * @param  int  $amount  in rial
     * @param  null  $orderId
     * @param  string  $payload  additional data
     * @return int order id
     */
    public function request(string $callbackUrl, int $amount, $orderId = null, string $payload = null)
    {
        $invoiceId = $orderId ? $orderId : $this->uniqueNumber();

        $this->payParams['Amount'] = $amount;
        $this->payParams['callbackURL'] = $callbackUrl;
        $this->payParams['InvoiceID'] = $invoiceId;
        $this->payParams['Payload'] = $payload;

        return $invoiceId;
    }


    /**
     * Script for redirect user to payment gateway with require parameter
     *
     * @return string
     */
    public function getRedirectScript()
    {
        $jsCode = <<<'HTML'
<!DOCTYPE html><html lang="fa"><body>
                <script>
                var form = document.createElement("form");
                form.setAttribute("method", "POST");
                form.setAttribute("action", "%s");
                form.setAttribute("target", "_self");
HTML;
        $i = 0;
        foreach ($this->payParams as $key => $value) {

            $jsCode .= sprintf(
                'var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", "%s");
                hiddenField.setAttribute("value", "%s");
                form.appendChild(hiddenField);',
                $key,
                $value
            );
            $i++;
        }

        $jsCode .= 'document.body.appendChild(form);form.submit();</script></body></html>';

        return sprintf($jsCode, self::Saderat_SHAPARAK_URL);
    }


    /**
     * Verify and get data of transaction by get method or arrayValue too.(by call toArray())
     *
     * @return SaderatResponse
     * @throws Exception\SaderatException
     * @throws RequestException
     */
    public function verify()
    {
        $response = new SaderatResponse($this->terminalId);

        return $response->verify();
    }


    /**
     * Rollback payment
     *
     * @param  string  $digitalReceipt  of transaction need to rollback
     * @return bool
     * @throws Exception\SaderatException
     * @throws RequestException
     */
    public function rollbackPayment(string $digitalReceipt = null)
    {
        if ($digitalReceipt == '') {
            $digitalReceipt = $_POST['digitalreceipt'];
        }
        $response = new SaderatResponse($this->terminalId);

        return $response->rollbackPayment($digitalReceipt);
    }

    public function uniqueNumber()
    {
        return hexdec(uniqid());
    }

}
