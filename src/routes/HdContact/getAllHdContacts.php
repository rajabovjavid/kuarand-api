<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get all customers
$app->get('/api/hdContact/getAllHdContacts', function (Request $request, Response $response) {

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdContact_query = $db->prepare("SELECT hdContactId, hdId, hdContactType, hdContact FROM HdContact");
        $hdContact_query->execute();
        $hdContact = $hdContact_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdContact
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});