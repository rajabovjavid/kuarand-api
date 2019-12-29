<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get statistics
$app->get('/api/getStatistics', function (Request $request, Response $response) {

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hairdresser_query = $db->prepare("SELECT COUNT(*) as hd_count FROM Hairdresser");
        $hairdresser_query->execute();
        $hairdressers_count = $hairdresser_query->fetch(PDO::FETCH_OBJ);

        $customer_query = $db->prepare("SELECT COUNT(*) as cus_count FROM Customer");
        $customer_query->execute();
        $customers_count = $customer_query->fetch(PDO::FETCH_OBJ);

        $reservation_query = $db->prepare("SELECT COUNT(*) as res_count FROM Reservation");
        $reservation_query->execute();
        $reservations_count = $reservation_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'hairdressers_count' => $hairdressers_count->hd_count,
            'customers_count' => $customers_count->cus_count,
            'reservations_count' => $reservations_count->res_count,
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});