<?php

namespace Tests\AppBundle\Controller;

/**
 * TokenControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TokenControllerTest extends ApiTestCase
{
    public function testPostToken()
    {
        $user = $this->getUser('alice');

        $this->assertUnauthorized(
            $this->post('/api/tokens', [
                'username' => $user->getUsername(),
                'password' => 'invalid_password',
            ])
                ->getResponse()
        );

        $response = $this->post('/api/tokens', [
            'username' => $user->getUsername(),
            'password' => 'alice_plain_password',
        ])
            ->getResponse();

        $this->assertOk($response);
        $this->assertResponseContainsToken($response);
    }
}
