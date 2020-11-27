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
     * @ORM\Column(type="object")
     */
    protected $typeTransaction;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $checkPaid;

    /**
     * @param string
     */
    public function setTransactionType($type)
    {
        $this->typeTransaction = $type;
    }

    /**
     * @param bool
     */
    public function setPaid($paid)
    {
        $this->checkPaid = $paid;
    }

    /**
     * @return int
     */
    public function getBankID()
    {
        return $this->bankID;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

}