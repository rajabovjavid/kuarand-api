<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->post('/api/admin/deleteAdmin', function (Request $request, Response $response) {

    $adminEmail = $request->getParam('admin_email');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // check whether that email exists or not
        $get_admin_query = $db->prepare("select * from Admin where adminEmail=:mail");
        $get_admin_query->execute(array(
            'mail' => $adminEmail
        ));

        $row_count = $get_admin_query->rowCount();

        if ($row_count == 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => "that email doesn't exist"
            );
            return $response->withJson($data);
        }


        // delete admin
        $delete_admin_query = $db->prepare("CALL deleteAdmin(?)");
        $delete_admin_query->bindParam(1, $adminEmail, PDO::PARAM_STR);
        $delete = $delete_admin_query->execute();


        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'admin is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'admin is deleted'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});