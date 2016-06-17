<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Task;
use Doctrine\ORM\EntityRepository;

/**
 * TaskRepository
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskRepository extends EntityRepository
{
    /**
     * @param int $limit
     * @param int $offset
     *
     * @return Task[]
     */
    public function findLatest(int $limit = Task::NUM_ITEMS, int $offset = null): array
    {
        return $this->findBy(
            [],
            ['createdAt' => 'DESC'],
            $limit,
            $offset
        );
    }
}