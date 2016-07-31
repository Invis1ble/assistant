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
        $plainPassword = '111111';
        $user = $this->createUser('alice', $plainPassword);

        $client = $this->post('/api/tokens', [
            'username' => $user->getUsername(),
            'password' => '222222',
        ]);

        $response = $client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertContentTypeIsJson($response);

        $client = $this->post('/api/tokens', [
            'username' => $user->getUsername(),
            'password' => $plainPassword,
        ]);

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContentTypeIsJson($response);
        $this->assertObjectHasAttribute('token', json_decode($response->getContent()));
    }
}
