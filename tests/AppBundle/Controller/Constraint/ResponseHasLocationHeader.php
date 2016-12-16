<?php

namespace Tests\AppBundle\Controller\Constraint;

use PHPUnit_Framework_Constraint;
use Symfony\Component\HttpFoundation\Response;

/**
 * ResponseHasLocationHeader
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class ResponseHasLocationHeader extends PHPUnit_Framework_Constraint
{
    /**
     * @param Response $response
     *
     * @return bool
     */
    public function matches($response)
    {
        return $response->headers->has('Location');
    }

    /**
     * @param Response $response
     *
     * @return string
     */
    protected function failureDescription($response)
    {
        return 'Response ' . $this->toString();
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'has header "Location"';
    }
}