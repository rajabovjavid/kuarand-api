<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->put('/api/hdAddress/updateHdAddress', function (Request $request, Response $response) {

    $hdId = $request->getParam('hd_id');
    $hdAddressCity = $request->getParam('address_city');
    $hdAddressRegion = $request->getParam('address_region');
    $hdAddressNeigh = $request->getParam('address_neigh');
    $hdAddressStreet = $request->getParam('address_street');
    $hdAddressOther = $request->getParam('address_other');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // add hd address
        $update_hdAddress_query = $db->prepare("CALL updateHdAddress(?, ?, ?, ?, ?, ?)");
        $update_hdAddress_query->bindParam(1, $hdId, PDO::PARAM_INT);
        $update_hdAddress_query->bindParam(2, $hdAddressCity, PDO::PARAM_STR);
        $update_hdAddress_query->bindParam(3, $hdAddressRegion, PDO::PARAM_STR);
        $update_hdAddress_query->bindParam(4, $hdAddressNeigh, PDO::PARAM_STR);
        $update_hdAddress_query->bindParam(5, $hdAddressStreet, PDO::PARAM_STR);
        $update_hdAddress_query->bindParam(6, $hdAddressOther, PDO::PARAM_STR);
        $update = $update_hdAddress_query->execute();

        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'hd address is not updated'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'data' => $hdId,
            'message' => 'hd address is updated'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});