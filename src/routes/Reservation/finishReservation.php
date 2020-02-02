<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->put('/api/reservation/finishReservation', function (Request $request, Response $response) {

    $reservationId = $request->getParam('reservation_id');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // check whether that reservation id exists or not
        $get_reservation_query = $db->prepare("select * from Reservation where reservationId='$reservationId'");
        $get_reservation_query->execute();

        $row_count = $get_reservation_query->rowCount();

        if ($row_count == 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => "that reservation id doesn't exist"
            );
            return $response->withJson($data);
        }

        $reservation = $get_reservation_query->fetch(PDO::FETCH_OBJ);

        $today = date("Y-m-d H:i:s");

        if(!$reservation->reservationDate == $today){
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'reservation is finished yet'
            );
            return $response->withJson($data);
        }

        // update reservation
        $update_reservation_query = $db->prepare("UPDATE Reservation SET isFinished=1 WHERE reservationId='$reservationId'");
        $update = $update_reservation_query->execute();


        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 3,
                'message' => 'reservation is not updated'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'reservation is updated'
        );
        return $response->withJson($data);

    }

    catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }

});