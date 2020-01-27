<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// filter services by hairdresser
$app->get('/api/hairdresserServices/filterHairdresserServicesByHd', function (Request $request, Response $response) {

    $hdId =$request->getQueryParams()["hd_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $service_query = $db->prepare(
            "SELECT serId, serPrice, discountedPrice, serName, serType, serMinTime
                      FROM hairdressersservicesview
                      WHERE hdId=:hd_id");
        $service_query->execute(array(
            'hd_id' => $hdId
        ));
        $services = $service_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $services
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
