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
    public function testGetTask()
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
            $this->get('/api/tasks/' . $uuid4)
                ->getResponse()
        );

        $this->assertNotFound(
            $this->get('/api/tasks/' . $uuid4, $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->get('/api/tasks/' . $bobTask->getId(), $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertOk(
            $this->get('/api/tasks/' . $aliceTask->getId(), $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }
}
