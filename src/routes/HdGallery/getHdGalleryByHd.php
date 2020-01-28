<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get photo
$app->get('/api/hdGallery/getHdGalleryByHd', function (Request $request, Response $response) {

    $hdId = $request->getQueryParams()["hd_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();


        $hdGallery_query = $db->prepare("SELECT hdGalleryId, hdPhoto, hdPhotoPriority FROM HdGallery WHERE hdId=:hd_id");
        $hdGallery_query->execute(array(
            'hd_id' => $hdId
        ));

        $hdGallery_query->bindColumn(1, $photo, PDO::PARAM_LOB);
        $hdGalleries = $hdGallery_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' =>  $hdGalleries
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});