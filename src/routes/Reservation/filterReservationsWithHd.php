<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// filter reservations by hairdresser id
$app->get('/api/reservation/filterReservationsWithHd', function (Request $request, Response $response){

    $hdId =$request->getQueryParams()["hd_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $reservation_query = $db->prepare(
            "SELECT hdId, reservationId, customerName, customerPhone, reservationDate, serName, serPrice, isFinished
                      FROM hairdresserreservationsview
                      WHERE hdId=:hd_id");
        $reservation_query->execute(array(
            "hd_id" => $hdId
        ));
        $reservations = $reservation_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $reservations
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
