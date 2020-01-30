<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->put('/api/admin/updateAdmin', function (Request $request, Response $response) {

    $adminName = $request->getParam('admin_name');
    $adminEmail = $request->getParam('admin_email');
    $adminPassword = ($request->getParam('admin_password') == "") ? "" : md5($request->getParam('admin_password'));
    $adminType = $request->getParam('admin_type');


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


        // update admin
        $update_admin_query = $db->prepare("CALL updateAdmin(?, ?, ?, ?)");
        $update_admin_query->bindParam(1, $adminEmail, PDO::PARAM_STR);
        $update_admin_query->bindParam(2, $adminName, PDO::PARAM_STR);
        $update_admin_query->bindParam(3, $adminType, PDO::PARAM_INT);
        $update_admin_query->bindParam(4, $adminPassword, PDO::PARAM_STR);
        $update = $update_admin_query->execute();


        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'admin is not updated'
            );
            return $response->withJson($data);
        }

        $updatedAdmin = $update_admin_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            "data" => $updatedAdmin,
            'message' => 'admin is updated'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});