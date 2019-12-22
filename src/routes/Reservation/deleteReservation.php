<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->delete('/api/reservation/deleteReservation', function (Request $request, Response $response){

    $reservationId= $request->getParam('reservation_id');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // check whether that reservation id exists or not
        $get_reservation_query = $db->prepare("select * from Reservation where reservationId=:reservation_id");
        $get_reservation_query->execute(array(
            'reservation_id' => $reservationId
        ));

        $row_count = $get_reservation_query->rowCount();

        if ($row_count == 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => "that reservation id doesn't exist"
            );
            return $response->withJson($data);
        }

        // delete reservation
        $delete_reservation_query = $db->prepare("CALL deleteReservation(?)");
        $delete_reservation_query->bindParam(1, $reservationId, PDO::PARAM_INT);
        $delete = $delete_reservation_query->execute();

        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'reservation is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'reservation is deleted'
        );
        return $response->withJson($data);

    }

    catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }



});
