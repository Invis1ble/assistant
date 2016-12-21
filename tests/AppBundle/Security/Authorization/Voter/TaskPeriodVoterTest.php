<?php

namespace Tests\AppBundle\Security\Authorization\Voter;

use AppBundle\Security\Authorization\Voter\TaskPeriodVoter;
use AppBundle\Entity\User;

/**
 * TaskPeriodVoterTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group unit
 */
class TaskPeriodVoterTest extends AbstractVoterTestCase
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

        $aliceTask = $this->createUserTask($alice);
        $bobTask = $this->createUserTask($bob);

        $aliceToken = $this->createJWTToken($alice);
        $anonymousToken = $this->createAnonymousToken();

        return [
            [$aliceToken, $aliceTask, TaskPeriodVoter::LIST, TaskPeriodVoter::ACCESS_GRANTED],
            [$aliceToken, $bobTask, TaskPeriodVoter::LIST, TaskPeriodVoter::ACCESS_DENIED],
            [$anonymousToken, $bobTask, TaskPeriodVoter::LIST, TaskPeriodVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), TaskPeriodVoter::LIST, TaskPeriodVoter::ACCESS_ABSTAIN],

            [$aliceToken, $aliceTask, TaskPeriodVoter::CREATE, TaskPeriodVoter::ACCESS_GRANTED],
            [$aliceToken, $bobTask, TaskPeriodVoter::CREATE, TaskPeriodVoter::ACCESS_DENIED],
            [$anonymousToken, $bobTask, TaskPeriodVoter::CREATE, TaskPeriodVoter::ACCESS_DENIED],
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