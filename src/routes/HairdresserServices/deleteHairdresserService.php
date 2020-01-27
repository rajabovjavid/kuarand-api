<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->delete('/api/hairdresserServices/deleteHairdresserService', function (Request $request, Response $response) {

    $hdId = $request->getParam('hd_id');
    $serviceId = $request->getParam('ser_id');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();


        // delete hairdresser service
        $delete_hairdresserServices_query = $db->prepare("CALL deleteHairdresserServices(?, ?)");
        $delete_hairdresserServices_query->bindParam(1, $hdId, PDO::PARAM_INT);
        $delete_hairdresserServices_query->bindParam(2, $serviceId, PDO::PARAM_INT);

        $delete = $delete_hairdresserServices_query->execute();

        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'hairdresser service is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hairdresser service is deleted'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});