<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//add hd address
$app->post('/api/haird_resser/add_address/{hd_id}', function (Request $request, Response $response, $args){
    $hdId = $args["hd_id"];
    $hdAddressCity = $request->getParam('address_city');
    $hdAddressRegion = $request->getParam('address_region');
    $hdAddressNeigh = $request->getParam('address_neigh');
    $hdAddressStreet = $request->getParam('address_street');
    $hdAddressOther = $request->getParam('address_other');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $add_address_query = $db->prepare("INSERT INTO HdAddress SET
					hdAddressCity=:city,
					hdAddressRegion=:region,
					hdAddressNeighborhood=:neigh,
					hdAddressStreet=:street,
					hdAddressOtherInfo=:other,
					hdId=:hdId
					");
        $insert = $add_address_query->execute(array(
            'city' => $hdAddressCity,
            'region' => $hdAddressRegion,
            'neigh' => $hdAddressNeigh,
            'street' => $hdAddressStreet,
            'other' => $hdAddressOther,
            'hdId' => $hdId
        ));

        if($insert){
            $data = array(
                'status' => 'ok',
                'message' => 'address is added'
            );
            return $response->withJson($data);
        }
    }
    catch (PDOException $e){
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
