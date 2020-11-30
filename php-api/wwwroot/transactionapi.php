<?php
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

require_once '../init-db.php';
require_once '../entities/Transaction.php';
require_once 'TransactionController.php';

$dataFile = file_get_contents('php://input');
$requestData = !empty($dataFile) ? json_decode($dataFile, true) : $_REQUEST;

$resultToEncode='';
switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        $resultToEncode = TransactionController::getTransactions($entityManager, $requestData);
        break;

    case 'POST':
        $resultToEncode = TransactionController::postTransaction($entityManager, $requestData, new Transaction());
        break;

    case 'PUT':
        $resultToEncode = TransactionController::putTransaction($entityManager, $requestData,
            $entityManager->find(Transaction::class,$requestData['id']));
        break;

    case 'DELETE':
        $resultToEncode = TransactionController::deleteTransaction($entityManager, $requestData,
            $entityManager->find(Transaction::class,$requestData['id']));
        break;

    default:
        header('http/1.1 405 Method Not Allowed');
}

header('Content-type:application/json');

if(http_response_code() != 204) {
    //instantiate a new serializer with a normalizer and an encoder
    $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

    //echo out the array of student objects in json format
    echo $serializer->serialize($resultToEncode, 'json');
}