<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get all hdGalleries
$app->get('/api/hdGallery/getAllHdGalleries', function (Request $request, Response $response) {

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdGallery_query = $db->prepare("SELECT * FROM HdGallery");
        $hdGallery_query->execute();
        $hdGalleries = $hdGallery_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdGalleries
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});