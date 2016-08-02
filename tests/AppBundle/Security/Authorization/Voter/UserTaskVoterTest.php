<?php

namespace Tests\AppBundle\Security\Authorization\Voter;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;

use AppBundle\Entity\User;
use AppBundle\Security\Authorization\Voter\UserTaskVoter;

/**
 * UserTaskVoterTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserTaskVoterTest extends AbstractVoterTestCase
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

        return [
            [$aliceToken, $alice, UserTaskVoter::LIST, UserTaskVoter::ACCESS_GRANTED],
            [$aliceToken, $bob, UserTaskVoter::LIST, UserTaskVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), UserTaskVoter::LIST, UserTaskVoter::ACCESS_ABSTAIN],
            [$aliceToken, $alice, 'not_supported_attribute', UserTaskVoter::ACCESS_ABSTAIN],

            [$aliceToken, $alice, UserTaskVoter::CREATE, UserTaskVoter::ACCESS_GRANTED],
            [$aliceToken, $bob, UserTaskVoter::CREATE, UserTaskVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), UserTaskVoter::CREATE, UserTaskVoter::ACCESS_ABSTAIN],
            [$aliceToken, $alice, 'not_supported_attribute', UserTaskVoter::ACCESS_ABSTAIN],
        ];
    }

    protected function populateVariables()
    {
        $this->voter = static::$kernel->getContainer()
            ->get('app.security.authorization.voter.user_task_voter');
    }
}