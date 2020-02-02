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

        $statement = "";

        if($ser_name!=""){
            $statement = "SELECT DISTINCT hdId, hdName, hdAddressCity, hdAddressRegion, hdRating, hdPhoto
                          FROM searchinfohdview
                          WHERE hdAddressCity='$city' and hdAddressRegion='$region' and serName='$ser_name'";
        }
        elseif ($hd_type!=""){
            $statement = "SELECT DISTINCT hdId, hdName, hdAddressCity, hdAddressRegion, hdRating, hdPhoto
                          FROM searchinfohdview
                          WHERE hdAddressCity='$city' and hdAddressRegion='$region' and hdType='$hd_type'";
        }
        elseif ($hd_name!=""){
            $statement = "SELECT DISTINCT hdId, hdName, hdAddressCity, hdAddressRegion, hdRating, hdPhoto
                          FROM searchinfohdview
                          WHERE hdAddressCity='$city' and hdAddressRegion='$region' and hdName='$hd_name'";
        }


        $hairdressers_query = $db->prepare($statement);
        $hairdressers_query->execute();
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

