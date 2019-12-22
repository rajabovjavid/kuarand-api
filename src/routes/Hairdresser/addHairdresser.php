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
    $hdAddressOther = $request->getParam('address_other');

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
        $add_hairdresser_query = $db->prepare("CALL addHairdresser(?, ?, ?, ?)");
        $add_hairdresser_query->bindParam(1, $hdName, PDO::PARAM_STR);
        $add_hairdresser_query->bindParam(2, $hdEmail, PDO::PARAM_STR);
        $add_hairdresser_query->bindParam(3, $hdPassword, PDO::PARAM_STR);
        $add_hairdresser_query->bindParam(4, $hdType, PDO::PARAM_INT);
        $add = $add_hairdresser_query->execute();
        $result = $add_hairdresser_query->fetch(PDO::FETCH_OBJ);

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hairdresser is not added'
            );
            return $response->withJson($data);
        }

        // make array for adding address
        $data_address_array = array(
            "address_city" => $hdAddressCity,
            "address_region" => $hdAddressRegion,
            "address_neigh" => $hdAddressNeigh,
            "address_street" => $hdAddressStreet,
            "address_other" => $hdAddressOther
        );
        $hd_id = $result->last_insert;

        // curl request to add address to hairdresser
        $make_call_address = callAPI('POST', 'http://localhost/rest_api_slim/public/api/hairdresser/add_address/'.$hd_id, json_encode($data_address_array));
        $response_of_address = json_decode($make_call_address, true);

        // make array for adding contact
        $data_contact_array = array(
            "hd_contact" => $hdEmail,
            "hd_contact_type" => 0
        );

        // curl request to add contact to hairdresser
        $make_call_contact = callAPI('POST', 'http://localhost/rest_api_slim/public/api/hairdresser/add_contact/'.$hd_id, json_encode($data_contact_array));
        $response_of_contact = json_decode($make_call_contact, true);

        // checking whether one of address/contact is added or not
        if($response_of_address["status"] == "ok" and $response_of_contact["status"] != "ok"){
            $data = array(
                'status' => 'ok',
                'data' => $result->last_insert,
                'message' => 'hairdresser is added, address is added, but contact could not be added'
            );
            return $response->withJson($data);
        }
        elseif ($response_of_address["status"] != "ok" and $response_of_contact["status"] == "ok"){
            $data = array(
                'status' => 'ok',
                'data' => $result->last_insert,
                'message' => 'hairdresser is added, contact is added, but address could not be added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'data' => $result->last_insert,
            'message' => 'hairdresser is added'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});