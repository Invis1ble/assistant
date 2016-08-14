<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;
use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\User;
use Tests\AppBundle\Controller\Constraint\{
    ResponseContentTypeIsJson,
    ResponseHasLocationHeader,
    ResponseStatusCodeIs,
    ResponsePayloadContains,
    ResponseItemEquals
};

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
     * @param string   $message
     */
    public static function assertResponseContentTypeIsJson(Response $response, string $message = '')
    {
        static::assertThat($response, static::responseContentTypeIsJson(), $message);
    }

    /**
     * @param Response $response
     * @param string   $message
     */
    public static function assertResponseHasLocationHeader(Response $response, string $message = '')
    {
        static::assertThat($response, static::responseHasLocationHeader(), $message);
    }

    /**
     * @param string   $expectedContentPath
     * @param Response $response
     * @param string   $message
     */
    public static function assertResponsePayloadContains(
        string $expectedContentPath,
        Response $response,
        string $message = ''
    )
    {
        static::assertThat($response, static::responsePayloadContains($expectedContentPath), $message);
    }

    /**
     * @param string   $path
     * @param mixed    $expectedValue
     * @param Response $response
     * @param string   $message
     */
    public static function assertResponseItemEquals(
        string $path,
        $expectedValue,
        Response $response,
        string $message = ''
    )
    {
        static::assertThat($response, static::responseItemEquals($path, $expectedValue), $message);
    }

    /**
     * @param Response $response
     * @param string   $message
     */
    public static function assertOk(Response $response, string $message = '')
    {
        static::assertThat(
            $response,
            static::logicalAnd(
                static::responseStatusCodeIs(Response::HTTP_OK),
                static::responseContentTypeIsJson()
            ),
            $message
        );
    }

    /**
     * @param Response $response
     * @param string   $message
     */
    public static function assertCreated(Response $response, string $message = '')
    {
        static::assertThat(
            $response,
            static::logicalAnd(
                static::responseStatusCodeIs(Response::HTTP_CREATED),
                static::responseContentTypeIsJson(),
                static::responseHasLocationHeader()
            ),
            $message
        );
    }

    /**
     * @param Response $response
     * @param string   $message
     */
    public static function assertPatched(Response $response, string $message = '')
    {
        static::assertThat(
            $response,
            static::logicalAnd(
                static::responseStatusCodeIs(Response::HTTP_NO_CONTENT),
                static::responseHasLocationHeader()
            ),
            $message
        );
    }

    /**
     * @param Response $response
     * @param string   $message
     */
    public static function assertValidationFailed(Response $response, string $message = '')
    {
        static::assertThat(
            $response,
            static::logicalAnd(
                static::responseStatusCodeIs(Response::HTTP_BAD_REQUEST),
                static::responseContentTypeIsJson(),
                static::responsePayloadContains('errors')
            ),
            $message
        );
    }

    /**
     * @param Response $response
     * @param string   $message
     */
    public static function assertUnauthorized(Response $response, string $message = '')
    {
        static::assertThat(
            $response,
            static::logicalAnd(
                static::responseStatusCodeIs(Response::HTTP_UNAUTHORIZED),
                static::responseContentTypeIsJson()
            ),
            $message
        );
    }

    /**
     * @param Response $response
     * @param string   $message
     */
    public static function assertForbidden(Response $response, string $message = '')
    {
        static::assertThat(
            $response,
            static::logicalAnd(
                static::responseStatusCodeIs(Response::HTTP_FORBIDDEN),
                static::responseContentTypeIsJson()
            ),
            $message
        );
    }

    /**
     * @param Response $response
     * @param string   $message
     */
    public static function assertNotFound(Response $response, string $message = '')
    {
        static::assertThat(
            $response,
            static::logicalAnd(
                static::responseStatusCodeIs(Response::HTTP_NOT_FOUND),
                static::responseContentTypeIsJson()
            ),
            $message
        );
    }

    /**
     * @return ResponseContentTypeIsJson
     */
    public static function responseContentTypeIsJson(): ResponseContentTypeIsJson
    {
        return new ResponseContentTypeIsJson();
    }

    /**
     * @return ResponseHasLocationHeader
     */
    public static function responseHasLocationHeader(): ResponseHasLocationHeader
    {
        return new ResponseHasLocationHeader();
    }

    /**
     * @param int $expectedStatusCode
     *
     * @return ResponseStatusCodeIs
     */
    public static function responseStatusCodeIs(int $expectedStatusCode): ResponseStatusCodeIs
    {
        return new ResponseStatusCodeIs($expectedStatusCode);
    }

    /**
     * @param string $expectedContentPath
     *
     * @return ResponsePayloadContains
     */
    public static function responsePayloadContains(string $expectedContentPath): ResponsePayloadContains
    {
        return new ResponsePayloadContains($expectedContentPath);
    }

    /**
     * @param string $path
     * @param mixed  $expectedValue
     *
     * @return ResponseItemEquals
     */
    public static function responseItemEquals(string $path, $expectedValue): ResponseItemEquals
    {
        return new ResponseItemEquals($path, $expectedValue);
    }
}
