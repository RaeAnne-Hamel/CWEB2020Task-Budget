<?php
use Doctrine\ORM\EntityManager;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TaskController
{

    public static function getTask(EntityManager $em, array $requestData)
    {
        /**
         * Want the option to be able to sort through the api
         * 1. list all the field options
         * 2. create sort using the key word sort
         * 3. create the order
         * 4. create the query
         **/
        $fieldOptions = ['id', 'startDate', 'dueDate', 'completedDate', 'type', 'urgency', 'detail'];

        //task table alias is t
        $sort = isset($_GET['sort']) && in_array($_GET['sort']) ? 't'.$_GET['sort'] : 't.id';

        $order =isset($_GET['order']) && in_array($_GET['order'], ['asc', 'desc']) ?
                    strtoupper($_GET['order']) : 'ASC';

        $qb = $em ->createQueryBuilder();

        $qb -> select('t')
            -> from('Task', 't')
            -> orderBy($sort, $order);

        $taskArray = $qb ->getQuery()
                         -> getArrayResult();

        if(empty($taskArray))
        {
            http_response_code(404); // response code of item not found
        }

        return $taskArray;

    }

    public static function postTask(EntityManager $em, array $requestData, Task $newTask)
    {

    }

    public static function putTask(EntityManager $em, array $newTaskValue, ?Task $oldTaskValueFromDB)
    {

    }

    public static function deleteTask(EntityManager $em, array $requestedData, ?Task $taskToDelete)
    {
        $encodeResult = ' ';

        if(is_null($taskToDelete))
        {
            http_response_code(404);
            $encodeResult = $requestedData;
        }
        else
        {
            if($requestedData['type'] == $taskToDelete -> getType() &&)
        }
    }


    /********************************
     *        HELPER METHODS        *
     ********************************/

    public static function populateTask(array $requestedData, Task &$task, array &$violations = []):bool
    {
        return true;
    }
}