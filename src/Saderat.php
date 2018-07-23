<?php namespace Dpsoft\Saderat;


use GuzzleHttp\Exception\RequestException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class Saderat
{
    /**
     * Saderat action url
     *
     * @var string
     *
     */
    const Saderat_SHAPARAK_URL = "https://Saderat.shaparak.ir:8080/Pay";

    /**
     * Payment options
     *
     * @var array
     *
     */
    public $payParams = [];

    /**
     * Terminal ID
     *
     * @var int
     */
    private $terminalId;


    /**
     * @param int $terminalId
     */
    public function __construct(int $terminalId)
    {
        $this->terminalId = $terminalId;

        $this->payParams['TerminalID'] = $terminalId;
    }


    /**
     * Get to gateway with parameters
     *
     * @param string $callbackUrl Redirect url after payment
     * @param int $amount in rial
     * @param string $payload additional data
     *
     * @return string
     *
     * @throws ValidationException
     *
     */
    public function payRequest(string $callbackUrl, int $amount,
                               string $payload = null
    )
    {
        v::url()->assert($callbackUrl);
        v::numeric()->min(1000)->assert($amount);
        v::stringType()->assert($payload);

        $invoiceId = rand(100000000, 999999999);

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
        $js_code
            = '<script>let form = document.createElement("form");
form.setAttribute("method", "POST");
form.setAttribute("action", "%s");
form.setAttribute("target", "_self");';
        $i = 0;
        foreach ($this->payParams as $key => $value) {

            $js_code .= sprintf(
                'var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", "%s");
                hiddenField.setAttribute("value", "%s");
                form.appendChild(hiddenField);', $key, $value
            );
            $i++;
        }

        $js_code .= 'document.body.appendChild(form);form.submit();</script>';

        return sprintf($js_code, self::Saderat_SHAPARAK_URL);
    }


    /**
     * Verify and get data of transaction by get method or arrayValue too.(by call toArray())
     *
     * @return SaderatResponse
     *
     * @throws Exception\SaderatException
     * @throws ValidationException
     * @throws RequestException
     *
     */
    public function verify()
    {
        $response = new SaderatResponse($this->terminalId);

        return $response->verify();
    }


    /**
     * Rollback payment
     *
     * @param string $digitalReceipt of transaction need to rollback
     *
     * @return bool
     *
     * @throws Exception\SaderatException
     * @throws ValidationException
     * @throws RequestException
     *
     */
    public function rollbackPayment(string $digitalReceipt = null)
    {
        if ($digitalReceipt == '') {
            $digitalReceipt = $_POST['digitalreceipt'];
        }
        $response = new SaderatResponse($this->terminalId);

        return $response->rollbackPayment($digitalReceipt);
    }

}
