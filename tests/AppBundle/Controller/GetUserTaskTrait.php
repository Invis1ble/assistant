<?php

namespace tests\AppBundle\Controller;

use AppBundle\Entity\{
    Task,
    User
};

/**
 * GetUserTaskTrait
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
trait GetUserTaskTrait
{
    /**
     * @param User $user
     *
     * @return Task|null
     */
    protected function getUserTask(User $user)
    {
        return $user->getTasks()
            ->get(0);
    }
}