<?php

namespace Tests\AppBundle\Controller;

/**
 * TokenControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group smoke
 */
class TokenControllerTest extends ApiTestCase
{
    public function testPostToken()
    {
        $alice = $this->getUser('alice');

        $this->assertUnauthorized(
            $this->post('/api/tokens', [
                'username' => $alice->getUsername(),
                'password' => 'invalid_password',
            ])
                ->getResponse()
        );

        $response = $this->post('/api/tokens', [
            'username' => $alice->getUsername(),
            'password' => 'alice_plain_password',
        ])
            ->getResponse();

        $this->assertOk($response);
        $this->assertResponsePayloadContains('token', $response);
        $this->assertResponseItemEquals('data.id', $alice->getId()->toString(), $response);
    }
}
