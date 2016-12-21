<?php

namespace tests\AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\{
    Task,
    User
};

/**
 * Trait CreateUserTaskTrait
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
trait CreateUserTaskTrait
{
    use CreateUserCategoryTrait;

    /**
     * @param User $user
     *
     * @return Task
     */
    protected function createUserTask(User $user): Task
    {
        $userCategory = $this->createUserCategory($user);
        $userTask = new Task();
        $userTask->setCategory($userCategory);

        return $userTask;
    }
}