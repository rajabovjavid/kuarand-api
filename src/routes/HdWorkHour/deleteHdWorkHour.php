<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/hdWorkHour/deleteHdWorkHour', function (Request $request, Response $response) {

    $hdId= $request->getParam('hd_id');
    $day= $request->getParam('work_day');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // check whether that hdWorkHour id exists or not
        $get_hdWorkHour_query = $db->prepare('select * from HdWorkHour where hdId=:hd_id AND day=:work_day');
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

        // delete reservation
        $delete_hdWorkHour_query = $db->prepare("CALL deleteHdWorkHour(?, ?)");
        $delete_hdWorkHour_query->bindParam(1, $hdId, PDO::PARAM_INT);
        $delete_hdWorkHour_query->bindParam(2, $day, PDO::PARAM_INT);
        $delete = $delete_hdWorkHour_query->execute();

        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hdWorkHour is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hdWorkHour is deleted'
        );
        return $response->withJson($data);
    }

    catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }

});