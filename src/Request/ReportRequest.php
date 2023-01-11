<?php

declare(strict_types=1);

namespace Paytrail\SDK\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\ObjectPropertyConverter;

class ReportRequest implements \JsonSerializable
{
    use ObjectPropertyConverter;

    public const PAYMENT_STATUSES = [
        'default',
        'paid',
        'all',
        'settled'
    ];

    private $requestType;
    private $callbackUrl;
    private $paymentStatus;
    private $startDate;
    private $endDate;
    private $limit;
    private $reportFields;
    private $subMerchant;

    public function validate()
    {
        $props = get_object_vars($this);

        if (empty($props['requestType'])) {
            throw new ValidationException('RequestType can not be empty');
        }

        if (!in_array($props['requestType'], ['json', 'csv'])) {
            throw new ValidationException('RequestType must be either "json" or "csv"');
        }

        if (empty($props['callbackUrl'])) {
            throw new ValidationException('CallbackUrl can not be empty');
        }

        if ($props['paymentStatus'] && !in_array($props['paymentStatus'], self::PAYMENT_STATUSES)) {
            throw new ValidationException('Invalid paymentStatus value');
        }

        if ($props['limit'] > 50000) {
            throw new ValidationException('Limit exceeds maximum value of 50000');
        }

        if ($props['limit'] < 0) {
            throw new ValidationException('Limit must have a minimum value of 0');
        }

        if ($props['reportFields'] && !is_array($props['reportFields'])) {
            throw new ValidationException('ReportFields must be type of array');
        }

        return true;
    }

    /**
     * Set request type.
     *
     * @param string $requestType
     * @return $this
     */
    public function setRequestType(string $requestType): self
    {
        $this->requestType = $requestType;
        return $this;
    }

    /**
     * Set callback url.
     *
     * @param string $callbackUrl
     * @return $this
     */
    public function setCallbackUrl(string $callbackUrl): self
    {
        $this->callbackUrl = $callbackUrl;
        return $this;
    }

    /**
     * Set payment statuses.
     *
     * @param string $paymentStatus
     * @return $this
     */
    public function setPaymentStatus(string $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;
        return $this;
    }

    /**
     * Set start date.
     *
     * @param string $startDate Start date as ISO format.
     * @return $this
     */
    public function setStartDate(string $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * Set end date.
     *
     * @param string $endDate End date as ISO format.
     * @return $this
     */
    public function setEndDate(string $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Set limit.
     *
     * @param int $limit
     * @return $this
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Set report fields.
     *
     * @param string[] $reportFields
     * @return $this
     */
    public function setReportFields(array $reportFields): self
    {
        $this->reportFields = $reportFields;
        return $this;
    }

    /**
     * Set submerchant.
     *
     * @param int $subMerchant
     * @return $this
     */
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
