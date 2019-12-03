<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get customer name by email
$app->get('/api/customer/getName/{email}', function (Request $request, Response $response, $args) {

    $cusEmail = $args['email'];

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
                'message' => "no customer with that email"
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
