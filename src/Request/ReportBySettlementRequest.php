<?php

declare(strict_types=1);

namespace Paytrail\SDK\Request;

use Paytrail\SDK\Exception\ValidationException;

class ReportBySettlementRequest implements \JsonSerializable
{
    protected $requestType;
    protected $callbackUrl;
    protected $reportFields;
    protected $subMerchant;

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
