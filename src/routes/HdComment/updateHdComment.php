<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->put('/api/hdComment/updateHdComment', function (Request $request, Response $response) {

    $reservationId = $request->getParam('reservation_id');
    $commentContent = $request->getParam('comment_content');
    $commentRate = $request->getParam('comment_rate');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // update hd comment
        $update_hdComment_query = $db->prepare("CALL updateHdComment(?, ?, ?)");
        $update_hdComment_query->bindParam(1, $reservationId, PDO::PARAM_INT);
        $update_hdComment_query->bindParam(2, $commentContent, PDO::PARAM_STR);
        $update_hdComment_query->bindParam(3, $commentRate, PDO::PARAM_STR);
        $update = $update_hdComment_query->execute();


        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'hd comment is not updated'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hd comment is updated'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});