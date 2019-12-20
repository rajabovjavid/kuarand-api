<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get all customers
$app->get('/api/customers', function (Request $request, Response $response) {
    $sql = "SELECT * FROM Customer";

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $customer_query = $db->prepare("SELECT * FROM Customer");
        $customer_query->execute();
        $customers = $customer_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $customers
        );
        return $response->withJson($data);

        /* return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($customers)); */
    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});

