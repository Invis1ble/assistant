<?php

namespace Tests\AppBundle\Controller;

/**
 * TaskPeriodControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskPeriodControllerTest extends ApiTestCase
{
    use GetUserTaskTrait;

    public function testGetPeriods()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $aliceTask = $this->getUserTask($alice);
        $bobTask = $this->getUserTask($bob);

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
        $this->assertResponsePayloadContains('entities', $response);
    }

    public function testPostPeriod()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $aliceTask = $this->getUserTask($alice);
        $bobTask = $this->getUserTask($bob);

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
