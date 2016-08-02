<?php

namespace Tests\AppBundle\Security\Authorization\Voter;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;

use AppBundle\Security\Authorization\Voter\TaskPeriodVoter;
use AppBundle\Entity\User;
use AppBundle\Entity\Task;

/**
 * TaskPeriodVoterTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskPeriodVoterTest extends AbstractVoterTestCase
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

        $aliceToken = new JWTUserToken($alice->getRoles());
        $aliceToken->setUser($alice);

        $aliceTask = new Task();
        $aliceTask->setUser($alice);

        $bobTask = new Task();
        $bobTask->setUser($bob);

        return [
            [$aliceToken, $aliceTask, TaskPeriodVoter::LIST, TaskPeriodVoter::ACCESS_GRANTED],
            [$aliceToken, $bobTask, TaskPeriodVoter::LIST, TaskPeriodVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), TaskPeriodVoter::LIST, TaskPeriodVoter::ACCESS_ABSTAIN],
            [$aliceToken, $aliceTask, 'not_supported_attribute', TaskPeriodVoter::ACCESS_ABSTAIN],

            [$aliceToken, $aliceTask, TaskPeriodVoter::CREATE, TaskPeriodVoter::ACCESS_GRANTED],
            [$aliceToken, $bobTask, TaskPeriodVoter::CREATE, TaskPeriodVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), TaskPeriodVoter::CREATE, TaskPeriodVoter::ACCESS_ABSTAIN],
            [$aliceToken, $aliceTask, 'not_supported_attribute', TaskPeriodVoter::ACCESS_ABSTAIN],
        ];
    }

    protected function populateVariables()
    {
        $this->voter = static::$kernel->getContainer()
            ->get('app.security.authorization.voter.task_period_voter');
    }
}