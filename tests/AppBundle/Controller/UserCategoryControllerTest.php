<?php

namespace Tests\AppBundle\Controller;

/**
 * UserCategoryControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group smoke
 */
class UserCategoryControllerTest extends ApiTestCase
{
    public function testGetCategories()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $this->assertUnauthorized(
            $this->get('/api/users/' . $uuid4 . '/categories')
                ->getResponse()
        );

        $this->assertNotFound(
            $this->get('/api/users/' . $uuid4 . '/categories', $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->get('/api/users/' . $bob->getId() . '/categories', $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $client = $this->get('/api/users/' . $alice->getId() . '/categories', $alice->getUsername(), 'alice_plain_password');

        $response = $client->getResponse();

        $this->assertOk($response);
        $this->assertResponsePayloadContains('entities', $response);
    }

    public function testPostCategory()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $this->assertUnauthorized(
            $this->post('/api/users/' . $uuid4 . '/categories')
                ->getResponse()
        );

        $this->assertNotFound(
            $this->post('/api/users/' . $uuid4 . '/categories', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->post('/api/users/' . $bob->getId() . '/categories', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertValidationFailed(
            $this->post('/api/users/' . $alice->getId() . '/categories', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertCreated(
            $this->post('/api/users/' . $alice->getId() . '/categories', [
                'name' => 'Alice\'s new category',
                'description' => 'Description of the category',
            ], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }
}
