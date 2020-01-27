<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get customer by id
$app->get('/api/hdContact/getHdContactByHdId', function (Request $request, Response $response){

    $hdId =$request->getQueryParams()["hd_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hdContact_query = $db->prepare(
            "SELECT hdContactId, hdId, hdContactType, hdContact 
                      FROM HdContact
                      WHERE hdId=:hd_id");
        $hdContact_query->execute(array(
            'hd_id' => $hdId
        ));
        $hdContacts = $hdContact_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $hdContacts
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});