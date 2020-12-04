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
        //Create the query object
        $qb = $em->createQueryBuilder();

        //Write the query to the database to grab all transactions.
        $qb->select('t')
            ->from('Transaction', 't');

        //Turn the results of the query into an array object that can be passed back as JSON.
        $transactionArray = $qb->getQuery()->getArrayResult();

        //If the array does not contain any data
        if(empty($transactionArray))
        {
            //Send back error 404 for no data found.
            http_response_code(404);
        }

        //Return the array of data or the error code.
        return $transactionArray;

    }

    /**
     * Handles POST request
     * @param EntityManager $em
     * @param array $reqData
     * @param Transaction $newTransaction
     * @return array|Transaction
     */
    public static function postTransaction(EntityManager $em, array $reqData, Transaction $newTransaction)
    {
        //Instantiating initial values for code used later.
        $result = null;
        $violations = [];

        //If a Transaction can be created using the data sent from the UI
        if(self::populateTransaction($reqData, $newTransaction, $violations))
        {
            try {
                //Save the Transaction to the in-memory database
                $em->persist($newTransaction);

                //Save all of the changes to the SQL database
                $em->flush();

                //Return code "Created"
                http_response_code(201);

                //Set the created Transaction to be the result object for returning
                $result = $newTransaction;

            }
            catch (\Doctrine\ORM\ORMException $e)
            {
                //Response code Internal Server Error
                http_response_code(500);

                //Set the result object to be any error message given
                $result = ['errorMessage'=> $e->getMessage()];
            }
        }
        else
        {
            //Response code Unprocessable Entity if the data cannot be turned into a valid object
            http_response_code(422);

            //Set any violations to the result object to be returned to the UI
            $result = $violations;
        }

        //Return the created Transaction object or any validation violations found
        return $result;
    }

    /**
     * Handles PUT request
     * @param EntityManager $em
     * @param array $reqData
     * @param Transaction $transactionFromDb
     */
    public static function putTransaction(EntityManager $em, array $reqData, Transaction $transactionFromDb)
    {
        //Instantiating initial values for code used later.
        $result = null;
        $violations = [];

        //If the Transaction object passed in from the UI does not contain any data
        if(is_null($transactionFromDb))
        {
            //Response Code Not Found
            http_response_code(404);

            //Send back the same data sent from the UI
            $result = $reqData;
        }
        //Else if a valid Transaction object can be created with the data passed in from the UI
        elseif (self::populateTransaction($reqData, $transactionFromDb, $violations))
        {
            try
            {
                //Save changes made to the database
                $em->flush();

                //Send the Transaction back to the UI
                $result = $transactionFromDb;
            }
            catch (\Doctrine\ORM\ORMException $e)
            {
                //Response Code Internal Server Error: Some Exception occured.
                http_response_code(500);

                //Send back any exception messages that occurred
                $result = ['errorMessage'=> $e->getMessage()];
            }
        }
        //Couldn't edit the Transaction
        else
        {
            //Response Code Unprocessable Entity
            http_response_code(422);

            //Send back any Validation violations that occurred when attempting to edit the Transaction
            $result = $violations;
        }

        //Return the edited Transaction object, Exception messages that occurred, or any Validation violations.
        return $result;
    }

    /**
     * Handles DELETE Request
     * @param EntityManager $em
     * @param array $reqData
     * @param Transaction $transactionToDelete
     * @return string
     */
    public static function deleteTransaction(EntityManager $em, array $reqData, Transaction $transactionToDelete )
    {
        //Initializing the return value
        $result = null;

        //If the Transaction sent from the UI does not contain any valid data
        if(is_null($transactionToDelete))
        {
            //Response code Transaction Not Found
            http_response_code(404);

            //Result is set to be the data passed in from the UI
            $result = $reqData;
        }
        //Transaction sent from UI is valid
        else
        {
            try
            {
                //Transaction is removed from the in-memory database
                $em->remove($transactionToDelete);

                //Save all changes made to the in-memory database to the actual SQL database.
                $em->flush();

                //Response code No Content
                http_response_code(204);

                //Set the return object to equal nothing.
                $result = null;
            }
            catch(\Doctrine\ORM\ORMException $e)
            {
                //Response code Reset Content
                http_response_code(205);

                //Set the return object equal to the Transaction to be deleted.
                $result = $transactionToDelete;
            }

        }

        //Return either the data entered, null, or the transactionToDelete object.
        return $result;
    }

    /**
     * Validate Transaction Helper
     * @param array $reqData
     * @param Transaction $transaction
     * @param array $violations
     * @return bool
     */
    public static function populateTransaction(array $reqData, Transaction $transaction, array &$violations = [])
    {
        //Initialize the Serializer for code later down
        $serializer = new Serializer([new ObjectNormalizer()], []);

        try {
            $serializer->denormalize($reqData, Transaction::class, null,
                [ObjectNormalizer::OBJECT_TO_POPULATE => $transaction]);

            $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
            foreach($validator->validate($transaction) as $violationObject) {
                $violations[$violationObject->getPropertyPath()] = $violationObject->getMessage();
            }

        } catch (\Symfony\Component\Serializer\Exception\ExceptionInterface $e) {
            $violations['errorMessage'] = $e->getMessage();
        }

        //Return whether or not there were any validation violations when creating the Transaction
        return empty($violations);
    }
}