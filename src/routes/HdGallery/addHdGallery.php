<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/hdGallery/addHdGallery', function (Request $request, Response $response) {

    $hdId = $request->getParam('hd_id');
    $hdPhoto = fopen($request->getParam('hd_photo'), "rb");
    $hdPhotoPriority = $request->getParam('hdPhoto_priority');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // add reservation
        $add_hdGallery_query = $db->prepare("CALL addHdGallery(?, ?, ?)");
        $add_hdGallery_query->bindParam(1, $hdId, PDO::PARAM_INT);
        $add_hdGallery_query->bindParam(2, $hdPhoto, PDO::PARAM_LOB);
        $add_hdGallery_query->bindParam(3, $hdPhotoPriority, PDO::PARAM_INT);
        $add = $add_hdGallery_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'hdGallery is not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'data' => $db->lastInsertId(),
            'message' => 'hdGallery is added'
        );
        return $response->withJson($data);

    }
    catch(PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }

});