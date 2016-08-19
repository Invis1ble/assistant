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
     * @var PeriodManager
     */
    protected $periodManager;

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

    public function remove(Task $task, bool $andFlush = true)
    {
        foreach ($task->getPeriods() as $period) {
            $this->getPeriodManager()->remove($period);
        }

        $this->objectManager->remove($task);

        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @return PeriodManager
     */
    public function getPeriodManager(): PeriodManager
    {
        return $this->periodManager;
    }

    /**
     * @param PeriodManager $periodManager
     *
     * @return TaskManager
     */
    public function setPeriodManager(PeriodManager $periodManager): TaskManager
    {
        $this->periodManager = $periodManager;

        return $this;
    }
}