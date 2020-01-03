<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get photo
$app->get('/api/hdGallery/getHdGalleryPhotoById', function (Request $request, Response $response) {

    $hdGalleryId = $request->getQueryParams()["hdGallery_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();


        $hdGallery_query = $db->prepare("SELECT hdPhoto FROM HdGallery WHERE hdGalleryId=:hdGallery_id");
        $hdGallery_query->execute(array(
            'hdGallery_id' => $hdGalleryId
        ));

        $hdGallery_query->bindColumn(1, $photo, PDO::PARAM_LOB);
        $hdGallery_query->fetch(PDO::FETCH_BOUND);
        /*header("Content-Type:image/jpeg");
        echo '<img src="data:image/jpeg;base64,'.base64_encode($photo).'"/>';*/

        $data = array(
            'status' => 'ok',
            'data' =>  base64_encode($photo)
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});