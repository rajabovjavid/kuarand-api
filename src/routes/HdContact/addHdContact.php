<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/hdContact/addHdContact', function (Request $request, Response $response) {

    $hdId = $request->getParam("hd_id");
    $hdContactType = $request->getParam('hd_contact_type');
    $hdContact = $request->getParam('hd_contact');

    try {
        // Get DB Object
        $db_obj = new db();
        // Connect
        $db = $db_obj->connect();

        // add hd contact
        $add_hdContact_query = $db->prepare("CALL addHdContact(?, ?, ?)");
        $add_hdContact_query->bindParam(1, $hdId, PDO::PARAM_INT);
        $add_hdContact_query->bindParam(2, $hdContactType, PDO::PARAM_INT);
        $add_hdContact_query->bindParam(3, $hdContact, PDO::PARAM_STR);
        $add = $add_hdContact_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
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