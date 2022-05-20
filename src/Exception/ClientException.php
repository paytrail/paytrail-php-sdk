<?php
declare(strict_types=1);

namespace Paytrail\SDK\Exception;

class ClientException extends \Exception
{
    private $responseBody;
    private $responseCode;

    public function SetResponseBody($responseBody): void
    {
        $this->responseBody = $responseBody;
    }

    public function getResponseBody()
    {
        return $this->responseBody;
    }

    public function setResponseCode($code)
    {
        $this->responseCode = $code;
    }

    public function getResponseCode()
    {
        return $this->responseCode;
    }
}
