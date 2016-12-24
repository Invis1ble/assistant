<?php

namespace Tests\AppBundle\Controller;

/**
 * CategoryControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group smoke
 */
class CategoryControllerTest extends ApiTestCase
{
    use GetUserCategoryTrait;

    public function testGetCategory()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $aliceCategory = $this->getUserCategory($alice);
        $bobCategory = $this->getUserCategory($bob);

        $this->assertUnauthorized(
            $this->get('/api/categories/' . $uuid4)
                ->getResponse()
        );

        $this->assertNotFound(
            $this->get('/api/categories/' . $uuid4, $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->get('/api/categories/' . $bobCategory->getId(), $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertOk(
            $this->get('/api/categories/' . $aliceCategory->getId(), $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }

    public function testPatchCategory()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $aliceCategory = $this->getUserCategory($alice);
        $bobCategory = $this->getUserCategory($bob);

        $this->assertUnauthorized(
            $this->patch('/api/categories/' . $uuid4)
                ->getResponse()
        );

        $this->assertNotFound(
            $this->patch('/api/categories/' . $uuid4, [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->patch('/api/categories/' . $bobCategory->getId(), [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertValidationFailed(
            $this->patch('/api/categories/' . $aliceCategory->getId(), [
                'name' => '',
            ], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertPatched(
            $this->patch('/api/categories/' . $aliceCategory->getId(), [
                'name' => 'Updated name',
            ], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }

    public function testDeleteCategory()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $aliceCategory = $this->getUserCategory($alice);
        $bobCategory = $this->getUserCategory($bob);

        $this->assertUnauthorized(
            $this->delete('/api/categories/' . $uuid4)
                ->getResponse()
        );

        $this->assertNotFound(
            $this->delete('/api/categories/' . $uuid4, [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->delete('/api/categories/' . $bobCategory->getId(), [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertDeleted(
            $this->delete('/api/categories/' . $aliceCategory->getId(), [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }
}
