<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->put('/api/hairdresserServices/updateHairdresserService', function (Request $request, Response $response) {

    $hdId = $request->getParam('hd_id');
    $serviceId = $request->getParam('ser_id');
    $servicePrice = $request->getParam('ser_price');
    $serviceMinTime = $request->getParam('ser_min_time');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();


        // update hairdresser service
        $update_hairdresserServices_query = $db->prepare("CALL updateHairdresserServices(?, ?, ?, ?)");
        $update_hairdresserServices_query->bindParam(1, $hdId, PDO::PARAM_INT);
        $update_hairdresserServices_query->bindParam(2, $serviceId, PDO::PARAM_INT);
        $update_hairdresserServices_query->bindParam(3, $servicePrice, PDO::PARAM_STR);
        $update_hairdresserServices_query->bindParam(4, $serviceMinTime, PDO::PARAM_INT);
        $update = $update_hairdresserServices_query->execute();

        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'hairdresser service is not updated'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hairdresser service is updated'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});