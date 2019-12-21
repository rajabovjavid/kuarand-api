<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/hdGallery/deleteHdGallery', function (Request $request, Response $response) {

    $hdGalleryId= $request->getParam('hdGallery_id');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // check whether that hdGallery id exists or not
        $get_hdGallery_query = $db->prepare('select * from HdGallery where hdGalleryId=:hdGallery_id');
        $get_hdGallery_query->execute(array(
            'hdGallery_id' => $hdGalleryId
        ));

        $row_count = $get_hdGallery_query->rowCount();

        if ($row_count == 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => "that hdGallery doesn't exist"
            );
            return $response->withJson($data);
        }

        // delete hdGallery
        $delete_hdGallery_query = $db->prepare("CALL deleteHdGallery(?)");
        $delete_hdGallery_query->bindParam(1, $hdGalleryId, PDO::PARAM_INT);
        $delete = $delete_hdGallery_query->execute();

        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hdGallery is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hdGallery is deleted'
        );
        return $response->withJson($data);
    }

    catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }

});