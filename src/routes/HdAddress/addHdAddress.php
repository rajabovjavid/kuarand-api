<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/hdAddress/addHdAddress', function (Request $request, Response $response) {

    $hdId = $request->getParam('hd_id');
    $hdAddressCity = $request->getParam('address_city');
    $hdAddressRegion = $request->getParam('address_region');
    $hdAddressNeigh = $request->getParam('address_neigh');
    $hdAddressStreet = $request->getParam('address_street');
    $hdAddressOtherInfo = $request->getParam('address_other');

    try {
        // Get DB Object
        $db_obj = new db();
        // Connect
        $db = $db_obj->connect();

        // add hd address
        $add_hdAddress_query = $db->prepare("CALL addHdAddress(?, ?, ?, ?, ?, ?)");
        $add_hdAddress_query->bindParam(1, $hdId, PDO::PARAM_INT);
        $add_hdAddress_query->bindParam(2, $hdAddressCity, PDO::PARAM_STR);
        $add_hdAddress_query->bindParam(3, $hdAddressRegion, PDO::PARAM_STR);
        $add_hdAddress_query->bindParam(4, $hdAddressNeigh, PDO::PARAM_STR);
        $add_hdAddress_query->bindParam(5, $hdAddressStreet, PDO::PARAM_STR);
        $add_hdAddress_query->bindParam(6, $hdAddressOtherInfo, PDO::PARAM_STR);
        $add = $add_hdAddress_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hd address is not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hd adress is added'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
