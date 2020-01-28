<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// search hairdressers
$app->get('/api/hairdresser/searchHairdressers', function (Request $request, Response $response) {

    $hd_name = $request->getQueryParams()['hd_name'];
    $city = $request->getQueryParams()['city'];
    $region = $request->getQueryParams()["region"];
    $ser_name = $request->getQueryParams()["ser_name"];
    $hd_type = $request->getQueryParams()["hd_type"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hairdressers_query = $db->prepare(
            "SELECT DISTINCT hdId, hdName, hdAddressCity, hdAddressRegion, hdRating
                      FROM allinfohdview
                      WHERE hdName LIKE :hd_name OR hdType=:hd_type OR hdAddressCity=:city OR serName LIKE :ser_name OR hdAddressRegion LIKE :region");
        $hairdressers_query->execute(array(
            "hd_name" => $hd_name,
            "city" => $city,
            "region" => $region,
            "ser_name" => $ser_name,
            "hd_type" => $hd_type
        ));
        $searchResult = $hairdressers_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $searchResult
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});

