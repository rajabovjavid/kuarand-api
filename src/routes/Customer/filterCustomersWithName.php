<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// filter customers by name
$app->get('/api/customer/filterCustomersWithName', function (Request $request, Response $response){

    $cusName =$request->getQueryParams()["cus_name"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $customer_query = $db->prepare(
            "SELECT customerId, customerName, customerEmail, customerPhone, customerCreatedAt
                      FROM Customer
                      WHERE customerName LIKE ?");
        $customer_query->execute(array("%$cusName%"));
        $customers = $customer_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $customers
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});