<?php

namespace Tests\AppBundle\Security\Authorization\Voter;

use AppBundle\Security\Authorization\Voter\PeriodVoter;
use AppBundle\Entity\User;
use AppBundle\Entity\Period;
use AppBundle\Entity\Task;

/**
 * PeriodVoterTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group unit
 */
class PeriodVoterTest extends AbstractVoterTestCase
{
    /**
     * @return array[]
     */
    public function voteProvider(): array
    {
        $alice = new User();
        $alice->setUsername('alice');

        $bob = new User();
        $bob->setUsername('bob');

        $aliceToken = $this->createJWTToken($alice);

        $aliceTask = new Task();
        $aliceTask->setUser($alice);

        $bobTask = new Task();
        $bobTask->setUser($bob);
        
        $aliceTaskPeriod = new Period();
        $aliceTaskPeriod->setTask($aliceTask);
        
        $bobTaskPeriod = new Period();
        $bobTaskPeriod->setTask($bobTask);

        $anonymousToken = $this->createAnonymousToken();

        return [
            [$aliceToken, $aliceTaskPeriod, PeriodVoter::SHOW, PeriodVoter::ACCESS_GRANTED],
            [$aliceToken, $bobTaskPeriod, PeriodVoter::SHOW, PeriodVoter::ACCESS_DENIED],
            [$anonymousToken, $aliceTaskPeriod, PeriodVoter::SHOW, PeriodVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), PeriodVoter::SHOW, PeriodVoter::ACCESS_ABSTAIN],
            [$aliceToken, $aliceTaskPeriod, 'not_supported_attribute', PeriodVoter::ACCESS_ABSTAIN],

            [$aliceToken, $aliceTaskPeriod, PeriodVoter::EDIT, PeriodVoter::ACCESS_GRANTED],
            [$aliceToken, $bobTaskPeriod, PeriodVoter::EDIT, PeriodVoter::ACCESS_DENIED],
            [$anonymousToken, $bobTaskPeriod, PeriodVoter::EDIT, PeriodVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), PeriodVoter::EDIT, PeriodVoter::ACCESS_ABSTAIN],
            [$aliceToken, $aliceTaskPeriod, 'not_supported_attribute', PeriodVoter::ACCESS_ABSTAIN],
        ];
    }

    protected function populateVariables()
    {
        $this->voter = static::$kernel->getContainer()
            ->get('app.security.authorization.voter.period_voter');
    }
}