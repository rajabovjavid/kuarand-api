<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get customer by id
$app->get('/api/customer/getCustomerById', function (Request $request, Response $response){

    $cusId =$request->getQueryParams()["cus_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $customer_query = $db->prepare(
            "SELECT customerId, customerName, customerEmail, customerPhone, customerCreatedAt 
                      FROM Customer
                      WHERE customerId=:cus_id");
        $customer_query->execute(array(
            'cus_id' => $cusId
        ));
        $customers = $customer_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $customers
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});