<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Period;
use AppBundle\Entity\User;

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

        $this->assertUnauthorized(
            $this->get('/api/periods/' . $uuid4)
                ->getResponse()
        );

        $user = $this->getUser('alice');

        $this->assertNotFound(
            $this->get('/api/periods/' . $uuid4, $user->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertOk(
            $this->get('/api/periods/' . $this->getPeriod($user)->getId(), $user->getUsername(), 'alice_plain_password')
                ->getResponse()
        );
    }

    public function testPatchPeriod()
    {
        $uuid4 = $this->getUUID4stub();

        $this->assertUnauthorized(
            $this->patch('/api/periods/' . $uuid4)
                ->getResponse()
        );

        $user = $this->getUser('alice');

        $this->assertNotFound(
            $this->patch('/api/periods/' . $uuid4, [], $user->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $period = $this->getPeriod($user);

        $this->assertValidationFailed(
            $this->patch('/api/periods/' . $period->getId(), [
                'startedAt' => 'invalid_timestamp',
            ], $user->getUsername(), 'alice_plain_password')
                ->getResponse()
        );

        $this->assertEquals(
            204,
            $this->patch('/api/periods/' . $period->getId(), [
                'startedAt' => $period->getStartedAt()->getTimestamp() - 1,
            ], $user->getUsername(), 'alice_plain_password')
                ->getResponse()
                ->getStatusCode()
        );
    }

    /**
     * @param User $user
     *
     * @return Period|null
     */
    protected function getPeriod(User $user)
    {
        $alias = 'period';

        return $this->getRepository('AppBundle:Period')
            ->createQueryBuilder($alias)
            ->leftJoin($alias . '.task', $alias . '__task')
            ->andWhere($alias . '__task.user = :' . $alias . '__task__user')
            ->setParameter($alias . '__task__user', $user)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
