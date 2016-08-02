<?php

namespace Tests\AppBundle\Security\Authorization\Voter;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;

use AppBundle\Security\Authorization\Voter\UserVoter;
use AppBundle\Entity\User;

/**
 * UserVoterTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserVoterTest extends AbstractVoterTestCase
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
            [$aliceToken, $alice, UserVoter::SHOW, UserVoter::ACCESS_GRANTED],
            [$aliceToken, $bob, UserVoter::SHOW, UserVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), UserVoter::SHOW, UserVoter::ACCESS_ABSTAIN],
            [$aliceToken, $alice, 'not_supported_attribute', UserVoter::ACCESS_ABSTAIN],
        ];
    }

    protected function populateVariables()
    {
        $this->voter = static::$kernel->getContainer()
            ->get('app.security.authorization.voter.user_voter');
    }
}