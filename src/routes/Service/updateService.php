<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->post('/api/service/updateService', function (Request $request, Response $response) {

    $serId = $request->getParam('ser_id');
    $serName = $request->getParam('ser_name');
    $serType = $request->getParam('ser_type');
    $serMinTime = $request->getParam('ser_minTime');

    try{

        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // check whether that service id exists or not
        $get_service_query = $db->prepare("select * from Service where serId=:ser_id");
        $get_service_query->execute(array(
            'ser_id' => $serId
        ));

        $row_count = $get_service_query->rowCount();

        if ($row_count == 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => "that service id doesn't exist"
            );
            return $response->withJson($data);
        }

        // update service
        $update_service_query = $db->prepare("CALL updateService(?, ?, ?, ?)");
        $update_service_query->bindParam(1, $serId, PDO::PARAM_INT);
        $update_service_query->bindParam(2, $serName, PDO::PARAM_STR);
        $update_service_query->bindParam(3, $serType, PDO::PARAM_INT);
        $update_service_query->bindParam(4, $serMinTime, PDO::PARAM_STR);
        $update = $update_service_query->execute();


        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'service is not updated'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'service is updated'
        );
        return $response->withJson($data);

    }
    catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }

});
