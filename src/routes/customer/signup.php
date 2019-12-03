<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// customer sign up
$app->post('/api/customer/signup', function (Request $request, Response $response) {
    $cusName = $request->getParam('cus_name');
    $cusEmail = $request->getParam('cus_email');
    $cusPassword = md5($request->getParam('cus_password'));

    try {

        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $get_customer_query = $db->prepare("select * from Customer where customerEmail=:mail");
        $get_customer_query->execute(array(
            'mail' => $cusEmail
        ));

        //dönen satır sayısını belirtir
        $row_count = $get_customer_query->rowCount();

        if ($row_count != 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'used email'
            );
            return $response->withJson($data);
        }

        //Kullanıcı kayıt işlemi yapılıyor...
        $add_customer_query = $db->prepare("INSERT INTO Customer SET
					customerName=:cname,
					customerEmail=:email,
					customerPassword=:password
					");
        $insert = $add_customer_query->execute(array(
            'cname' => $cusName,
            'email' => $cusEmail,
            'password' => $cusPassword
        ));

        if (!$insert) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'customer not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'data' => $db->lastInsertId(),
            'message' => 'customer is added'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
