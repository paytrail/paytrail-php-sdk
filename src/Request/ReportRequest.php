<?php
declare(strict_types=1);

namespace Paytrail\SDK\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\ObjectPropertyConverter;


class ReportRequest implements \JsonSerializable
{
    private $requestType;
    private $callbackUrl;
    private $paymentStatus;
    private $startDate;
    private $endDate;
    private $limit;
    private $reportFields;
    private $subMerchant;


    use ObjectPropertyConverter;

    public function validate()
    {
        $props = get_object_vars($this);

        if (empty($props['requestType'])) {
            throw new ValidationException('RequestType can not be empty');
        }

        if (empty($props['callbackUrl'])) {
            throw new ValidationException('CallbackUrl can not be empty');
        }

        if ($props['startDate']) {
            if ((new \DateTime())->createFromFormat('Y-m-d', $startDate) == false) {
                throw new ValidationException('startDate must be in Y-m-d format');
            }
        }

        if ($props['endDate']) {
            if ((new \DateTime())->createFromFormat('Y-m-d', $endDate) == false) {
                throw new ValidationException('startDate must be in Y-m-d format');
            }
        }

        if ($props['limit'] > 50000) {
            throw new ValidationException('Limit exceeds maximum value of 50000');
        }

        return true;
    }

    public function setRequestType(string $requestType): self
    {
        $this->requestType = $requestType;
        return $this;
    }

    public function setCallbackUrl(string $callbackUrl): self
    {
        $this->callbackUrl = $callbackUrl;
        return $this;
    }

    public function setPaymentStatus(string $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;
        return $this;
    }

    public function setStartDate(string $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function setEndDate(string $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function setReportFields(string $reportFields): self
    {
        $this->reportFields = $reportFields;
        return $this;
    }

    public function setSubMerchant(int $subMerchant): self
    {
        $this->subMerchant = $subMerchant;
        return $this;
    }

    public function jsonSerialize(): array
    {
        $dataArray = json_encode(get_object_vars($this));
        return array_filter(json_decode($dataArray, true));
    }
}
