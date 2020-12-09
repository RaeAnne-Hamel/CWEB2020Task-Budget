<?php
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Choice({"True", "False"})
     */
    protected $checkPaid;

    /**
     * @return mixed
     */
    public function getBankID()
    {
        return $this->bankID;
    }

    /**
     * @param mixed $bankID
     */
    public function setBankID($bankID)
    {
        $this->bankID = $bankID;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getTypeTransaction()
    {
        return $this->typeTransaction;
    }

    /**
     * @param mixed $typeTransaction
     */
    public function setTypeTransaction($typeTransaction)
    {
        $this->typeTransaction = $typeTransaction;
    }

    /**
     * @return mixed
     */
    public function getCheckPaid()
    {
        return $this->checkPaid;
    }

    /**
     * @param mixed $checkPaid
     */
    public function setCheckPaid($checkPaid)
    {
        $this->checkPaid = $checkPaid;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

}