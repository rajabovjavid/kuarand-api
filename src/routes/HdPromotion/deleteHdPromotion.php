<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->delete('/api/hdPromotion/deleteHdPromotion', function (Request $request, Response $response) {

    $hdPromoId= $request->getParam('hdPromo_id');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // check whether that hdPromotion id exists or not
        $get_hdPromotion_query = $db->prepare('select * from HdPromotion where hdPromoId=:hdPromo_id');
        $get_hdPromotion_query->execute(array(
            'hdPromo_id' => $hdPromoId
        ));

        $row_count = $get_hdPromotion_query->rowCount();

        if ($row_count == 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => "that hdPromotion doesn't exist"
            );
            return $response->withJson($data);
        }

        // delete hdPromotion
        $delete_hdPromotion_query = $db->prepare("CALL deleteHdPromotion(?)");
        $delete_hdPromotion_query->bindParam(1, $hdPromoId, PDO::PARAM_INT);
        $delete = $delete_hdPromotion_query->execute();

        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hdPromotion is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hdPromotion is deleted'
        );
        return $response->withJson($data);
    }

    catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }

});