<?php

namespace Tests\AppBundle\Controller\Constraint;

use PHPUnit_Framework_Constraint;
use Symfony\Component\HttpFoundation\Response;

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
    protected $expectedContent;

    /**
     * ResponseStatusCodeIs constructor.
     *
     * @param string $expectedContent
     */
    public function __construct(string $expectedContent)
    {
        parent::__construct();

        $this->setExpectedContent($expectedContent);
    }

    /**
     * @param Response $response
     *
     * @return bool
     */
    public function matches($response)
    {
        return array_key_exists(
            $this->getExpectedContent(),
            json_decode($response->getContent(), true)
        );
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
        return 'contains "' . $this->getExpectedContent() . '"';
    }

    /**
     * @return string
     */
    public function getExpectedContent(): string
    {
        return $this->expectedContent;
    }

    /**
     * @param string $expectedContent
     *
     * @return ResponsePayloadContains
     */
    public function setExpectedContent(string $expectedContent): ResponsePayloadContains
    {
        $this->expectedContent = $expectedContent;

        return $this;
    }
}