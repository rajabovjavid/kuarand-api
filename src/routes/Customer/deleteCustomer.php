<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->post('/api/customer/deleteCustomer', function (Request $request, Response $response) {

    $customerEmail = $request->getParam('cus_email');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // checking whether email used or not
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


        // add admin
        $delete_customer_query = $db->prepare("CALL deleteCustomer(?)");
        $delete_customer_query->bindParam(1, $customerEmail, PDO::PARAM_STR);
        $delete = $delete_customer_query->execute();


        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'customer is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'customer is deleted'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});