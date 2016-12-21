<?php

namespace Tests\AppBundle\Security\Authorization\Voter;

use AppBundle\Security\Authorization\Voter\PeriodVoter;
use AppBundle\Entity\{
    Period,
    User
};

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
    use CreateUserTaskTrait;

    /**
     * @return array[]
     */
    public function voteProvider(): array
    {
        $alice = new User();
        $alice->setUsername('alice');

        $bob = new User();
        $bob->setUsername('bob');

        $alicePeriod = $this->createUserPeriod($alice);
        $bobPeriod = $this->createUserPeriod($bob);

        $aliceToken = $this->createJWTToken($alice);
        $anonymousToken = $this->createAnonymousToken();

        return [
            [$aliceToken, $alicePeriod, PeriodVoter::SHOW, PeriodVoter::ACCESS_GRANTED],
            [$aliceToken, $bobPeriod, PeriodVoter::SHOW, PeriodVoter::ACCESS_DENIED],
            [$anonymousToken, $alicePeriod, PeriodVoter::SHOW, PeriodVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), PeriodVoter::SHOW, PeriodVoter::ACCESS_ABSTAIN],

            [$aliceToken, $alicePeriod, PeriodVoter::EDIT, PeriodVoter::ACCESS_GRANTED],
            [$aliceToken, $bobPeriod, PeriodVoter::EDIT, PeriodVoter::ACCESS_DENIED],
            [$anonymousToken, $bobPeriod, PeriodVoter::EDIT, PeriodVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), PeriodVoter::EDIT, PeriodVoter::ACCESS_ABSTAIN],

            [$aliceToken, $alicePeriod, 'not_supported_attribute', PeriodVoter::ACCESS_ABSTAIN],
        ];
    }

    /**
     * @param User $user
     *
     * @return Period
     */
    protected function createUserPeriod(User $user): Period
    {
        $userTask = $this->createUserTask($user);
        $userPeriod = new Period();
        $userPeriod->setTask($userTask);

        return $userPeriod;
    }

    protected function populateVariables()
    {
        $this->voter = static::$kernel->getContainer()
            ->get('app.security.authorization.voter.period_voter');
    }
}