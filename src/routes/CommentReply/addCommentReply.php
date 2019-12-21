<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/commentReply/addCommentReply', function (Request $request, Response $response) {

    $reservationId = $request->getParam('reservation_id');
    $replyContent = $request->getParam('reply_content');

    try{

        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // add CommentReply
        $add_commentReply_query = $db->prepare("CALL addCommentReply(?, ?)");
        $add_commentReply_query->bindParam(1, $reservationId, PDO::PARAM_INT);
        $add_commentReply_query->bindParam(2, $replyContent, PDO::PARAM_STR);

        $add = $add_commentReply_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'comment reply is not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'data' => $db->lastInsertId(),
            'message' => 'comment reply is added'
        );
        return $response->withJson($data);

    }
    catch (PDOException $e){
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }

});