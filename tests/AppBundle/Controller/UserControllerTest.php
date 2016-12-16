<?php

namespace Tests\AppBundle\Controller;

/**
 * UserControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group smoke
 */
class UserControllerTest extends ApiTestCase
{
    public function testPostUser()
    {
        $this->assertValidationFailed(
            $this->post('/api/users')
                ->getResponse()
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
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $this->assertUnauthorized(
            $this->get('/api/users/' . $uuid4)
                ->getResponse()
        );

        $this->assertNotFound(
            $this->get('/api/users/' . $uuid4, $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->get('/api/users/' . $bob->getId(), $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertOk(
            $this->get('/api/users/' . $alice->getId(), $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }
}
