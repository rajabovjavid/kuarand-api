<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get hdPromotion by id
$app->get('/api/hdPromotion/getHdPromotionById', function (Request $request, Response $response){

    $hdPromoId =$request->getQueryParams()["hdPromo_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdPromotion_query = $db->prepare(
            "SELECT *
                      FROM HdPromotion
                      WHERE hdPromoId=:hdPromo_id");
        $hdPromotion_query->execute(array(
            'hdPromo_id' => $hdPromoId
        ));
        $hdPromotions = $hdPromotion_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdPromotions
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
