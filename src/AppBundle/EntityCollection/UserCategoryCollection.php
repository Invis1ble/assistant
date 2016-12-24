<?php

namespace AppBundle\EntityCollection;

use AppBundle\Entity\{
    User,
    Category
};

/**
 * UserCategoryCollection
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserCategoryCollection extends AbstractEntityCollection
{
    /**
     * @var User
     */
    protected $user;

    /**
     * CategoryCollection constructor.
     *
     * @param Category[] $entities
     * @param User       $user
     */
    public function __construct(array $entities = [], User $user)
    {
        $this
            ->setEntities($entities)
            ->setUser($user)
        ;
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
     * @return UserCategoryCollection
     */
    public function setUser(User $user): UserCategoryCollection
    {
        $this->user = $user;

        return $this;
    }
}