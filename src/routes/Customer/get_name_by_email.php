<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get custo_mer name by email
$app->get('/api/customer/getName', function (Request $request, Response $response) {

    $cusEmail = $request->getQueryParams()["cus_email"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $get_customer_query = $db->prepare(
            "SELECT * 
                      FROM Customer
                      WHERE customerEmail=:mail");
        $get_customer_query->execute(array(
            'mail' => $cusEmail
        ));
        $customer = $get_customer_query->fetch(PDO::FETCH_OBJ);

        if($customer==null){
            $data = array(
                'status' => 'error',
                'message' => "no custo_mer with that email"
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'data' => $customer->customerName
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
