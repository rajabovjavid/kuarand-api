<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get hdPromotion by hd_id and ser_id
$app->get('/api/hdPromotion/getHdPromotionByHd_SerId', function (Request $request, Response $response){

    $hdId =$request->getQueryParams()["hd_id"];
    $serId =$request->getQueryParams()["ser_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdPromotion_query = $db->prepare(
            "SELECT *
                      FROM HdPromotion
                      WHERE hdId=:hd_id and serId=:ser_id");
        $hdPromotion_query->execute(array(
            'hd_id' => $hdId,
            'ser_id' => $serId
        ));

        if($hdPromotion_query->rowCount() == 0){
            $data = array(
                'status' => 'ok',
                'data' => 0,
                'message' => "no promotion for this service"
            );
            return $response->withJson($data);
        }

        $hdPromotion = $hdPromotion_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdPromotion
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
