<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/hairdresser/addHairdresser', function (Request $request, Response $response) {

    $hdName = $request->getParam('hd_name');
    $hdEmail = $request->getParam('hd_email');
    $hdPassword = md5($request->getParam('hd_password'));
    $hdType = $request->getParam('hd_type');

    $hdAddressCity = $request->getParam('address_city');
    $hdAddressRegion = $request->getParam('address_region');
    $hdAddressNeigh = $request->getParam('address_neigh');
    $hdAddressStreet = $request->getParam('address_street');
    $hdAddressOtherInfo = $request->getParam('address_other');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // check whether that email is used or not
        $get_hairdresser_query = $db->prepare("select * from Hairdresser where hdEmail=:mail");
        $get_hairdresser_query->execute(array(
            'mail' => $hdEmail
        ));

        $row_count = $get_hairdresser_query->rowCount();

        if ($row_count != 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'used email'
            );
            return $response->withJson($data);
        }

        // add hairdresser
        $add_hairdresser_query = $db->prepare("CALL addHairdresser(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $add_hairdresser_query->bindParam(1, $hdName, PDO::PARAM_STR);
        $add_hairdresser_query->bindParam(2, $hdEmail, PDO::PARAM_STR);
        $add_hairdresser_query->bindParam(3, $hdPassword, PDO::PARAM_STR);
        $add_hairdresser_query->bindParam(4, $hdType, PDO::PARAM_INT);
        $add_hairdresser_query->bindParam(5, $hdAddressCity, PDO::PARAM_STR);
        $add_hairdresser_query->bindParam(6, $hdAddressRegion, PDO::PARAM_STR);
        $add_hairdresser_query->bindParam(7, $hdAddressNeigh, PDO::PARAM_STR);
        $add_hairdresser_query->bindParam(8, $hdAddressStreet, PDO::PARAM_STR);
        $add_hairdresser_query->bindParam(9, $hdAddressOtherInfo, PDO::PARAM_STR);
        $add = $add_hairdresser_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hairdresser is not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hairdresser is added'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});