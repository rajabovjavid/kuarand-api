<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get hdWorkHour by id
$app->get('/api/hdWorkHour/getHdWorkHourById', function (Request $request, Response $response){

    $hdId =$request->getQueryParams()["hd_id"];
    $day =$request->getQueryParams()["day"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdWorkHour_query = $db->prepare(
            "SELECT *
                      FROM HdWorkHour
                      WHERE hdId=:hd_id AND day=:day");
        $hdWorkHour_query->execute(array(
            'hd_id' => $hdId,
            'day' => $day
        ));
        $hdWorkHours = $hdWorkHour_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdWorkHours
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
