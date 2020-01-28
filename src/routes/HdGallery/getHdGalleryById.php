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
            "SELECT hdGalleryId, hdPhoto, hdPhotoPriority
                      FROM HdGallery
                      WHERE hdGalleryId=:hdGallery_id");
        $hdGallery_query->execute(array(
            'hdGallery_id' => $hdGalleryId
        ));

        /*$hdGallery_query->bindColumn(1, $hdGallery_Id, PDO::PARAM_INT);
        $hdGallery_query->bindColumn(2, $hdPhoto, PDO::PARAM_LOB);
        $hdGallery_query->bindColumn(3, $hdPhotoPriority, PDO::PARAM_INT);*/

        $hdGallery = $hdGallery_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdGallery
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});

