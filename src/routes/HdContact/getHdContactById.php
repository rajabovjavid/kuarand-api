<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get customer by id
$app->get('/api/hdContact/getHdContactById', function (Request $request, Response $response){

    $hdContactId =$request->getQueryParams()["hdContact_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdContact_query = $db->prepare(
            "SELECT hdContactId, hdId, hdContactType, hdContact 
                      FROM HdContact
                      WHERE hdContactId=:hdContact_id");
        $hdContact_query->execute(array(
            'hdContact_id' => $hdContactId
        ));
        $hdContacts = $hdContact_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdContacts
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});