<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/hdPromotion/addHdPromotion', function (Request $request, Response $response) {

    $hdId = $request->getParam('hd_id');
    $serId = $request->getParam('ser_id');
    $hdPromoDiscount = $request->getParam('promo_discount');
    $hdPromoDuration = $request->getParam('promo_duration');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // add reservation
        $add_hdPromotion_query = $db->prepare("CALL addHdPromotion(?, ?, ?, ?)");
        $add_hdPromotion_query->bindParam(1, $hdId, PDO::PARAM_INT);
        $add_hdPromotion_query->bindParam(2, $serId, PDO::PARAM_INT);
        $add_hdPromotion_query->bindParam(3, $hdPromoDiscount, PDO::PARAM_STR);
        $add_hdPromotion_query->bindParam(4, $hdPromoDuration, PDO::PARAM_STR);
        $add = $add_hdPromotion_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'hdPromotion is not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'data' => $db->lastInsertId(),
            'message' => 'hdPromotion is added'
        );
        return $response->withJson($data);

    }
    catch(PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }

});
