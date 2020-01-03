<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get admin by id
$app->get('/api/admin/getAdminById', function (Request $request, Response $response){

    $adminId =$request->getQueryParams()["admin_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $admin_query = $db->prepare(
            "SELECT adminId, adminName, adminEmail, adminType, adminCreatedAt 
                      FROM Admin
                      WHERE adminId=:admin_id");
        $admin_query->execute(array(
            'admin_id' => $adminId
        ));
        $admins = $admin_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $admins
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});