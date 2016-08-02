<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;
use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\User;

/**
 * ApiTestCase
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
abstract class ApiTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @param string $username
     *
     * @return User
     */
    protected function getUser(string $username): User
    {
        return $this->getRepository('AppBundle:User')
            ->findOneBy(['username' => $username]);
    }

    /**
     * @param string      $uri
     * @param string|null $username
     * @param string|null $password
     *
     * @return Client
     */
    protected function get(string $uri, string $username = null, string $password = null): Client
    {
        return $this->request('GET', $uri, [], [], [], null, true, $username, $password);
    }

    /**
     * @param string      $uri
     * @param array       $data
     * @param string|null $username
     * @param string|null $password
     *
     * @return Client
     */
    protected function post(string $uri, array $data = [], string $username = null, string $password = null): Client
    {
        return $this->request('POST', $uri, [], [], [], json_encode($data), true, $username, $password);
    }

    /**
     * @param string      $uri
     * @param array       $data
     * @param string|null $username
     * @param string|null $password
     *
     * @return Client
     */
    protected function patch(string $uri, array $data = [], string $username = null, string $password = null): Client
    {
        return $this->request('PATCH', $uri, [], [], [], json_encode($data), true, $username, $password);
    }

    /**
     * @param string      $method
     * @param string      $uri
     * @param array       $parameters
     * @param array       $files
     * @param array       $server
     * @param string|null $content
     * @param bool        $changeHistory
     * @param string|null $username
     * @param string|null $password
     *
     * @return Client
     */
    protected function request(
        string $method,
        string $uri,
        array $parameters = [],
        array $files = [],
        array $server = [],
        string $content = null,
        bool $changeHistory = true,
        string $username = null,
        string $password = null
    ): Client
    {
        if (null !== $username && null !== $password) {
            $response = $this->post('/api/tokens', [
                'username' => $username,
                'password' => $password,
            ])->getResponse();

            if (200 === $response->getStatusCode()) {
                $data = json_decode($response->getContent(), true);

                if (isset($data['token'])) {
                    $server['HTTP_Authorization'] = 'Bearer ' . $data['token'];
                }
            }
        }

        $this->client->request($method, $uri, $parameters, $files, array_merge([
            'ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], $server), $content, $changeHistory);

        return $this->client;
    }

    /**
     * @param string $id
     *
     * @return object
     */
    protected function getService(string $id)
    {
        return static::$kernel->getContainer()->get($id);
    }

    /**
     * @param string $persistentObject
     *
     * @return EntityRepository
     */
    protected function getRepository(string $persistentObject): EntityRepository
    {
        return $this->getService('doctrine')
            ->getRepository($persistentObject);
    }

    /**
     * @return string UUID v4 stub
     */
    protected function getUUID4stub(): string
    {
        return '00000000-0000-4000-a000-000000000000';
    }

    /**
     * @param Response $response
     */
    protected function assertResponseHasLocationHeader(Response $response)
    {
        $this->assertTrue(
            $response->headers->has('Location'),
            'Failed asserting that "Location" header is present.'
        );
    }

    /**
     * @param Response $response
     */
    protected function assertResponseContentTypeIsJson(Response $response)
    {
        $contentType = $response->headers->get('Content-Type');

        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            'Failed asserting that "Content-Type" header is "application/json", ' .
            ($contentType === null ? 'none' : ('"' . $response->headers->get('Content-Type') . '"')) . ' given.'
        );
    }

    /**
     * @param Response $response
     */
    protected function assertResponseContainsEntities(Response $response)
    {
        $this->assertObjectHasAttribute(
            'entities',
            json_decode($response->getContent()),
            'Failed asserting that response contains "entities".'
        );
    }

    /**
     * @param Response $response
     */
    protected function assertOk(Response $response)
    {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertResponseContentTypeIsJson($response);
    }

    /**
     * @param Response $response
     */
    protected function assertCreated(Response $response)
    {
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertResponseContentTypeIsJson($response);
        $this->assertResponseHasLocationHeader($response);
    }

    /**
     * @param Response $response
     */
    protected function assertValidationFailed(Response $response)
    {
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertResponseContentTypeIsJson($response);

        $this->assertObjectHasAttribute(
            'errors',
            json_decode($response->getContent()),
            'Failed asserting that response contains "errors".'
        );
    }

    /**
     * @param Response $response
     */
    protected function assertUnauthorized(Response $response)
    {
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertResponseContentTypeIsJson($response);
    }

    /**
     * @param Response $response
     */
    protected function assertForbidden(Response $response)
    {
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertResponseContentTypeIsJson($response);
    }

    /**
     * @param Response $response
     */
    protected function assertNotFound(Response $response)
    {
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertResponseContentTypeIsJson($response);
    }
}
