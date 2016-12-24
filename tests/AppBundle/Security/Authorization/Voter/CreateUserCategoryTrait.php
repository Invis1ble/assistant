<?php

namespace tests\AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\{
    Category,
    User
};

/**
 * Trait CreateUserCategoryTrait
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
trait CreateUserCategoryTrait
{
    /**
     * @param User $user
     *
     * @return Category
     */
    protected function createUserCategory(User $user): Category
    {
        $userCategory = new Category();
        $userCategory->setUser($user);

        return $userCategory;
    }
}