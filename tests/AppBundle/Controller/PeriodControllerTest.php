<?php

namespace Tests\AppBundle\Controller;

use Doctrine\ORM\QueryBuilder;

use AppBundle\Entity\{
    Period,
    User
};

/**
 * PeriodControllerTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class PeriodControllerTest extends ApiTestCase
{
    public function testGetPeriod()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $alicePeriod = $this->getUserPeriod($alice);
        $bobPeriod = $this->getUserPeriod($bob);

        $this->assertUnauthorized(
            $this->get('/api/periods/' . $uuid4)
                ->getResponse()
        );

        $this->assertNotFound(
            $this->get('/api/periods/' . $uuid4, $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->get('/api/periods/' . $bobPeriod->getId(), $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertOk(
            $this->get('/api/periods/' . $alicePeriod->getId(), $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }

    public function testPatchPeriod()
    {
        $uuid4 = $this->getUUID4stub();

        $alice = $this->getUser('alice');
        $bob = $this->getUser('bob');

        $alicePeriod = $this->getLastUserPeriod($alice);
        $bobPeriod = $this->getLastUserPeriod($bob);

        $this->assertUnauthorized(
            $this->patch('/api/periods/' . $uuid4)
                ->getResponse()
        );

        $this->assertNotFound(
            $this->patch('/api/periods/' . $uuid4, [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertForbidden(
            $this->patch('/api/periods/' . $bobPeriod->getId(), [], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertValidationFailed(
            $this->patch('/api/periods/' . $alicePeriod->getId(), [
                'startedAt' => 'invalid_timestamp',
            ], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $finishedAt = $alicePeriod->getFinishedAt();

        $this->assertPatched(
            $this->patch('/api/periods/' . $alicePeriod->getId(), [
                'startedAt' => $alicePeriod->getStartedAt()->getTimestamp() + 1,
                'finishedAt' => $finishedAt === null ? null : $finishedAt->getTimestamp() + 1,
            ], $alice->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }

    /**
     * @param User $user
     *
     * @return Period|null
     */
    protected function getUserPeriod(User $user)
    {
        return $this->createGetUserPeriodQueryBuilder($user)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param User $user
     *
     * @return Period|null
     */
    protected function getLastUserPeriod(User $user)
    {
        $queryBuilder = $this->createGetUserPeriodQueryBuilder($user);
        $alias = $queryBuilder->getRootAliases()[0];

        return $queryBuilder
            ->addOrderBy($alias . '.startedAt', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param User $user
     *
     * @return QueryBuilder
     */
    protected function createGetUserPeriodQueryBuilder(User $user): QueryBuilder
    {
        $alias = 'period';

        return $this->getRepository('AppBundle:Period')
            ->createQueryBuilder($alias)
            ->leftJoin($alias . '.task', $alias . '__task')
            ->andWhere($alias . '__task.user = :' . $alias . '__task__user')
            ->setParameter($alias . '__task__user', $user)
            ->setMaxResults(1)
        ;
    }
}
