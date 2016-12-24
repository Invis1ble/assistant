<?php

namespace tests\AppBundle\Controller;

use AppBundle\Entity\{
    Category,
    User
};

/**
 * Trait GetUserCategory
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
trait GetUserCategoryTrait
{
    /**
     * @param User $user
     *
     * @return Category
     */
    protected function getUserCategory(User $user): Category
    {
        return $user->getCategories()
            ->get(0);
    }
}