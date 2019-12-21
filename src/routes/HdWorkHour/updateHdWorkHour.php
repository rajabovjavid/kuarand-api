<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/hdWorkHour/updateHdWorkHour', function (Request $request, Response $response) {

    $hdId = $request->getParam('hd_id');
    $day = $request->getParam('work_day');
    $startHour = $request->getParam('start_hour');
    $finishHour = $request->getParam('finish_hour');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // check whether that hdWorkHour exists or not
        $get_hdWorkHour_query = $db->prepare("select * from HdWorkHour where hdId=:hd_id AND day=:work_day");
        $get_hdWorkHour_query->execute(array(
            'hd_id' => $hdId,
            'work_day' => $day
        ));

        $row_count = $get_hdWorkHour_query->rowCount();

        if ($row_count == 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => "that hdWorkHour doesn't exist"
            );
            return $response->withJson($data);
        }

        // update hdWorkHour
        $update_hdWorkHour_query = $db->prepare("CALL updateHdWorkHour(?, ?, ?, ?)");
        $update_hdWorkHour_query->bindParam(1, $hdId, PDO::PARAM_INT);
        $update_hdWorkHour_query->bindParam(2, $day, PDO::PARAM_INT);
        $update_hdWorkHour_query->bindParam(3, $startHour, PDO::PARAM_STR);
        $update_hdWorkHour_query->bindParam(4, $finishHour, PDO::PARAM_STR);
        $update = $update_hdWorkHour_query->execute();

        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hdWorkHour is not updated'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hdWorkHour is updated'
        );
        return $response->withJson($data);


    }

    catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }



});