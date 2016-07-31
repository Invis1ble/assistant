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
        $client = $this->post('/api/users');

        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContentTypeIsJson($response);

        $client = $this->post('/api/users', [
            'username' => 'bob',
            'plainPassword' => [
                'first' => '111111',
                'second' => '111111',
            ],
        ]);

        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertContentTypeIsJson($response);
        $this->assertHasLocation($response);
    }

    public function testGetUser()
    {
        $plainPassword = '111111';
        $user = $this->createUser('alice', $plainPassword);

        $client = $this->get('/api/users/' . $user->getId());

        $response = $client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertContentTypeIsJson($response);

        $client = $this->get('/api/users/' . $user->getId(), $user->getUsername(), $plainPassword);

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContentTypeIsJson($response);
    }
}
