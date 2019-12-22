<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->put('/api/hdGallery/updateHdGallery', function (Request $request, Response $response) {

    $hdGalleryId = $request->getParam('hdGallery_id');
    $hdPhoto = fopen($request->getParam('hd_photo'), "rb");
    $hdPhotoPriority = $request->getParam('hdPhoto_priority');

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // check whether that hdGallery id exists or not
        $get_hdGallery_query = $db->prepare("select * from HdGallery where hdGalleryId=:hdGallery_id");
        $get_hdGallery_query->execute(array(
            'hdGallery_id' => $hdGalleryId,
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

        // update hdGallery
        $update_hdGallery_query = $db->prepare("CALL updateHdGallery(?, ?, ?)");
        $update_hdGallery_query->bindParam(1, $hdGalleryId, PDO::PARAM_INT);
        $update_hdGallery_query->bindParam(2, $hdPhoto, PDO::PARAM_LOB);
        $update_hdGallery_query->bindParam(3, $hdPhotoPriority, PDO::PARAM_INT);
        $update = $update_hdGallery_query->execute();

        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hdGallery is not updated'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hdGallery is updated'
        );
        return $response->withJson($data);


    }

    catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }



});