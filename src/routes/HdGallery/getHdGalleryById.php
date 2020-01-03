<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get hdGallery by id
$app->get('/api/hdGallery/getHdGalleryById', function (Request $request, Response $response){

    $hdGalleryId =$request->getQueryParams()["hdGallery_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdGallery_query = $db->prepare(
            "SELECT *
                      FROM HdGallery
                      WHERE hdGalleryId=:hdGallery_id");
        $hdGallery_query->execute(array(
            'hdGallery_id' => $hdGalleryId
        ));
        $hdGalleries = $hdGallery_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdGalleries
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});

