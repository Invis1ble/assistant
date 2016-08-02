<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\User;
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
     * @param User $user
     * @param int  $limit
     * @param int  $offset
     *
     * @return Task[]
     */
    public function findLatest(User $user, int $limit = Task::NUM_ITEMS, int $offset = null): array
    {
        return $this->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC'],
            $limit,
            $offset
        );
    }
}