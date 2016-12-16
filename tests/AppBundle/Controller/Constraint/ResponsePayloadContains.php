<?php

namespace Tests\AppBundle\Controller\Constraint;

use PHPUnit_Framework_Constraint;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * ResponsePayloadContains
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class ResponsePayloadContains extends PHPUnit_Framework_Constraint
{
    /**
     * @var string
     */
    protected $expectedContentPath;

    /**
     * ResponseStatusCodeIs constructor.
     *
     * @param string $expectedContentPath
     */
    public function __construct(string $expectedContentPath)
    {
        parent::__construct();

        $this->setExpectedContentPath($expectedContentPath);
    }

    /**
     * @param Response $response
     *
     * @return bool
     */
    public function matches($response)
    {
        try {
            PropertyAccess::createPropertyAccessor()
                ->getValue(json_decode($response->getContent()), $this->getExpectedContentPath());

            return true;
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
        return 'contains "' . $this->getExpectedContentPath() . '"';
    }

    /**
     * @return string
     */
    public function getExpectedContentPath(): string
    {
        return $this->expectedContentPath;
    }

    /**
     * @param string $expectedContentPath
     *
     * @return ResponsePayloadContains
     */
    public function setExpectedContentPath(string $expectedContentPath): ResponsePayloadContains
    {
        $this->expectedContentPath = $expectedContentPath;

        return $this;
    }
}