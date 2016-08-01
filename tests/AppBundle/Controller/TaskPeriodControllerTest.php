<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;

/**
 * TaskPeriodControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskPeriodControllerTest extends ApiTestCase
{
    public function testGetPeriods()
    {
        $uuid4 = $this->getUUID4stub();

        $this->assertUnauthorized(
            $this->get('/api/tasks/' . $uuid4 . '/periods')
                ->getResponse()
        );

        $user = $this->getUser('alice');

        $this->assertNotFound(
            $this->get('/api/tasks/' . $uuid4 . '/periods', $user->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $task = $user->getTasks()
            ->get(0);
        /* @var $task Task */

        $response = $this->get('/api/tasks/' . $task->getId() . '/periods', $user->getUsername(), 'alice_plain_password')
            ->getResponse();

        $this->assertOk($response);
        $this->assertResponseContainsEntities($response);
    }

    public function testPostPeriod()
    {
        $uuid4 = $this->getUUID4stub();

        $this->assertUnauthorized(
            $this->post('/api/tasks/' . $uuid4 . '/periods')
                ->getResponse()
        );

        $user = $this->getUser('alice');

        $this->assertNotFound(
            $this->post('/api/tasks/' . $uuid4 . '/periods', [], $user->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $task = $user->getTasks()
            ->get(0);
        /* @var $task Task */

        $this->assertValidationFailed(
            $this->post('/api/tasks/' . $task->getId() . '/periods', [], $user->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertCreated(
            $this->post('/api/tasks/' . $task->getId() . '/periods', [
                'startedAt' => time(),
            ], $user->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }
}
