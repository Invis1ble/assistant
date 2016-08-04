<?php

namespace AppBundle\EntityCollection;

use AppBundle\Entity\{
    Period,
    Task
};

/**
 * TaskPeriodCollection
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskPeriodCollection extends AbstractEntityCollection
{
    /**
     * @var Task
     */
    protected $task;

    /**
     * TaskCollection constructor.
     *
     * @param Task     $task
     * @param Period[] $entities
     */
    public function __construct(Task $task, array $entities = [])
    {
        $this->setTask($task);
        $this->setEntities($entities);
    }

    /**
     * @return Task
     */
    public function getTask(): Task
    {
        return $this->task;
    }

    /**
     * @param Task $task
     *
     * @return TaskPeriodCollection
     */
    public function setTask(Task $task): TaskPeriodCollection
    {
        $this->task = $task;

        return $this;
    }
}