<?php

namespace Tests\AppBundle\Controller;

/**
 * UserTaskControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserTaskControllerTest extends ApiTestCase
{
    public function testGetTasks()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $this->assertUnauthorized(
            $this->get('/api/users/' . $uuid4 . '/tasks')
                ->getResponse()
        );

        $this->assertNotFound(
            $this->get('/api/users/' . $uuid4 . '/tasks', $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->get('/api/users/' . $bob->getId() . '/tasks', $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $client = $this->get('/api/users/' . $alice->getId() . '/tasks', $alice->getUsername(), 'alice_plain_password');

        $response = $client->getResponse();

        $this->assertOk($response);
        $this->assertResponseContainsEntities($response);
    }

    public function testPostTask()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $this->assertUnauthorized(
            $this->post('/api/users/' . $uuid4 . '/tasks')
                ->getResponse()
        );

        $this->assertNotFound(
            $this->post('/api/users/' . $uuid4 . '/tasks', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->post('/api/users/' . $bob->getId() . '/tasks', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertValidationFailed(
            $this->post('/api/users/' . $alice->getId() . '/tasks', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertCreated(
            $this->post('/api/users/' . $alice->getId() . '/tasks', [
                'title' => 'Alice\'s new task',
            ], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }
}
