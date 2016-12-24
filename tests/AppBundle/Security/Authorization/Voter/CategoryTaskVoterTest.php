<?php

namespace Tests\AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\User;
use AppBundle\Security\Authorization\Voter\CategoryTaskVoter;

/**
 * CategoryTaskVoterTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group unit
 */
class CategoryTaskVoterTest extends AbstractVoterTestCase
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
            [$aliceToken, $aliceCategory, CategoryTaskVoter::LIST, CategoryTaskVoter::ACCESS_GRANTED],
            [$aliceToken, $bobCategory, CategoryTaskVoter::LIST, CategoryTaskVoter::ACCESS_DENIED],
            [$anonymousToken, $bobCategory, CategoryTaskVoter::LIST, CategoryTaskVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), CategoryTaskVoter::LIST, CategoryTaskVoter::ACCESS_ABSTAIN],

            [$aliceToken, $aliceCategory, CategoryTaskVoter::CREATE, CategoryTaskVoter::ACCESS_GRANTED],
            [$aliceToken, $bobCategory, CategoryTaskVoter::CREATE, CategoryTaskVoter::ACCESS_DENIED],
            [$anonymousToken, $bobCategory, CategoryTaskVoter::CREATE, CategoryTaskVoter::ACCESS_DENIED],
            [$aliceToken, new \stdClass(), CategoryTaskVoter::CREATE, CategoryTaskVoter::ACCESS_ABSTAIN],

            [$aliceToken, $aliceCategory, 'not_supported_attribute', CategoryTaskVoter::ACCESS_ABSTAIN],
        ];
    }

    protected function populateVariables()
    {
        $this->voter = static::$kernel->getContainer()
            ->get('app.security.authorization.voter.category_task_voter');
    }
}