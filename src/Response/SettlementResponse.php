<?php

declare(strict_types=1);

namespace Paytrail\SDK\Response;

use Paytrail\SDK\Interfaces\ResponseInterface;

class SettlementResponse implements ResponseInterface
{
    private $settlements;

    public function setSettlements($settlements)
    {
        $this->settlements = $settlements;
        return $this;
    }

    public function getSettlements()
    {
        return $this->settlements;
    }
}
