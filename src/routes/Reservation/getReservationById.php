<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get reservation by id
$app->get('/api/reservation/getReservationById', function (Request $request, Response $response){

    $resId =$request->getQueryParams()["res_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $reservation_query = $db->prepare(
            "SELECT *
                      FROM Reservation
                      WHERE reservationId=:res_id");
        $reservation_query->execute(array(
            'res_id' => $resId
        ));
        $reservations = $reservation_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $reservations
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});