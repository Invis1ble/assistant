<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

use AppBundle\Entity\User;
use AppBundle\Manager\UserManager;

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
        $this->purgeDatabase();
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return User
     */
    protected function createUser(string $username, string $password): User
    {
        $userManager = $this->getService('app.manager.user_manager');
        /* @var $userManager UserManager */

        $user = $userManager->createUser();

        $user->setUsername($username);
        $user->setPlainPassword($password);

        $userManager->save($user);

        return $user;
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

    protected function purgeDatabase()
    {
        $doctrine = $this->getService('doctrine');
        /* @var $doctrine ManagerRegistry */

        $connection = $doctrine->getConnection();
        /* @var $connection Connection */

        $connection->exec('SET foreign_key_checks = 0');

        $purger = new ORMPurger($doctrine->getManager());

        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();

        $connection->exec('SET foreign_key_checks = 1');
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
     * @param Response $response
     */
    protected function assertHasLocation(Response $response)
    {
        $this->assertTrue(
            $response->headers->has('Location'),
            'Location is present'
        );
    }

    /**
     * @param Response $response
     */
    protected function assertContentTypeIsJson(Response $response)
    {
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            'Content-Type is application/json'
        );
    }
}
