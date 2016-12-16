<?php

namespace Tests\AppBundle\Controller\Constraint;

use PHPUnit_Framework_Constraint;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * ResponseItemEquals
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class ResponseItemEquals extends PHPUnit_Framework_Constraint
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var mixed
     */
    protected $expectedValue;

    /**
     * ResponseItemEquals constructor.
     *
     * @param string $path
     * @param mixed $expectedValue
     */
    public function __construct(string $path, $expectedValue)
    {
        parent::__construct();

        $this->setPath($path);
        $this->setExpectedValue($expectedValue);
    }

    /**
     * @param Response $response
     *
     * @return bool
     */
    public function matches($response)
    {
        try {
            return PropertyAccess::createPropertyAccessor()
                ->getValue(json_decode($response->getContent()), $this->getPath()) == $this->getExpectedValue();
        } catch (NoSuchPropertyException $exception) {
            return false;
        }
    }

    /**
     * @param Response $response
     *
     * @return string
     */
    protected function failureDescription($response)
    {
        return 'Response ' . $this->toString() . ', ' .
            $this->exporter->export(json_decode($response->getContent(), true)) . ' given';
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'item "' . $this->getPath() . '" equals to ' . $this->exporter->export($this->getExpectedValue());
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return ResponseItemEquals
     */
    public function setPath(string $path): ResponseItemEquals
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpectedValue()
    {
        return $this->expectedValue;
    }

    /**
     * @param mixed $expectedValue
     *
     * @return ResponseItemEquals
     */
    public function setExpectedValue($expectedValue): ResponseItemEquals
    {
        $this->expectedValue = $expectedValue;

        return $this;
    }
}