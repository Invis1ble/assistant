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

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $aliceTask = $alice->getTasks()
            ->get(0);
        /* @var $aliceTask Task */

        $bobTask = $bob->getTasks()
            ->get(0);
        /* @var $bobTask Task */

        $this->assertUnauthorized(
            $this->get('/api/tasks/' . $uuid4 . '/periods')
                ->getResponse()
        );

        $this->assertNotFound(
            $this->get('/api/tasks/' . $uuid4 . '/periods', $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->get('/api/tasks/' . $bobTask->getId() . '/periods', $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $response = $this->get('/api/tasks/' . $aliceTask->getId() . '/periods', $alice->getUsername(), 'alice_plain_password')
            ->getResponse();

        $this->assertOk($response);
        $this->assertResponseContainsEntities($response);
    }

    public function testPostPeriod()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $aliceTask = $alice->getTasks()
            ->get(0);
        /* @var $aliceTask Task */

        $bobTask = $bob->getTasks()
            ->get(0);
        /* @var $bobTask Task */

        $this->assertUnauthorized(
            $this->post('/api/tasks/' . $uuid4 . '/periods')
                ->getResponse()
        );

        $this->assertNotFound(
            $this->post('/api/tasks/' . $uuid4 . '/periods', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->post('/api/tasks/' . $bobTask->getId() . '/periods', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertValidationFailed(
            $this->post('/api/tasks/' . $aliceTask->getId() . '/periods', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertCreated(
            $this->post('/api/tasks/' . $aliceTask->getId() . '/periods', [
                'startedAt' => time(),
            ], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }
}
