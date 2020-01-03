<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get service by id
$app->get('/api/service/getServiceById', function (Request $request, Response $response){

    $serId =$request->getQueryParams()["ser_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $service_query = $db->prepare(
            "SELECT *
                      FROM Service
                      WHERE serId=:ser_id");
        $service_query->execute(array(
            'ser_id' => $serId
        ));
        $services = $service_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $services
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
