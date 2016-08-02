<?php

namespace AppBundle\EntityCollection;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;

/**
 * UserTaskCollection
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserTaskCollection
{
    /**
     * @var Task[]
     */
    public $entities;

    /**
     * @var User
     */
    public $user;

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
     * @param User     $user
     * @param int|null $offset
     * @param int|null $limit
     */
    public function __construct(array $entities = [], User $user, int $offset = null, int $limit = null)
    {
        $this->entities = $entities;
        $this->user = $user;
        $this->offset = $offset;
        $this->limit = $limit;
    }
}