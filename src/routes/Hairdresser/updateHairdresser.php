<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->put('/api/hairdresser/updateHairdresser', function (Request $request, Response $response) {

    $hdName = $request->getParam('hd_name');
    $hdEmail = $request->getParam('hd_email');
    $hdPassword = md5($request->getParam('hd_password'));
    $hdType = $request->getParam('hd_type');
    $hdStatus = $request->getParam('hd_status');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // check whether that email exists or not
        $get_hairdresser_query = $db->prepare("select * from Hairdresser where hdEmail=:mail");
        $get_hairdresser_query->execute(array(
            'mail' => $hdEmail
        ));
        $row_count = $get_hairdresser_query->rowCount();

        if ($row_count == 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => "that email doesn't exist"
            );
            return $response->withJson($data);
        }


        // update hairdresser
        $update_hairdresser_query = $db->prepare("CALL updateHairdresser(?, ?, ?, ?, ?)");
        $update_hairdresser_query->bindParam(1, $hdEmail, PDO::PARAM_STR);
        $update_hairdresser_query->bindParam(2, $hdName, PDO::PARAM_STR);
        $update_hairdresser_query->bindParam(3, $hdPassword, PDO::PARAM_STR);
        $update_hairdresser_query->bindParam(4, $hdType, PDO::PARAM_INT);
        $update_hairdresser_query->bindParam(5, $hdStatus, PDO::PARAM_INT);
        $update = $update_hairdresser_query->execute();


        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hairdresser is not updated'
            );
            return $response->withJson($data);
        }

        $updatedHairdresser = $update_hairdresser_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $updatedHairdresser,
            'message' => 'hairdresser is updated'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        $data = array(
            'status' => 'error',
            'error_code' => 3,
            'message' => $e->getMessage()
        );
        return $response->withJson($data);
    }
});