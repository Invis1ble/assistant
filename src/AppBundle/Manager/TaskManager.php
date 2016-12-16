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
     */
    public function save(Task $task)
    {
        $this->objectManager->persist($task);
    }

    /**
     * @param Task $task
     */
    public function saveAndFlush(Task $task)
    {
        $this->objectManager->persist($task);
        $this->objectManager->flush();
    }

    /**
     * @param Task $task
     */
    public function remove(Task $task)
    {
        $this->objectManager->remove($task);
    }

    /**
     * @param Task $task
     */
    public function removeAndFlush(Task $task)
    {
        $this->remove($task);
        $this->objectManager->flush();
    }
}