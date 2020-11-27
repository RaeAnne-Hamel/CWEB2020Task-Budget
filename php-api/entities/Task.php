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
     * @ORM\column(type= "integer")
     * @ORM\GeneratedValue
     * @Assert\PositiveOrZero
     */
    protected $id;

    //this could possible be done in a date class??
    /**
     * @ORM\column (type = "string")
     */
    protected $startDate;

    /**
     * @ORM\column (type = "string")
     */
    protected $dueDate;

    /**
     * @ORM\column (type = "string")
     */
    protected $completedDate;

    /**
     * @ORM\column (type = "string")
     * @Assert\NotBlank(normalizer="trim", message="Task Type is required")
     * @Assert\Length(max = 60, max = "Task Type cannot be longer the {{limit}} characters.")
     */
    protected $type;

    /**
     * @ORM\column (type = "string")
     * @Assert\NotBlank(normalizer="trim", message="Task Type is required")
     * @Assert\choice(choices=Task::URGENCY_TYPE, message="Choose a valid urgency type")
     */
    protected $urgency;

    /**
     * @ORM\column (type = "string")
     * @Assert\NotBlank(normalizer="trim", message="Task Type is required")
     * @Assert\Length(max = 180, max = "Task Type cannot be longer the {{limit}} characters.")
     */
    protected $detail;
}