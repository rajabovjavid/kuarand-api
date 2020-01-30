<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get all customers
$app->get('/api/hairdresserServices/getAllHairdresserServices', function (Request $request, Response $response) {

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hairdresserServices_query = $db->prepare("SELECT hdId, serId, serPrice, discountedPrice, serMinTime FROM HairdresserServices");
        $hairdresserServices_query->execute();
        $hairdresserServices = $hairdresserServices_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hairdresserServices
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});