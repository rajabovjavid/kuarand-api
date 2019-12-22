<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->post('/api/reservation/addReservation', function (Request $request, Response $response) {

    $customerID = $request->getParam('customer_id');
    $hdId = $request->getParam('hd_id');
    $serId = $request->getParam('ser_id');
    $reservationDate = $request->getParam('reservation_date');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();


        // add reservation
        $add_reservation_query = $db->prepare("CALL addReservation(?, ?, ?, ?)");
        $add_reservation_query->bindParam(1, $customerID, PDO::PARAM_INT);
        $add_reservation_query->bindParam(2, $hdId, PDO::PARAM_INT);
        $add_reservation_query->bindParam(3, $serId, PDO::PARAM_INT);
        $add_reservation_query->bindParam(4, $reservationDate, PDO::PARAM_STR);
        $add = $add_reservation_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'reservation is not added'
            );
            return $response->withJson($data);
        }

            $data = array(
                'status' => 'ok',
                'message' => 'reservation is added'
             );
            return $response->withJson($data);

        }

        catch(PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});