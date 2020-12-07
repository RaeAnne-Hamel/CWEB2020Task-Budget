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
     * @ORM\column (type="string")
     */
    protected $completedDate;

    /**
     * @ORM\column (type="string")
     * @Assert\NotBlank(normalizer="trim", message="Task Type is required")
     *
     */
    protected $type;

    /**
     *
     * @Assert\choice(choices=Task::URGENCY_TYPE, message="Choose a valid urgency type")
     */
    protected $urgency;

    /**
     * @ORM\column (type="string", nullable=true)
     *
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getUrgency(): ?string
    {
        return $this->urgency;
    }

    /**
     * @param string $urgency
     */
    public function setUrgency(string $urgency): void
    {
        $this->urgency = $urgency;
    }

    /**
     * @return string|null
     */
    public function getDetail(): ?string
    {
        return $this->detail;
    }

    /**
     * @param string|null $detail
     */
    public function setDetail(?string $detail): void
    {
        $this->detail = $detail;
    }






}