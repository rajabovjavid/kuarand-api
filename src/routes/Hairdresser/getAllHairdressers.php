<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get all hairdressers
$app->get('/api/hairdresser/getAllHairdressers', function (Request $request, Response $response) {

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hairdresser_query = $db->prepare("SELECT hdId, hdName, hdEmail, hdType, hdStatus, hdCreatedAt, hdRating FROM Hairdresser");
        $hairdresser_query->execute();
        $hairdressers = $hairdresser_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hairdressers
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});