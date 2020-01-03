<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get all hdPromotions
$app->get('/api/hdPromotion/getAllHdPromotions', function (Request $request, Response $response) {

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdPromotion_query = $db->prepare("SELECT * FROM HdPromotion");
        $hdPromotion_query->execute();
        $hdPromotions = $hdPromotion_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdPromotions
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});