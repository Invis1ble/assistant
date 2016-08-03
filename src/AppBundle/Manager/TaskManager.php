<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Task;

/**
 * TaskManager
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskManager extends AbstractManager
{
    /**
     * @return Task
     */
    public function createTask(): Task
    {
        return new Task();
    }

    /**
     * @param Task $task
     * @param bool $andFlush
     */
    public function save(Task $task, bool $andFlush = true)
    {
        $this->objectManager->persist($task);

        if ($andFlush) {
            $this->objectManager->flush();
        }
    }
}