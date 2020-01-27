<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/service/addService', function (Request $request, Response $response) {

    $serName = $request->getParam('ser_name');
    $serType = $request->getParam('ser_type');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // add service
        $add_service_query = $db->prepare("CALL addService(?, ?)");
        $add_service_query->bindParam(1, $serName, PDO::PARAM_STR);
        $add_service_query->bindParam(2, $serType, PDO::PARAM_INT);
        $add = $add_service_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'service is not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'data' => $add_service_query->fetch(PDO::FETCH_OBJ),
            'message' => 'service is added'
        );
        return $response->withJson($data);

    }
    catch(PDOException $e) {
        $data = array(
            'status' => 'error',
            'error_code' => 2,
            'message' => $e->getMessage()
        );
        return $response->withJson($data);
    }

});
