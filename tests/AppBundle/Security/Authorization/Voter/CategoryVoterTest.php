<?php

namespace Tests\AppBundle\Security\Authorization\Voter;

use AppBundle\Security\Authorization\Voter\CategoryVoter;
use AppBundle\Entity\User;

/**
 * CategoryVoterTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group unit
 */
class CategoryVoterTest extends AbstractVoterTestCase
{
    use CreateUserCategoryTrait;

    /**
     * @return array[]
     */
    public function voteProvider(): array
    {
        $alice = new User();
        $alice->setUsername('alice');

        $bob = new User();
        $bob->setUsername('bob');

        $aliceCategory = $this->createUserCategory($alice);
        $bobCategory = $this->createUserCategory($bob);

        $aliceToken = $this->createJWTToken($alice);
        $anonymousToken = $this->createAnonymousToken();

        return [
            [$aliceToken, $aliceCategory, CategoryVoter::SHOW, CategoryVoter::ACCESS_GRANTED],
            [$aliceToken, $bobCategory, CategoryVoter::SHOW, CategoryVoter::ACCESS_DENIED],
            [$anonymousToken, $bobCategory, CategoryVoter::SHOW, CategoryVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), CategoryVoter::SHOW, CategoryVoter::ACCESS_ABSTAIN],

            [$aliceToken, $aliceCategory, CategoryVoter::EDIT, CategoryVoter::ACCESS_GRANTED],
            [$aliceToken, $bobCategory, CategoryVoter::EDIT, CategoryVoter::ACCESS_DENIED],
            [$anonymousToken, $bobCategory, CategoryVoter::EDIT, CategoryVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), CategoryVoter::EDIT, CategoryVoter::ACCESS_ABSTAIN],

            [$aliceToken, $aliceCategory, CategoryVoter::DELETE, CategoryVoter::ACCESS_GRANTED],
            [$aliceToken, $bobCategory, CategoryVoter::DELETE, CategoryVoter::ACCESS_DENIED],
            [$anonymousToken, $bobCategory, CategoryVoter::DELETE, CategoryVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), CategoryVoter::DELETE, CategoryVoter::ACCESS_ABSTAIN],

            [$aliceToken, $aliceCategory, 'not_supported_attribute', CategoryVoter::ACCESS_ABSTAIN],
        ];
    }

    protected function populateVariables()
    {
        $this->voter = static::$kernel->getContainer()
            ->get('app.security.authorization.voter.category_voter');
    }
}