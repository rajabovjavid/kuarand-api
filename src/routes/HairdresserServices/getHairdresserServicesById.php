<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get customer by id
$app->get('/api/hairdresserServices/getHairdresserServicesById', function (Request $request, Response $response){

    $hdId =$request->getQueryParams()["hd_id"];
    $serId =$request->getQueryParams()["ser_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hairdresserServices_query = $db->prepare(
            "SELECT hdId, serId, serPrice, discountedPrice
                      FROM HairdresserServices
                      WHERE hdId=:hd_id AND serId=:ser_id");
        $hairdresserServices_query->execute(array(
            'hd_id' => $hdId,
            'ser_id' => $serId
        ));
        $hairdresserServices = $hairdresserServices_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hairdresserServices
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});