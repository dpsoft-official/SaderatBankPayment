<?php
/**
 * Created by PhpStorm.
 * User: sadeghpm
 * Date: 2/21/18
 * Time: 6:52 PM
 */

namespace Dpsoft\Saderat;


class Request
{

    /**
     * @var string
     */
    const WSDL_REQUEST = "https://mabna.shaparak.ir/TokenService?wsdl";
    /**
     * @var string
     */
    const WSDL_VERIFY = "https://mabna.shaparak.ir/TransactionReference/TransactionReference?wsdl";
    /**
     * @var string
     */
    const SHAPARAK_WEB_PAGE = "https://mabna.shaparak.ir";
    /**
     * @var \SoapClient
     */
    private $client;

    public function __construct()
    {

    }

    public function reservation(array $params)
    {
        $result = $this->mabnaSoap()->reservation($params);
        return $result->return;
    }

    /**
     * @param string $url
     *
     * @return \SoapClient
     */
    private function mabnaSoap(string $url = null)
    {
        $this->client = $this->client ?? new \SoapClient($url ?? self::WSDL_REQUEST);
        return $this->client;
    }

    public function sendConfirmation(array $params)
    {
        $result = $this->mabnaSoap(self::WSDL_VERIFY)->sendConfirmation($params);
        return $result->return;
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
        $script
            = <<<'REDIRECT'
        <script>
            var form = document.createElement("form");
            form.setAttribute("action", "%s");
            form.setAttribute("method", "POST");
            form.setAttribute("target", "_self");

            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("name", "TOKEN");
            hiddenField.setAttribute("value", "%s");
            hiddenField.setAttribute("type", "hidden");

            form.appendChild(hiddenField);
            document.body.appendChild(form);
            
            form.submit();
        </script>
        
REDIRECT;
        return sprintf($script, self::SHAPARAK_WEB_PAGE, $token);
    }

    /**
     * @param \SoapClient $client
     *
     * @return Request
     */
    public function setClient(\SoapClient $client)
    {
        $this->client = $client;
        return $this;
    }
}