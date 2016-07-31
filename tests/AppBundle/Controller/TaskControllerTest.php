<?php

namespace Tests\AppBundle\Controller;

/**
 * TaskControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskControllerTest extends ApiTestCase
{
    public function testPostTask()
    {
        $client = $this->post('/api/tasks');

        $response = $client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertContentTypeIsJson($response);

        $plainPassword = '111111';
        $user = $this->createUser('alice', $plainPassword);

        $client = $this->post('/api/tasks', [], $user->getUsername(), $plainPassword);

        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContentTypeIsJson($response);

        $client = $this->post('/api/tasks', [
            'title' => 'Test title',
        ], $user->getUsername(), $plainPassword);

        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertContentTypeIsJson($response);
        $this->assertHasLocation($response);
    }
}
