<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->delete('/api/hairdresser/deleteHairdresser', function (Request $request, Response $response) {

    $hdEmail = $request->getParam('hd_email');

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


        // delete hairdresser
        $delete_hairdresser_query = $db->prepare("CALL deleteHairdresser(?)");
        $delete_hairdresser_query->bindParam(1, $hdEmail, PDO::PARAM_STR);
        $delete = $delete_hairdresser_query->execute();


        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hairdresser is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hairdresser is deleted'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});