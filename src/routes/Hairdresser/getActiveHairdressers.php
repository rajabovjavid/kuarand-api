<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// search hairdressers
$app->get('/api/hairdresser/getActiveHairdressers', function (Request $request, Response $response) {

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hairdressers_query = $db->prepare(
            "SELECT H.hdId, hdName, hdAddressCity, hdAddressRegion, hdRating, hdPhoto
                      FROM Hairdresser H, 
                           HdAddress HA, 
                           HdGallery HG
                      WHERE H.hdStatus=3 
                        and H.hdId=HA.hdId 
                        and H.hdId=HG.hdId 
                        and HG.hdPhotoPriority=1");

        $hairdressers_query->execute();

        $hds = $hairdressers_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hds
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});

