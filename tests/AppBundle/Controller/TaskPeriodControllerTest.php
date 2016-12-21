<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\{
    Task,
    User
};

/**
 * TaskPeriodControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group smoke
 */
class TaskPeriodControllerTest extends ApiTestCase
{
    use GetUserTaskTrait;

    public function testGetPeriods()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $aliceTask = $this->getUserTask($alice);
        $bobTask = $this->getUserTask($bob);

        $this->assertUnauthorized(
            $this->get('/api/tasks/' . $uuid4 . '/periods')
                ->getResponse()
        );

        $this->assertNotFound(
            $this->get('/api/tasks/' . $uuid4 . '/periods', $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->get('/api/tasks/' . $bobTask->getId() . '/periods', $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $response = $this->get('/api/tasks/' . $aliceTask->getId() . '/periods', $alice->getUsername(), 'alice_plain_password')
            ->getResponse();

        $this->assertOk($response);
        $this->assertResponsePayloadContains('entities', $response);
    }

    public function testPostPeriod()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $aliceTask = $this->getInactiveUserTask($alice);
        $bobTask = $this->getInactiveUserTask($bob);

        $this->assertUnauthorized(
            $this->post('/api/tasks/' . $uuid4 . '/periods')
                ->getResponse()
        );

        $this->assertNotFound(
            $this->post('/api/tasks/' . $uuid4 . '/periods', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->post('/api/tasks/' . $bobTask->getId() . '/periods', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertValidationFailed(
            $this->post('/api/tasks/' . $aliceTask->getId() . '/periods', [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertCreated(
            $this->post('/api/tasks/' . $aliceTask->getId() . '/periods', [
                'startedAt' => time(),
            ], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }

    /**
     * @param User $user
     *
     * @return Task|null
     */
    protected function getInactiveUserTask(User $user)
    {
        $alias = 'task';

        $queryBuilder = $this->getRepository('AppBundle:Task')
            ->createQueryBuilder($alias)
        ;

        return $queryBuilder
            ->addSelect($queryBuilder->expr()->count($alias . '__periods.id') . ' HIDDEN ' . $alias . '__periods__count')
            ->leftJoin($alias . '.periods', $alias . '__periods')
            ->leftJoin($alias . '.category', $alias . '__category')
            ->andWhere($queryBuilder->expr()->isNull($alias . '__periods.finishedAt'))
            ->andWhere($alias . '__category.user = :' . $alias . '__category__user')
            ->setParameter($alias . '__category__user', $user)
            ->addGroupBy($alias . '.id')
            ->andHaving($alias . '__periods__count = :' . $alias . '__periods__count')
            ->setParameter($alias . '__periods__count', 0)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
