<?php

namespace tests\AppBundle\Controller;

use AppBundle\Entity\{
    Task,
    User
};

/**
 * Trait GetUserTaskTrait
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
trait GetUserTaskTrait
{
    use GetUserCategoryTrait;

    /**
     * @param User $user
     *
     * @return Task|null
     */
    protected function getUserTask(User $user)
    {
        return $this->getUserCategory($user)
            ->getTasks()
            ->get(0);
    }
}