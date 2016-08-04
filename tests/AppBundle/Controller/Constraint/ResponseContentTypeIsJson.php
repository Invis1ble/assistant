<?php

namespace Tests\AppBundle\Controller\Constraint;

use PHPUnit_Framework_Constraint;
use Symfony\Component\HttpFoundation\Response;

/**
 * ResponseContentTypeIsJson
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class ResponseContentTypeIsJson extends PHPUnit_Framework_Constraint
{
    /**
     * @param Response $response
     *
     * @return bool
     */
    public function matches($response)
    {
        return $response->headers->contains('Content-Type', 'application/json');
    }

    /**
     * @param Response $response
     *
     * @return string
     */
    protected function failureDescription($response)
    {
        $contentType = $response->headers->get('Content-Type');

        $description = 'Response ' . $this->toString() . ', ';
        $description .= ($contentType === null ? 'no header' : ('"' . $contentType . '"'));
        $description .= ' given';

        return $description;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'has header "Content-Type" and it\'s value is "application/json"';
    }
}