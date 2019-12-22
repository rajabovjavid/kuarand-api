<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/hdWorkHour/addHdWorkHour', function (Request $request, Response $response) {

    $hdId = $request->getParam('hd_id');
    $day = $request->getParam('work_day');
    $starHour = $request->getParam('start_hour');
    $finishHour = $request->getParam('finish_hour');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // add hdWorkHour
        $add_hdWorkHour_query = $db->prepare("CALL addHdWorkHour(?, ?, ?, ?)");
        $add_hdWorkHour_query->bindParam(1, $hdId, PDO::PARAM_INT);
        $add_hdWorkHour_query->bindParam(2, $day, PDO::PARAM_INT);
        $add_hdWorkHour_query->bindParam(3, $starHour, PDO::PARAM_STR);
        $add_hdWorkHour_query->bindParam(4, $finishHour, PDO::PARAM_STR);
        $add = $add_hdWorkHour_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'hdWorkHour is not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hdWorkHour is added'
        );
        return $response->withJson($data);

    }

    catch(PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }



});
