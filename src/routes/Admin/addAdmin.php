<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/admin/addAdmin', function (Request $request, Response $response) {

    $adminName = $request->getParam('admin_name');
    $adminEmail = $request->getParam('admin_email');
    $adminPassword = md5($request->getParam('admin_password'));
    $adminType = $request->getParam('admin_type');


    try {
        // Get DB Object
        $db_obj = new db();
        // Connect
        $db = $db_obj->connect();

        // check whether that email is used or not
        $get_admin_query = $db->prepare("select * from Admin where adminEmail=:mail");
        $get_admin_query->execute(array(
            'mail' => $adminEmail
        ));

        $row_count = $get_admin_query->rowCount();

        if ($row_count != 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'used email'
            );
            return $response->withJson($data);
        }


        // add admin
        $add_admin_query = $db->prepare("CALL addAdmin(?, ?, ?, ?)");
        $add_admin_query->bindParam(1, $adminName, PDO::PARAM_STR);
        $add_admin_query->bindParam(2, $adminEmail, PDO::PARAM_STR);
        $add_admin_query->bindParam(3, $adminType, PDO::PARAM_INT);
        $add_admin_query->bindParam(4, $adminPassword, PDO::PARAM_STR);
        $add = $add_admin_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'admin is not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'admin is added'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});