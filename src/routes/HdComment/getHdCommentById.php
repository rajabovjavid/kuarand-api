<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get customer by id
$app->get('/api/hdComment/getHdCommentById', function (Request $request, Response $response){

    $reservationId =$request->getQueryParams()["reservation_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdComment_query = $db->prepare(
            "SELECT reservationId, commentContent, commentDate, commentRate 
                      FROM HdComment
                      WHERE reservationId=:reservation_id");
        $hdComment_query->execute(array(
            'reservation_id' => $reservationId
        ));
        $hdComments = $hdComment_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdComments
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});