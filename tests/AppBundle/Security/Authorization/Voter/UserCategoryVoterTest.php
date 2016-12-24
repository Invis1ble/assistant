<?php

namespace Tests\AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\User;
use AppBundle\Security\Authorization\Voter\UserCategoryVoter;

/**
 * UserCategoryVoterTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group unit
 */
class UserCategoryVoterTest extends AbstractVoterTestCase
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
        $anonymousToken = $this->createAnonymousToken();

        return [
            [$aliceToken, $alice, UserCategoryVoter::LIST, UserCategoryVoter::ACCESS_GRANTED],
            [$aliceToken, $bob, UserCategoryVoter::LIST, UserCategoryVoter::ACCESS_DENIED],
            [$anonymousToken, $bob, UserCategoryVoter::LIST, UserCategoryVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), UserCategoryVoter::LIST, UserCategoryVoter::ACCESS_ABSTAIN],

            [$aliceToken, $alice, UserCategoryVoter::CREATE, UserCategoryVoter::ACCESS_GRANTED],
            [$aliceToken, $bob, UserCategoryVoter::CREATE, UserCategoryVoter::ACCESS_DENIED],
            [$anonymousToken, $bob, UserCategoryVoter::CREATE, UserCategoryVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), UserCategoryVoter::CREATE, UserCategoryVoter::ACCESS_ABSTAIN],

            [$aliceToken, $alice, 'not_supported_attribute', UserCategoryVoter::ACCESS_ABSTAIN],
        ];
    }

    protected function populateVariables()
    {
        $this->voter = static::$kernel->getContainer()
            ->get('app.security.authorization.voter.user_category_voter');
    }
}