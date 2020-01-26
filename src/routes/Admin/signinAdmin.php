<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// admin sign in
$app->post('/api/admin/signinAdmin', function (Request $request, Response $response) {

    $adminEmail = $request->getParam('admin_email');
    $adminPassword = md5($request->getParam('admin_password'));

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $get_admin_query = $db->prepare(
            "select adminId, adminName, adminEmail, adminType, adminCreatedAt 
                      from Admin 
                      where adminEmail=:mail and adminPassword=:password");
        $get_admin_query->execute(array(
            'mail' => $adminEmail,
            'password' => $adminPassword
        ));

        //dönen satır sayısını belirtir
        $row_count = $get_admin_query->rowCount();

        if ($row_count == 1) {

            $admin = $get_admin_query->fetch(PDO::FETCH_OBJ); // TODO - password'u döndürmemesi lazım

            $data = array(
                'status' => 'ok',
                'data' => $admin,
                'message' => 'admin is signed in'
            );
            return $response->withJson($data);
        }
        else {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'password or email is incorrect'
            );
            return $response->withJson($data);
        }
    } catch (PDOException $e) {
        $data = array(
            'status' => 'error',
            'error_code' => 2,
            'message' => $e->getMessage()
        );
        return $response->withJson($data);
    }

});
