<?php
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="types")
 */
class Type
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $typeName;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->typeName;
    }

    public function getId()
    {
        return $this->id;
    }

}