<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;

/**
 * TaskControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskControllerTest extends ApiTestCase
{
    public function testGetTasks()
    {
        $this->assertUnauthorized(
            $this->get('/api/tasks')
                ->getResponse()
        );

        $user = $this->getUser('alice');

        $client = $this->get('/api/tasks', $user->getUsername(), 'alice_plain_password');

        $response = $client->getResponse();
        $this->assertOk($response);
        $this->assertResponseContainsEntities($response);
    }

    public function testGetTask()
    {
        $uuid4 = $this->getUUID4stub();

        $this->assertUnauthorized(
            $this->get('/api/tasks/' . $uuid4)
                ->getResponse()
        );

        $user = $this->getUser('alice');

        $this->assertNotFound(
            $this->get('/api/tasks/' . $uuid4, $user->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $task = $user->getTasks()
            ->get(0);
        /* @var $task Task */

        $this->assertOk(
            $this->get('/api/tasks/' . $task->getId(), $user->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }

    public function testPostTask()
    {
        $this->assertUnauthorized(
            $this->post('/api/tasks')
                ->getResponse()
        );

        $user = $this->getUser('alice');

        $this->assertValidationFailed(
            $this->post('/api/tasks', [], $user->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertCreated(
            $this->post('/api/tasks', [
                'title' => 'Alice\'s new task',
            ], $user->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }
}
