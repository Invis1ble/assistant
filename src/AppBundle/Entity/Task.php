<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Utils\Entity\CreatedAtTrait;

/**
 * Task
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 */
class Task
{
    use CreatedAtTrait;

    const DEFAULT_RATE = '20.00';
    
    const NUM_ITEMS = 10;

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
     * @ORM\Column(type="text")
     */
    protected $title;

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;
    
    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Period", mappedBy="task", cascade={"remove"})
     */
    protected $periods;

    public function __construct()
    {
        $this->setRate(static::DEFAULT_RATE);
        $this->periods = new ArrayCollection();
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     *
     * @return Task
     */
    public function setTitle(string $title = null): Task
    {
        $this->title = $title;

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
     * @return Task
     */
    public function setDescription(string $description = null): Task
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
     * @return Task
     */
    public function setRate(string $rate): Task
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
     * @return Task
     */
    public function setUser(User $user): Task
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param Period $period
     *
     * @return Task
     */
    public function addPeriod(Period $period): Task
    {
        $this->getPeriods()->add($period);

        return $this;
    }

    /**
     * @param Period $period
     *
     * @return Task
     */
    public function removePeriod(Period $period): Task
    {
        $this->getPeriods()->removeElement($period);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPeriods(): Collection
    {
        return $this->periods;
    }
}