<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->put('/api/commentReply/updateCommentReply', function (Request $request, Response $response) {

    $replyId = $request->getParam('reply_id');
    $replyContent = $request->getParam('reply_content');


    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // update admin
        $update_commentReply_query = $db->prepare("CALL updateCommentReply(?, ?)");
        $update_commentReply_query->bindParam(1, $replyId, PDO::PARAM_STR);
        $update_commentReply_query->bindParam(2, $replyContent, PDO::PARAM_STR);
        $update = $update_commentReply_query->execute();


        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'comment reply is not updated'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'comment reply is updated'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});