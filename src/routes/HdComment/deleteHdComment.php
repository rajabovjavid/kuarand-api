<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->delete('/api/hdComment/deleteHdComment', function (Request $request, Response $response) {

    $reservationId= $request->getParam('reservation_id');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // delete hd comment
        $delete_hdComment_query = $db->prepare("CALL deleteHdComment(?)");
        $delete_hdComment_query->bindParam(1, $reservationId, PDO::PARAM_INT);
        $delete = $delete_hdComment_query->execute();


        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hd comment is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hd comment is deleted'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});