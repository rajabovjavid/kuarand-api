<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// filter hd work hour by hairdresser id
$app->get('/api/hdWorkHour/filterHdWorkHourWithHd', function (Request $request, Response $response){

    $hdId =$request->getQueryParams()["hd_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdWorkHour_query = $db->prepare(
            "SELECT day, startHour, finishHour
                      FROM HdWorkHour
                      WHERE hdId=:hd_id");
        $hdWorkHour_query->execute(array(
            "hd_id" => $hdId
        ));
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
