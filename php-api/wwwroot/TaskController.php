<?php
use Doctrine\ORM\EntityManager;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TaskController
{
    /**
     * This Method will attempt to bring back all Task abject and property values from the database
     * and be able to sort them
     * @param EntityManager $em
     * @param array $requestData -- array containing all the task object and their values we want to display
     * @return array|int|string -- returns the objects to be displayed
     */
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
        //sort by pointer -- if no pointer is selected then sort by id
        $sort = isset($_GET['sort']) && in_array($_GET['sort'], $fieldOptions) ? 't'.$_GET['sort'] : 't.id';

        // order by pointer(either asc or desc) if no pointer is selected then order by asc
        $order = isset($_GET['order']) && in_array($_GET['order'], ['asc', 'desc']) ?
                   strtoupper($_GET['order']) : 'ASC';

        $qb = $em ->createQueryBuilder();

        //create query: select all from task table sort by pointer then order
        $qb ->select('t')
            ->from('Task', 't')
            -> orderBy($sort, $order);

        if(isset($requestData['id']))
        {
            //add a parameter ':ident' to act a placeholder
            $qb->where('t.id = :ident')
                // the value for the placeholder
                ->setParameter('ident',$requestData['id']);

        }

        $taskArray = $qb ->getQuery()
                         -> getArrayResult();


        if(empty($taskArray))
        {
            http_response_code(404); // response code of item not found
        }

        //return the task array
        return $taskArray;

    }

    /**
     * this Method will create a new Task object we applied values we wanted and save it to the database
     * @param EntityManager $em
     * @param array $requestData -- the values we want to apply to a task object
     * @param Task $newTask -- a new task object
     * @return array|Task|null -- a new created task object containing the values we wanted
     */
    public static function postTask(EntityManager $em, array $requestData, Task $newTask)
    {
        //Json Result
        $encodedResult = null;

        //If any error occurs store it in an array
        $violations = [];

        //if populateTask is successful
        if(self::populateTask($requestData, $newTask, $violations)){

            try {
                //save to the in-memory database
                $em->persist($newTask);

                //saves all to sql database
                $em -> flush();

                //response code: was create
                http_response_code(201);

                //send task object to the browser
                $encodedResult = $newTask;

            } catch (\Doctrine\ORM\ORMException $e) {

                //database error occurs the status code 500 is sent
                http_response_code(500); //Means: Internal Server Error
                $encodedResult = ['errorMessage' => $e -> getMessage()];

            }
        }else{
            //populateTask is unsuccessful
            http_response_code(422); //Unprocessable entity
            $encodedResult = $violations; //send back the violations array
        }

        //return json result
        return $encodedResult;
    }

    /**
     * The Method will update a specific task's values and save it to the database
     * NOTE: this is coded very similar to postTask
     * @param EntityManager $em
     * @param array $newTaskValue
     * @param Task|null $oldTaskValueFromDB
     * @return array|Task
     */
    public static function putTask(EntityManager $em, array $newTaskValue, ?Task $oldTaskValueFromDB)
    {
        $encodedResult = null;
        $violations = [];

        if(is_null($oldTaskValueFromDB)){
            http_response_code(404);
            $encodedResult = $newTaskValue;

        }elseif(self::populateTask($newTaskValue, $oldTaskValueFromDB, $violations)){
            try {
                //update the database with new values
                $em -> flush();

                //send back task object to the browser
                $encodedResult = $oldTaskValueFromDB;

            } catch (\Doctrine\ORM\ORMException $e) {

                http_response_code(500); //Means: Internal Server Error
                $encodedResult = ['errorMessage' => $e -> getMessage()];

            }
        }else{

            http_response_code(422); //Unprocessable entity
            $encodedResult = $violations;
        }
        return $encodedResult;
    }

    /**
     * This Method will remove a specific task from the database
     * @param EntityManager $em
     * @param array $requestedData -- array containing the value to delete
     * @param Task|null $taskToDelete -- specific task object
     * @return array|string[]|Task
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public static function deleteTask(EntityManager $em, array $requestedData, ?Task $taskToDelete)
    {
        //json object
        $encodeResult = null;

        //if the object does not exist
        if(is_null($taskToDelete))
        {
            //send a response - not found
            http_response_code(404);
            $encodeResult = $requestedData;
        }
        else
        {
            //if the the type and id match the object type and id to be delete
            if($requestedData['type'] == $taskToDelete -> getType() &&
                $requestedData['id'] == $taskToDelete -> getId())
            {
                try{
                    //remove the object
                    $em ->remove($taskToDelete);

                    //refresh the database
                    $em ->flush();

                    //response code: server is asking browser to clear the form/request
                    http_response_code(205);
                    $encodeResult = null;
                }
                catch (\Doctine\Orm\OrmException $e)
                {
                    http_response_code(422);
                    $encodeResult = $taskToDelete;
                }
            }
            else
            {
                http_response_code( 422);
                $encodeResult = ['errorMessage' =>'The Task information does not correspond with the id.'];
            }
        }

        //send back json object
        return $encodeResult;
    }


    /********************************
     *        HELPER METHODS        *
     *******************************/
    /**
     * The Method will return true or false to signify whether or not a method should add/update a task object
     * to the database
     * @param array $requestedData -- associative array containing the values to copy the task object
     * @param Task $task -- pass by reference -- existing task object to be populated with
     * @param array $violations -- passing by reference -- array to store any violations
     * @return bool -- true success and populate task object / false fail send by violation array
     */
    public static function populateTask(array $requestedData, Task &$task, array &$violations = []):bool
    {
        $serializer = new Serializer([new ObjectNormalizer()], []);

        try {
            //copy the values from requestedData into Task, but skip  the id attribute
            $serializer->denormalize($requestedData, Task::class, null,
                [ObjectNormalizer::OBJECT_TO_POPULATE => $task,
                    ObjectNormalizer::IGNORED_ATTRIBUTES => ['id']]);

            //create a new validator
            $validator = \Symfony\Component\Validator\Validation::createValidatorBuilder()->
            enableAnnotationMapping()->getValidator();

            //for each value in the new populate -- going to validate them
            foreach ($validator->validate($task) as $validationObject){

                // the property name is the key and error message of the violation object
                //get the violation object  message
                $violations[$validationObject->getPropertyPath()] = $validationObject -> getMessage();
            }


        } catch (\Symfony\Component\Serializer\Exception\ExceptionInterface $e) {

            // add the error to the violations array
            $violations['errorMessage'] = $e -> getMessage();
        }

        // if violations array is empty: success(true) else failure (false)
        return empty($violations);
    }
}