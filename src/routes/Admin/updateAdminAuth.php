<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->put('/api/admin/updateAdminAuth', function (Request $request, Response $response) {

    $adminId = $request->getParam('admin_id');
    $adminType = $request->getParam('admin_type');


    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // update admin
        $update_admin_query = $db->prepare("UPDATE ADMIN SET adminType='$adminType' WHERE adminId='$adminId'");

        $update = $update_admin_query->execute();


        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'admin is not updated'
            );
            return $response->withJson($data);
        }


        $data = array(
            'status' => 'ok',
            'message' => 'admin is updated'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});