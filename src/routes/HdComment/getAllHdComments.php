<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get all customers
$app->get('/api/hdComment/getAllHdComments', function (Request $request, Response $response) {

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdComment_query = $db->prepare("SELECT reservationId, commentContent, commentDate, commentRate FROM HdComment");
        $hdComment_query->execute();
        $hdComment = $hdComment_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdComment
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});