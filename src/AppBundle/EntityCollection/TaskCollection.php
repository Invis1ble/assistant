<?php

namespace AppBundle\EntityCollection;

use AppBundle\Entity\Task;

/**
 * TaskCollection
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskCollection
{
    /**
     * @var Task[]
     */
    public $entities;

    /**
     * @var int
     */
    public $offset;

    /**
     * @var int
     */
    public $limit;

    /**
     * TaskCollection constructor.
     *
     * @param Task[]   $entities
     * @param int|null $offset
     * @param int|null $limit
     */
    public function __construct(array $entities = [], int $offset = null, int $limit = null)
    {
        $this->entities = $entities;
        $this->offset = $offset;
        $this->limit = $limit;
    }
}