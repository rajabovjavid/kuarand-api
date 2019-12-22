<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->delete('/api/hdAddress/deleteHdAddress', function (Request $request, Response $response) {

    $hdId= $request->getParam('hd_id');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // delete hd address
        $delete_hdAddress_query = $db->prepare("CALL deleteHdAddress(?)");
        $delete_hdAddress_query->bindParam(1, $hdId, PDO::PARAM_INT);
        $delete = $delete_hdAddress_query->execute();


        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hd address is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hd address is deleted'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});