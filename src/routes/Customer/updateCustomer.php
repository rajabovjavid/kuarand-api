<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->put('/api/customer/updateCustomer', function (Request $request, Response $response) {

    $customerName = $request->getParam('cus_name');
    $customerEmail = $request->getParam('cus_email');
    $customerPassword = md5($request->getParam('cus_password'));
    $customerPhone = $request->getParam('cus_phone');


    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // check whether that email exists or not
        $get_customer_query = $db->prepare("select * from Customer where customerEmail=:mail");
        $get_customer_query->execute(array(
            'mail' => $customerEmail
        ));

        $row_count = $get_customer_query->rowCount();

        if ($row_count == 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => "that email doesn't exist"
            );
            return $response->withJson($data);
        }


        // update customer
        $update_customer_query = $db->prepare("CALL updateCustomer(?, ?, ?, ?)");
        $update_customer_query->bindParam(1, $customerEmail, PDO::PARAM_STR);
        $update_customer_query->bindParam(2, $customerName, PDO::PARAM_STR);
        $update_customer_query->bindParam(3, $customerPassword, PDO::PARAM_STR);
        $update_customer_query->bindParam(4, $customerPhone, PDO::PARAM_STR);
        $update = $update_customer_query->execute();


        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'customer is not updated'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'customer is updated'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});