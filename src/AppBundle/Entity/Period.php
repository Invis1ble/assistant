<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use DateTime;

/**
 * Period
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PeriodRepository")
 */
class Period
{
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
     * @var DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    protected $startedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $finishedAt;
    
    /**
     * @var Task
     *
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="periods")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id", nullable=false)
     */
    protected $task;

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param DateTime $startedAt
     *
     * @return Period
     */
    public function setStartedAt(DateTime $startedAt): Period
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * @param DateTime|null $finishedAt
     *
     * @return Period
     */
    public function setFinishedAt(DateTime $finishedAt = null): Period
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * @return Task
     */
    public function getTask(): Task
    {
        return $this->task;
    }

    /**
     * @param Task $task
     *
     * @return Period
     */
    public function setTask(Task $task): Period
    {
        $this->task = $task;
        
        return $this;
    }

    /**
     * @param Task $task
     *
     * @return Period
     */
    public function setTaskAndAddSelfToPeriodsCollection(Task $task): Period
    {
        $this->setTask($task);
        $task->addPeriod($this);
        
        return $this;
    }
}