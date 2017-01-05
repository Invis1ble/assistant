<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

/**
 * Category
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @ORM\Table(
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"name", "user_id"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
{
    const DEFAULT_RATE = '20.00';

    /**
     * @var Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @var string|null
     *А
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $rate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="categories")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;
    
    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Task", mappedBy="category", cascade={"remove"})
     */
    protected $tasks;

    public function __construct()
    {
        $this->setRate(static::DEFAULT_RATE);
        $this->tasks = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return Category
     */
    public function setName(string $name = null): Category
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     *
     * @return Category
     */
    public function setDescription(string $description = null): Category
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getRate(): string
    {
        return $this->rate;
    }

    /**
     * @param string $rate
     *
     * @return Category
     */
    public function setRate(string $rate): Category
    {
        $this->rate = $rate;

        return $this;
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
     * @return Category
     */
    public function setUser(User $user): Category
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param Task $task
     *
     * @return Category
     */
    public function addTask(Task $task): Category
    {
        $this->getTasks()->add($task);

        return $this;
    }

    /**
     * @param Task $task
     *
     * @return Category
     */
    public function removeTask(Task $task): Category
    {
        $this->getTasks()->removeElement($task);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }
}