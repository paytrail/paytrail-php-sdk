<?php
declare(strict_types=1);

/**
 * Class PaymentMethodGroup
 */

namespace Paytrail\SDK\Model;

use Paytrail\SDK\Util\PropertyBinder;
use Paytrail\SDK\Util\JsonSerializable;

/**
 * Class PaymentMethodGroup
 *
 * @see https://docs.paytrail.com/#/?id=paymentmethodgroup
 * @package Paytrail\SDK\Model
 */
class PaymentMethodGroup implements \JsonSerializable
{

    use JsonSerializable;
    use PropertyBinder;

    /**
     * Payment method group id.
     *
     * @var string|null
     */
    protected $id;

    /**
     * Payment method group name.
     *
     * @var string|null
     */
    protected $name;

    /**
     * Payment method group icon url.
     *
     * @var string|null
     */
    protected $icon;

    /**
     * Payment method group svg url.
     *
     * @var string|null
     */
    protected $svg;

    /**
     * Get payment method group id.
     *
     * @return string
     */
    public function getId() : ?string
    {
        return $this->id;
    }

    /**
     * Set payment method group id.
     *
     * @param string|null $id
     * @return self
     */
    public function setId(?string $id) : self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get payment method group name.
     *
     * @return string|null
     */
    public function getName() : ?string
    {
        return $this->name;
    }

    /**
     * Set payment method group name.
     *
     * @param string|null $name
     * @return self
     */
    public function setName(?string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get payment method group svg url.
     *
     * @return string|null
     */
    public function getSvg() : ?string
    {
        return $this->svg;
    }

    /**
     * Set payment method group svg url.
     *
     * @param string|null $svg
     * @return self
     */
    public function setSvg(?string $svg) : self
    {
        $this->svg = $svg;

        return $this;
    }

    /**
     * Get payment method group icon url.
     *
     * @return string|null
     */
    public function getIcon() : ?string
    {
        return $this->icon;
    }

    /**
     * Set payment method group icon url.
     *
     * @param string|null $icon
     * @return self
     */
    public function setIcon(?string $icon) : self
    {
        $this->icon = $icon;

        return $this;
    }
}
