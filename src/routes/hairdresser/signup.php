<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// hairdresser signup
$app->post('/api/hairdresser/signup', function (Request $request, Response $response) {

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

        $get_hd_query = $db->prepare("select * from Hairdresser where hdEmail=:mail");
        $get_hd_query->execute(array(
            'mail' => $hdEmail
        ));

        //dönen satır sayısını belirtir
        $row_count = $get_hd_query->rowCount();

        if ($row_count != 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'used email'
            );
            return $response->withJson($data);
        }

        //Kullanıcı kayıt işlemi yapılıyor...
        $add_hd_query = $db->prepare("INSERT INTO Hairdresser SET
					hdName=:hname,
					hdEmail=:mail,
					hdPassword=:password,
					hdType=:htype
					");
        $insert = $add_hd_query->execute(array(
            'hname' => $hdName,
            'mail' => $hdEmail,
            'password' => $hdPassword,
            'htype' => $hdType
        ));

        if (!$insert) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hairdresser is not added'
            );
            return $response->withJson($data);
        }
        // hairdresser is added successfully

        // make array for adding address
        $data_address_array = array(
            "address_city" => $hdAddressCity,
            "address_region" => $hdAddressRegion,
            "address_neigh" => $hdAddressNeigh,
            "address_street" => $hdAddressStreet,
            "address_other" => $hdAddressOther
        );
        $hd_id = $db->lastInsertId();

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
                'data' => $db->lastInsertId(),
                'message' => 'hairdresser is added, address is added, but contact could not be added'
            );
            return $response->withJson($data);
        }
        elseif ($response_of_address["status"] != "ok" and $response_of_contact["status"] == "ok"){
            $data = array(
                'status' => 'ok',
                'data' => $db->lastInsertId(),
                'message' => 'hairdresser is added, but address could not be added'
            );
            return $response->withJson($data);
        }

        // hairdresser is added successfully
        $data = array(
            'status' => 'ok',
            'data' => $db->lastInsertId(),
            'message' => 'hairdresser is added completely'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
