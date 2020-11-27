<?php
use Doctrine\ORM\EntityManager;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TaskController
{

    public static function getTask(EntityManager $em, array $requestData)
    {

    }

    public static function postTask(EntityManager $em, array $requestData, Task $newTask)
    {

    }

    public static function putTask(EntityManager $em, array $newTaskValue, ?Task $oldTaskValueFromDB)
    {

    }

    public static function deleteTask(EntityManager $em, array $requestedData, ?Task $taskToDelete)
    {

    }


    /********************************
     *        HELPER METHODS        *
     ********************************/

    public static function populateTask(array $requestedData, Task &$task, array &$violations = []):bool
    {
        return true;
    }
}