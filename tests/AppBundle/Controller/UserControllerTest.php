<?php

namespace Tests\AppBundle\Controller;

/**
 * UserControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserControllerTest extends ApiTestCase
{
    public function testPostUser()
    {
        $this->assertValidationFailed(
            $this->post('/api/users')->getResponse()
        );

        $this->assertCreated(
            $this->post('/api/users', [
                'username' => 'dave',
                'plainPassword' => [
                    'first' => 'alice_plain_password',
                    'second' => 'alice_plain_password',
                ],
            ])
                ->getResponse()
        );
    }

    public function testGetUser()
    {
        $user = $this->getUser('alice');

        $this->assertUnauthorized(
            $this->get('/api/users/' . $user->getId())->getResponse()
        );

        $this->assertOk(
            $this->get('/api/users/' . $user->getId(), $user->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }
}
