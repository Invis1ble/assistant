<?php

namespace Tests\AppBundle\Controller;

/**
 * ExceptionControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group smoke
 */
class ExceptionControllerTest extends ApiTestCase
{
    public function testErrors()
    {
        $this->assertNotFound(
            $this->get('/api/unknown')
                ->getResponse()
        );
    }
}
