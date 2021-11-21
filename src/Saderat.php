<?php namespace Dpsoft\Saderat;


use Dpsoft\Saderat\Exception\SaderatException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Saderat
{
    /**
     * Saderat action url
     *
     * @var string
     */
    const Saderat_SHAPARAK_URL = "https://sepehr.shaparak.ir:8080/Pay";
    const REQUEST_TOKEN_URL = "https://sepehr.shaparak.ir:8081/V1/PeymentApi/GetToken";
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
    protected $terminalId;
    /**
     * @var mixed
     */
    protected $token;
    /**
     * @var Client
     */
    private $client;


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
     * @param int $amount in rial more than 1000 rials
     * @param null $orderId
     * @param string|null $payload additional data in json string
     * @return int order id
     * @throws SaderatException ,\Exception
     * @throws \GuzzleHttp\Exception\GuzzleException ,\Exception
     */
    public function request(string $callbackUrl, int $amount, $orderId = null, string $payload = null)
    {
        if ($amount < 1000) {
            throw new \Exception("Amount is below than 1000 Rials.");
        }
        $invoiceId = $orderId ? $orderId : $this->uniqueNumber();

        $this->payParams['Amount'] = $amount;
        $this->payParams['callbackURL'] = $callbackUrl;
        $this->payParams['InvoiceID'] = $invoiceId;
        $this->payParams['Payload'] = $payload;

        $client = $this->client ?? new Client();
        $body = $client->post(
            self::REQUEST_TOKEN_URL,
            [
                'form_params' => $this->payParams,
            ]
        )->getBody();

        $data = json_decode($body, true);

        if (($data['Status'] ?? -1) !== 0) {
            throw new SaderatException($data['Status'] ?? -1);
        }
        $this->token = $data['Accesstoken'];
        return $invoiceId;
    }


    /**
     * Script for redirect user to payment gateway with require parameter
     *
     * @return string
     */
    public function getRedirectScript()
    {
        $data = ['TerminalID' => $this->terminalId, 'token' => $this->token];
        $jsCode = <<<'HTML'
<!DOCTYPE html><html lang="fa"><body>
                <script>
                var form = document.createElement("form");
                form.setAttribute("method", "POST");
                form.setAttribute("action", "%s");
                form.setAttribute("target", "_self");
HTML;
        foreach ($data as $key => $value) {

            $jsCode .= sprintf(
                'var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", "%s");
                hiddenField.setAttribute("value", "%s");
                form.appendChild(hiddenField);',
                $key,
                $value
            );
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
     * @param string $digitalReceipt of transaction need to rollback
     * @return bool
     * @throws Exception\SaderatException
     * @throws RequestException
     */
    public function rollbackPayment(string $digitalReceipt)
    {
        $response = new SaderatResponse($this->terminalId);

        return $response->rollbackPayment($digitalReceipt);
    }

    public function uniqueNumber()
    {
        return hexdec(uniqid());
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

}
