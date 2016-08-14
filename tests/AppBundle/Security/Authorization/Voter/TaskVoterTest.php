<?php

namespace Tests\AppBundle\Security\Authorization\Voter;

use AppBundle\Security\Authorization\Voter\TaskVoter;
use AppBundle\Entity\{
    Task,
    User
};

/**
 * TaskVoterTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskVoterTest extends AbstractVoterTestCase
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

        $anonymousToken = $this->createAnonymousToken();

        return [
            [$aliceToken, $aliceTask, TaskVoter::SHOW, TaskVoter::ACCESS_GRANTED],
            [$aliceToken, $bobTask, TaskVoter::SHOW, TaskVoter::ACCESS_DENIED],
            [$anonymousToken, $bobTask, TaskVoter::SHOW, TaskVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), TaskVoter::SHOW, TaskVoter::ACCESS_ABSTAIN],
            [$aliceToken, $aliceTask, 'not_supported_attribute', TaskVoter::ACCESS_ABSTAIN],

            [$aliceToken, $aliceTask, TaskVoter::EDIT, TaskVoter::ACCESS_GRANTED],
            [$aliceToken, $bobTask, TaskVoter::EDIT, TaskVoter::ACCESS_DENIED],
            [$anonymousToken, $bobTask, TaskVoter::EDIT, TaskVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), TaskVoter::EDIT, TaskVoter::ACCESS_ABSTAIN],
            [$aliceToken, $aliceTask, 'not_supported_attribute', TaskVoter::ACCESS_ABSTAIN],
        ];
    }

    protected function populateVariables()
    {
        $this->voter = static::$kernel->getContainer()
            ->get('app.security.authorization.voter.task_voter');
    }
}