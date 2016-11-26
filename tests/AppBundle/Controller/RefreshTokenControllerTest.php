<?php

namespace Tests\AppBundle\Controller;

/**
 * RefreshTokenControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group smoke
 */
class RefreshTokenControllerTest extends ApiTestCase
{
    public function testPostRefreshToken()
    {
        $alice = $this->getUser('alice');

        $this->assertUnauthorized(
            $this->post('/api/refresh-tokens', [
                'refresh_token' => 'invalid_refresh_token',
            ])
                ->getResponse()
        );

        $response = $this
            ->request('POST', '/api/refresh-tokens', [], [], [], json_encode([
                'refresh_token' => $this->createToken($alice->getUsername(), 'alice_plain_password')
                    ->getRefreshToken(),
            ]))
            ->getResponse()
        ;

        $this->assertOk($response);
        $this->assertResponsePayloadContains('token', $response);
        $this->assertResponsePayloadContains('refresh_token', $response);
    }
}
