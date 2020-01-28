<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// custo_mer sign in
$app->post('/api/customer/signin', function (Request $request, Response $response){
    $cusEmail = $request->getParam('cus_email');
    $cusPassword = md5($request->getParam('cus_password'));


    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $get_customer_query = $db->prepare(
            "select customerId, customerName, customerEmail, customerCreatedAt, customerPhone 
                      from Customer 
                      where customerEmail=:mail and customerPassword=:password");
        $get_customer_query->execute(array(
            'mail' => $cusEmail,
            'password' => $cusPassword
        ));

        //dönen satır sayısını belirtir
        $row_count = $get_customer_query->rowCount();

        if($row_count==1){
            $customer = $get_customer_query->fetch(PDO::FETCH_OBJ);

            $data = array(
                'status' => 'ok',
                'data' => $customer,
                'message' => 'customer is signed in'
            );
            return $response->withJson($data);
        }
        $data = array(
            'status' => 'error',
            'error_code' => 1,
            'message' => 'Customer email or password is incorrect!'
        );
        return $response->withJson($data);
    }
    catch (PDOException $e){
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }

});