<?php

namespace Tests\AppBundle\Controller;

/**
 * TaskControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group smoke
 */
class TaskControllerTest extends ApiTestCase
{
    use GetUserTaskTrait;

    public function testGetTask()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $aliceTask = $this->getUserTask($alice);
        $bobTask = $this->getUserTask($bob);

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

    public function testPatchTask()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $aliceTask = $this->getUserTask($alice);
        $bobTask = $this->getUserTask($bob);

        $this->assertUnauthorized(
            $this->patch('/api/tasks/' . $uuid4)
                ->getResponse()
        );

        $this->assertNotFound(
            $this->patch('/api/tasks/' . $uuid4, [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->patch('/api/tasks/' . $bobTask->getId(), [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertValidationFailed(
            $this->patch('/api/tasks/' . $aliceTask->getId(), [
                'title' => '',
            ], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertPatched(
            $this->patch('/api/tasks/' . $aliceTask->getId(), [
                'title' => 'Updated title',
            ], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }
}
