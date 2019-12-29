<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get hairdresser by id
$app->get('/api/hairdresser/getHairdresserById', function (Request $request, Response $response){

    $hdId =$request->getQueryParams()["hd_id"];  //$app->request()->get('hd_id');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hairdresser_query = $db->prepare(
            "SELECT * 
                      FROM Hairdresser
                      WHERE hdId=:hd_id");
        $hairdresser_query->execute(array(
            'hd_id' => $hdId
        ));
        $hairdresser = $hairdresser_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hairdresser
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});