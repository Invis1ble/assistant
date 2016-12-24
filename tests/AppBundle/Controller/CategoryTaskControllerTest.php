<?php

namespace Tests\AppBundle\Controller;

/**
 * CategoryTaskControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group smoke
 */
class CategoryTaskControllerTest extends ApiTestCase
{
    use GetUserCategoryTrait;

    public function testGetTasks()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $aliceCategory = $this->getUserCategory($alice);
        $bobCategory = $this->getUserCategory($bob);

        $this->assertUnauthorized(
            $this->get('/api/categories/' . $uuid4 . '/tasks')
                ->getResponse()
        );

        $this->assertNotFound(
            $this->get('/api/categories/' . $uuid4 . '/tasks', $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->get('/api/categories/' . $bobCategory->getId() . '/tasks', $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $client = $this->get('/api/categories/' . $aliceCategory->getId() . '/tasks', $alice->getUsername(), 'alice_plain_password');

        $response = $client->getResponse();

        $this->assertOk($response);
        $this->assertResponsePayloadContains('entities', $response);
        $this->assertResponseItemEquals('offset', null, $response);

        $defaultNum = 10;

        $this->assertResponseItemEquals('limit', $defaultNum, $response);
        $this->assertResponseItemCount('entities', $defaultNum, $response);

        $offset = 1;
        $limit = 1;

        $client = $this->get(
            '/api/categories/' . $aliceCategory->getId() . '/tasks',
            $alice->getUsername(),
            'alice_plain_password',
            ['offset' => $offset, 'limit' => $limit]
        );

        $response = $client->getResponse();

        $this->assertOk($response);
        $this->assertResponsePayloadContains('entities', $response);
        $this->assertResponseItemEquals('offset', $offset, $response);
        $this->assertResponseItemEquals('limit', $limit, $response);
        $this->assertResponseItemCount('entities', $limit, $response);
    }

    public function testPostTask()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $aliceCategory = $this->getUserCategory($alice);
        $bobCategory = $this->getUserCategory($bob);

        $this->assertUnauthorized(
            $this->post('/api/categories/' . $uuid4 . '/tasks')
                ->getResponse()
        );

        $this->assertNotFound(
            $this->post('/api/categories/' . $uuid4 . '/tasks', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->post('/api/categories/' . $bobCategory->getId() . '/tasks', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertValidationFailed(
            $this->post('/api/categories/' . $aliceCategory->getId() . '/tasks', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertCreated(
            $this->post('/api/categories/' . $aliceCategory->getId() . '/tasks', [
                'title' => 'Alice\'s new task',
                'description' => 'Description of the task',
            ], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }
}
