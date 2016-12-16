<?php

namespace AppBundle\EntityCollection;

use AppBundle\Entity\{
    User,
    Task
};

/**
 * UserTaskCollection
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserTaskCollection extends AbstractEntityCollection
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $limit;

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
        $this->setEntities($entities);
        $this->setUser($user);
        $this->setLimit($limit);
        $this->setOffset($offset);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return UserTaskCollection
     */
    public function setUser(User $user): UserTaskCollection
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     *
     * @return UserTaskCollection
     */
    public function setOffset(int $offset = null): UserTaskCollection
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     *
     * @return UserTaskCollection
     */
    public function setLimit(int $limit = null): UserTaskCollection
    {
        $this->limit = $limit;

        return $this;
    }
}