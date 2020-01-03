<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get customer by id
$app->get('/api/hdAddress/getHdAddressById', function (Request $request, Response $response){

    $hdId =$request->getQueryParams()["hd_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdAddress_query = $db->prepare(
            "SELECT hdId, hdAddressCity, hdAddressRegion, hdAddressNeighborhood, hdAddressStreet, hdAddressOtherInfo
                      FROM HdAddress
                      WHERE hdId=:hd_id");
        $hdAddress_query->execute(array(
            'hd_id' => $hdId
        ));
        $hdAddresses = $hdAddress_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdAddresses
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});