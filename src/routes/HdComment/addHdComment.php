<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/hdComment/addHdComment', function (Request $request, Response $response) {

    $reservationId = $request->getParam('reservation_id');
    $commentContent = $request->getParam('comment_content');
    $commentRate = $request->getParam('comment_rate');

    try{

        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // add hd comment
        $add_hdReply_query = $db->prepare("CALL addHdComment(?, ?, ?)");
        $add_hdReply_query->bindParam(1, $reservationId, PDO::PARAM_INT);
        $add_hdReply_query->bindParam(2, $commentContent, PDO::PARAM_STR);
        $add_hdReply_query->bindParam(3, $commentRate, PDO::PARAM_STR);
        $add = $add_hdReply_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'hd comment is not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hd comment is added'
        );
        return $response->withJson($data);

    }
    catch (PDOException $e){
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }

});
