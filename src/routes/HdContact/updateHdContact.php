<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->put('/api/hdContact/updateHdContact', function (Request $request, Response $response) {

    $hdContactId = $request->getParam('hd_contact_id');
    $hdContactType = $request->getParam('hd_contact_type');
    $hdContact = $request->getParam('hd_contact');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // update hd contact
        $update_hdContact_query = $db->prepare("CALL updateHdContact(?, ?, ?)");
        $update_hdContact_query->bindParam(1, $hdContactId, PDO::PARAM_INT);
        $update_hdContact_query->bindParam(2, $hdContactType, PDO::PARAM_INT);
        $update_hdContact_query->bindParam(3, $hdContact, PDO::PARAM_STR);
        $update = $update_hdContact_query->execute();

        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'hd contact is not updated'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'data' => $hdContactId,
            'message' => 'hd contact is updated'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});