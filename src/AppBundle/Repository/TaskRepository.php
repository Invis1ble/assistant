<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\Category;
use AppBundle\Entity\Task;

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
     * @param Category $category
     * @param int      $limit
     * @param int      $offset
     *
     * @return Task[]
     */
    public function findLatestInCategory(Category $category, int $limit = Task::NUM_ITEMS, int $offset = null): array
    {
        return $this->findBy(
            ['category' => $category],
            ['createdAt' => 'DESC'],
            $limit,
            $offset
        );
    }
}