<?php

namespace Tests\AppBundle\Controller\Constraint;

use PHPUnit_Framework_Constraint;
use Symfony\Component\HttpFoundation\Response;

/**
 * ResponseStatusCodeIs
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class ResponseStatusCodeIs extends PHPUnit_Framework_Constraint
{
    /**
     * @var int
     */
    protected $expectedStatusCode;

    /**
     * ResponseStatusCodeIs constructor.
     *
     * @param int $expectedStatusCode
     */
    public function __construct(int $expectedStatusCode)
    {
        parent::__construct();

        $this->setExpectedStatusCode($expectedStatusCode);
    }

    /**
     * @param Response $response
     *
     * @return bool
     */
    public function matches($response)
    {
        return $response->getStatusCode() === $this->getExpectedStatusCode();
    }

    /**
     * @param Response $response
     *
     * @return string
     */
    protected function failureDescription($response)
    {
        return 'Response ' . $this->toString() . ', ' . $response->getStatusCode() . ' given';
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'status code is ' . $this->getExpectedStatusCode();
    }

    /**
     * @return int
     */
    public function getExpectedStatusCode(): int
    {
        return $this->expectedStatusCode;
    }

    /**
     * @param int $expectedStatusCode
     *
     * @return ResponseStatusCodeIs
     */
    public function setExpectedStatusCode(int $expectedStatusCode): ResponseStatusCodeIs
    {
        $this->expectedStatusCode = $expectedStatusCode;

        return $this;
    }
}