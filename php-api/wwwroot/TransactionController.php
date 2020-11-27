<?php

use Doctrine\ORM\EntityManager;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validation;

class TransactionController
{

    /**
     * Handles GET request
     * @param EntityManager $em
     * @param array $reqData
     * @return string
     */
    public static function getTransactions(EntityManager $em, array $reqData)
    {
        $qb = $em->createQueryBuilder();
        $qb->select('t')
            ->from('Transaction', 't');

        $transactionArray = $qb->getQuery()->getArrayResult();
        if(empty($transactionArray))
        {
            http_response_code(404);
        }
        return $transactionArray;

    }
    public static function postTransaction(EntityManager $em, array $reqData, Transaction $newTransaction)
    {
    }
    public static function putTransaction(EntityManager $em, array $reqData, Transaction $transactionFromDb)
    {
    }

    /**
     * DELETE request handler
     * @param EntityManager $em
     * @param array $reqData
     * @param Transaction $transactionToDelete
     * @return string
     */
    public static function deleteTransaction(EntityManager $em, array $reqData, Transaction $transactionToDelete )
    {
        $result = null;
        if(is_null($transactionToDelete))
        {
            http_response_code(404);
            $result = $reqData;
        }
        else
        {
            try
            {
                $em->remove($transactionToDelete);
                $em->flush();
                http_response_code(204);
                $result = null;
            }
            catch(\Doctrine\ORM\ORMException $e)
            {
                http_response_code(205);
                $result = $transactionToDelete;
            }

        }

        return $result;
    }
}