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
class TaskPeriodCollection
{
    /**
     * @var Period[]
     */
    public $entities;

    /**
     * @var Task
     */
    public $task;

    /**
     * TaskCollection constructor.
     *
     * @param Task     $task
     * @param Period[] $entities
     */
    public function __construct(Task $task, array $entities = [])
    {
        $this->task = $task;
        $this->entities = $entities;
    }
}