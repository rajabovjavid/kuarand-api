<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get commentReply by id
$app->get('/api/commentReply/getCommentReplyById', function (Request $request, Response $response){

    $replyId =$request->getQueryParams()["reply_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $commentReply_query = $db->prepare(
            "SELECT *
                      FROM CommentReply
                      WHERE replyId =:reply_id");
        $commentReply_query->execute(array(
            'reply_id' => $replyId
        ));
        $commentReplies = $commentReply_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $commentReplies
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});

