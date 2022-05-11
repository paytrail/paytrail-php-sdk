<?php
declare(strict_types=1);

/**
 * Class Provider
 */

namespace Paytrail\SDK\Model;

use Paytrail\SDK\Util\PropertyBinder;
use Paytrail\SDK\Util\JsonSerializable;

/**
 * Class Provider
 *
 * The payment request will get a response object
 * containing an array of providers.
 *
 * @see https://paytrail.github.io/api-documentation/#/examples?id=response
 * @package Paytrail\SDK\Model
 */
class Provider implements \JsonSerializable
{

    use JsonSerializable;
    use PropertyBinder;

    /**
     * The provider redirect url.
     *
     * @var string
     */
    protected $url;

    /**
     * The provider icon url.
     *
     * @var string
     */
    protected $icon;

    /**
     * The provider svg url.
     *
     * @var string
     */
    protected $svg;

    /**
     * The provider name.
     *
     * @var string
     */
    protected $name;

    /**
     * The provider group.
     *
     * @var string
     */
    protected $group;

    /**
     * The provider id.
     *
     * @var string
     */
    protected $id;

    /**
     * The provider parameters.
     *
     * @var array
     */
    protected $parameters;

    /**
     * The getter for the url.
     *
     * @return string
     */
    public function getUrl() : ?string
    {

        return $this->url;
    }

    /**
     * The setter for the url.
     *
     * @param string $url
     * @return Provider Return self to enable chaining.
     */
    public function setUrl(?string $url) : Provider
    {
        $this->url = $url;

        return $this;
    }

    /**
     * The getter for the icon.
     *
     * @return string
     */
    public function getIcon() : ?string
    {

        return $this->icon;
    }

    /**
     * The setter for the icon.
     *
     * @param string $icon
     * @return Provider Return self to enable chaining.
     */
    public function setIcon(?string $icon) : Provider
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * The getter for the svg.
     *
     * @return string
     */
    public function getSvg() : ?string
    {

        return $this->svg;
    }

    /**
     * The setter for the svg.
     *
     * @param string $svg
     * @return Provider Return self to enable chaining.
     */
    public function setSvg(?string $svg) : Provider
    {
        $this->svg = $svg;

        return $this;
    }

    /**
     * The getter for the name.
     *
     * @return string
     */
    public function getName() : ?string
    {

        return $this->name;
    }

    /**
     * The setter for the name.
     *
     * @param string $name
     * @return Provider Return self to enable chaining.
     */
    public function setName(?string $name) : Provider
    {
        $this->name = $name;

        return $this;
    }

    /**
     * The getter for the group.
     *
     * @return string
     */
    public function getGroup() : ?string
    {

        return $this->group;
    }

    /**
     * The setter for the group.
     *
     * @param string $group
     * @return Provider Return self to enable chaining.
     */
    public function setGroup(?string $group) : Provider
    {
        $this->group = $group;

        return $this;
    }

    /**
     * The getter for the id.
     *
     * @return string
     */
    public function getId() : ?string
    {

        return $this->id;
    }

    /**
     * The setter for the id.
     *
     * @param string $id
     * @return Provider Return self to enable chaining.
     */
    public function setId(?string $id) : Provider
    {
        $this->id = $id;

        return $this;
    }

    /**
     * The getter for the parameters.
     *
     * @return array
     */
    public function getParameters() : ?array
    {

        return $this->parameters;
    }

    /**
     * The setter for the parameters.
     *
     * @param array $parameters
     * @return Provider Return self to enable chaining.
     */
    public function setParameters(?array $parameters) : Provider
    {
        $this->parameters = $parameters;

        return $this;
    }
}
