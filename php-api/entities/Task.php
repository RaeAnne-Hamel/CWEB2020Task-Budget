<?php


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="task")
 */
class Task
{
    const URGENCY_TYPE = ['High', 'Medium', "Low"];

    /**
     * @ORM\Id
     * @ORM\column(type="integer")
     * @ORM\GeneratedValue
     * @Assert\PositiveOrZero
     */
    protected $id;

    //this could possible be done in a date class??
    /**
     * @ORM\column (type="string")
     */
    protected $startDate;

    /**
     * @ORM\column (type="string")
     */
    protected $dueDate;

    /**
     * @ORM\column (type="string", nullable=true)
     */
    protected $completedDate;

    /**
     * @ORM\column (type="string", length=60, nullable=true)
     * @Assert\Length(max=60, maxMessage="Task Type cannot be longer than {{limit}} characters.")
     */
    protected $taskType;

    /**
     * @ORM\column (type="string")
     * @Assert\choice(choices=Task::URGENCY_TYPE, message="Choose a valid urgency type")
     */
    protected $urgency;

    /**
     * @ORM\column (type="string", nullable=true)
     * @Assert\Length(max=180, maxMessage="Task Type cannot be longer the {{limit}} characters.")
     */
    protected $detail;


    /***************************
     *    GETTERS AND SETTERS  *
     ***************************/
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param mixed $dueDate
     */
    public function setDueDate($dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @return mixed
     */
    public function getCompletedDate()
    {
        return $this->completedDate;
    }

    /**
     * @param mixed $completedDate
     */
    public function setCompletedDate($completedDate): void
    {
        $this->completedDate = $completedDate;
    }

    /**
     * @return mixed
     */
    public function getTaskType()
    {
        return $this->taskType;
    }

    /**
     * @param mixed $taskType
     */
    public function setTaskType($taskType): void
    {
        $this->taskType = $taskType;
    }

    /**
     * @return mixed
     */
    public function getUrgency()
    {
        return $this->urgency;
    }

    /**
     * @param mixed $urgency
     */
    public function setUrgency($urgency): void
    {
        $this->urgency = $urgency;
    }

    /**
     * @return mixed
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param mixed $detail
     */
    public function setDetail($detail): void
    {
        $this->detail = $detail;
    }









}