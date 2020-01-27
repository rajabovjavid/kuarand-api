<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get customer by id
$app->get('/api/hairdresserServices/getHairdresserServiceBySerId', function (Request $request, Response $response){

    $serId =$request->getQueryParams()["ser_id"];
    $hdId =$request->getQueryParams()["hd_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hairdresserServices_query = $db->prepare(
            "SELECT hdId, serId, serPrice, discountedPrice, serName, serType, serMinTime
                      FROM hairdressersservicesview
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