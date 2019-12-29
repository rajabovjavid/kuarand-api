<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// filter hairdressers by name
$app->get('/api/hairdresser/filterHairdressersWithName', function (Request $request, Response $response){

    $hdName =$request->getQueryParams()["hd_name"];  //$app->request()->get('hd_id');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hairdresser_query = $db->prepare(
            "SELECT hdId, hdName, hdEmail, hdType, hdStatus, hdCreatedAt, hdRating 
                      FROM Hairdresser
                      WHERE hdName LIKE ?");
        $hairdresser_query->execute(array("%$hdName%"));
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