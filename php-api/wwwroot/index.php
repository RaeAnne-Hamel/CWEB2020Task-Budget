<?php
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

require_once '../init-db.php';
require_once '../entities/Task.php';
require_once 'TaskController.php';

/****************************************************************************
 * Task API
 *
 * GET -- get all tasks
 * POST -- post new task
 * PUT -- update a task if completed or something date or detail or
 *        something needed to be update
 * DElETE -- may not need the task
 * OPTIONS: - required
 *
 ****************************************************************************/

// need the php://input for raw string for PUT and DELETE
$rawDataFile = file_get_contents('php://input');

/**
 * this is what will if fetched
 * if it is empty
 * call the Request super global
 * to grab it from the webserver
 */
$requestedData = !empty($rawdatafile) ? json_decode($rawDataFile, true) : $_REQUEST;

$encodedResult = ' ';

switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        $encodedResult = TaskController::getTask($entityManager, $requestedData);
        break;
    case 'POST':
        $encodedResult = TaskController::postTask($entityManager, $requestedData, new Task());
        break;
    case 'PUT':
        $encodedResult = TaskController::putTask($entityManager, $requestedData, $entityManager ->
        find(Task::class, $requestedData['id']));
        break;
    case 'DELETE':
        $encodedResult = TaskController::deleteTask($entityManager, $requestedData,
            $entityManager ->find(Task::class, $requestedData['id']));
        break;
    case 'OPTIONS':
        //handles options requests usually for a cors preflight security check
        http_response_code(204);
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-type' );
        header('Access-Control-Max-Age: 86400');
        break;
    default:
        header('HTTP/1.1 405 Method Not Allowed');
}

//handles the format for browser -- not html but json
header('Access-Control-Allow-Origin:*');
header('content-type:application/json');

if(http_response_code() != 204)
{
    // need a serializer
    $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncode()]);

    //echo the task array
    echo $serializer->serialize($encodedResult, 'json');
}

