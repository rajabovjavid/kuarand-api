<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get all customers
$app->get('/api/hdAddress/getAllHdAddresses', function (Request $request, Response $response) {

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdAddress_query = $db->prepare("SELECT hdId, hdAddressCity, hdAddressRegion, hdAddressNeighborhood, hdAddressStreet, hdAddressOtherInfo FROM HdAddress");
        $hdAddress_query->execute();
        $hdAddress = $hdAddress_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdAddress
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});