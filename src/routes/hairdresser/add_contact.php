<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//add hd contact
$app->post('/api/hairdresser/add_contact/{hd_id}', function (Request $request, Response $response, $args){
    $hdId = $args["hd_id"];
    $hdContact = $request->getParam('hd_contact');
    $hdContactType = $request->getParam('hd_contact_type');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $add_contact_query = $db->prepare("INSERT INTO HdContact SET
					hdContact=:contact,
					hdContactType=:htype,
					hdId=:hdId
					");

        $insert = $add_contact_query->execute(array(
            'contact' => $hdContact,
            'htype' => $hdContactType,
            'hdId' => $hdId
        ));

        if($insert){
            $data = array(
                'status' => 'ok',
                'message' => 'contact is added'
            );
            return $response->withJson($data);
        }
    }
    catch (PDOException $e){
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
