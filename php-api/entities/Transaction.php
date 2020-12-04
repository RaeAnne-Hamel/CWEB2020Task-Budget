<?php
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="transactions")
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    /**
     * @ORM\Column(type="integer")
     */
    protected $bankID;
    /**
     * @ORM\Column(type="decimal")
     */
    protected $amount;
    /**
     * @ORM\Column(type="string")
     */
    protected $typeTransaction;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $checkPaid;


}