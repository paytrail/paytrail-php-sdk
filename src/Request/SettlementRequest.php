<?php

declare(strict_types=1);

namespace Paytrail\SDK\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\ObjectPropertyConverter;

class SettlementRequest implements \JsonSerializable
{
    use ObjectPropertyConverter;

    protected $startDate;
    protected $endDate;
    protected $reference;
    protected $limit;
    protected $subMerchant;

    public function validate()
    {
        $props = get_object_vars($this);

        if (!empty($props['startDate'])) {
            if (!(new \DateTime())->createFromFormat('Y-m-d', $props['startDate'])) {
                throw new ValidationException('startDate must be in Y-m-d format');
            }
        }

        if (!empty($props['endDate'])) {
            if (!(new \DateTime())->createFromFormat('Y-m-d', $props['endDate'])) {
                throw new ValidationException('endDate must be in Y-m-d format');
            }
        }

        if (!empty($props['startDate']) && !empty($props['endDate'])) {
            if (
                (new \DateTime())->createFromFormat('Y-m-d', $props['startDate'])
                > (new \DateTime())->createFromFormat('Y-m-d', $props['endDate'])
            ) {
                throw new ValidationException('startDate cannot be lower than endDate');
            }
        }

        if ($props['limit'] < 0) {
            throw new ValidationException('Limit must have a minimum value of 0');
        }

        return true;
    }

    /**
     * Set start date.
     *
     * @param string|null $startDate Start date as ISO format.
     * @return $this
     */
    public function setStartDate(?string $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * Set end date.
     *
     * @param string|null $endDate End date as ISO format.
     * @return $this
     */
    public function setEndDate(?string $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Set the reference.
     *
     * @param string $reference
     *
     * @return $this
     */
    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

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
