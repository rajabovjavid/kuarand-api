<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get all hd work hours
$app->get('/api/hdWorkHour/getAllHdWorkHours', function (Request $request, Response $response) {

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdWorkHour_query = $db->prepare("SELECT * FROM HdWorkHour");
        $hdWorkHour_query->execute();
        $hdWorkHours = $hdWorkHour_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdWorkHours
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});