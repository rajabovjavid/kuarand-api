<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/hairdresser/add_contact/{hd_id}', function (Request $request, Response $response, $args) {

    $hdId = $args["hd_id"];
    $hdContactType = $request->getParam('hd_contact_type');
    $hdContact = $request->getParam('hd_contact');

    try {
        // Get DB Object
        $db_obj = new db();
        // Connect
        $db = $db_obj->connect();

        // add hd contact
        $add_hdcontact_query = $db->prepare("CALL addHdContact(?, ?, ?)");
        $add_hdcontact_query->bindParam(1, $hdId, PDO::PARAM_INT);
        $add_hdcontact_query->bindParam(2, $hdContactType, PDO::PARAM_INT);
        $add_hdcontact_query->bindParam(3, $hdContact, PDO::PARAM_STR);
        $add = $add_hdcontact_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hd contact is not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hd contact is added'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});