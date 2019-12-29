<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get hairdressers by status
$app->get('/api/hairdresser/getHairdressersByStatus', function (Request $request, Response $response){

    $hdStatus =$request->getQueryParams()["hd_status"];  //$app->request()->get('hd_id');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        if($hdStatus==3) {
            $hairdresser_query = $db->prepare(
                "SELECT hdId, hdName, hdEmail, hdType, hdStatus, hdCreatedAt, hdRating 
                          FROM Hairdresser
                          WHERE hdStatus=3");
        }
        else{
            $hairdresser_query = $db->prepare(
                "SELECT hdId, hdName, hdEmail, hdType, hdStatus, hdCreatedAt, hdRating 
                          FROM Hairdresser
                          WHERE hdStatus<>3");
        }

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