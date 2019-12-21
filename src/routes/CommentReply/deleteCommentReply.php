<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->delete('/api/commentReply/deleteCommentReply', function (Request $request, Response $response) {

    $replyId= $request->getParam('reply_id');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // delete comment reply
        $delete_commentReply_query = $db->prepare("CALL deleteCommentReply(?)");
        $delete_commentReply_query->bindParam(1, $replyId, PDO::PARAM_INT);
        $delete = $delete_commentReply_query->execute();


        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'comment reply is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'comment reply is deleted'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});