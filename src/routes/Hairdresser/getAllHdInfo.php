<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get hairdresser by id
$app->get('/api/hairdresser/getAllHdInfo', function (Request $request, Response $response){

    $hdId =$request->getQueryParams()["hd_id"];  //$app->request()->get('hd_id');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hairdresser_query = $db->prepare(
            "SELECT HD.hdId, hdName, hdEmail, hdType, hdStatus, hdRating, hdCommentCount, hdAddressCity, hdAddressRegion, hdAddressNeighborhood, hdAddressStreet, hdAddressOtherInfo
                      FROM Hairdresser HD, HdAddress HA
                      WHERE HD.hdId=HA.hdId and HD.hdId='$hdId'");
        $hairdresser_query->execute();
        $hairdresser = $hairdresser_query->fetch(PDO::FETCH_OBJ);

        $hairdresser_query1 = $db->prepare(
            "SELECT serId, serPrice, discountedPrice, serName, serType, serMinTime
                      FROM hairdressersservicesview
                      WHERE hdId='$hdId'");
        $hairdresser_query1->execute();
        $hairdresser_services = $hairdresser_query1->fetchAll(PDO::FETCH_OBJ);

        $hairdresser_query2 = $db->prepare(
            "SELECT day, startHour, finishHour
                      FROM Hairdresser HD, HdWorkHour HW
                      WHERE HD.hdId=HW.hdId and HD.hdId='$hdId'");
        $hairdresser_query2->execute();
        $hairdresser_work_hours = $hairdresser_query2->fetchAll(PDO::FETCH_OBJ);

        $hairdresser_query3 = $db->prepare(
            "SELECT employeeName, employeePhoto, employeeGender
                      FROM Hairdresser HD, Employee E
                      WHERE HD.hdId = E.hdId and HD.hdId='$hdId'");
        $hairdresser_query3->execute();
        $hairdresser_employees = $hairdresser_query3->fetchAll(PDO::FETCH_OBJ);

        $hairdresser_query4 = $db->prepare(
            "SELECT hdPhoto, hdPhotoPriority
                      FROM Hairdresser HD, HdGallery HG
                      WHERE HD.hdId=HG.hdId and HD.hdId='$hdId'");
        $hairdresser_query4->execute();
        $hairdresser_gallery = $hairdresser_query4->fetchAll(PDO::FETCH_OBJ);

        $hairdresser_query5 = $db->prepare(
            "SELECT hdContactId, hdContactType, hdContact
                      FROM Hairdresser HD, HdContact HC 
                      WHERE HD.hdId=HC.hdId and HD.hdId='$hdId' limit 2");
        $hairdresser_query5->execute();
        $hairdresser_contact = $hairdresser_query5->fetchAll(PDO::FETCH_OBJ);

        $hairdresser_query6 = $db->prepare(
            "SELECT R.reservationDate, HS.serMinTime
                      FROM Reservation R, HairdresserServices HS
                      WHERE R.hdId=HS.hdId and R.hdId='$hdId' and R.isFinished = 0");
        $hairdresser_query6->execute();
        $hairdresser_reservation = $hairdresser_query6->fetchAll(PDO::FETCH_OBJ);




        foreach ($hairdresser_gallery as $photo){
            if($photo->hdPhotoPriority == 1){
                $impPhoto = $photo->hdPhoto;
            }
        }
//        unset($hairdresser_gallery, $impPhoto);

        $final_data = array(
            "hdId" => $hairdresser->hdId,
            "hdName" => $hairdresser->hdName,
            "hdEmail" => $hairdresser->hdEmail,
            "hdType" => $hairdresser->hdType,
            "hdStatus" => $hairdresser->hdStatus,
            "hdRating" => $hairdresser->hdRating,
            "hdCommentCount" => $hairdresser->hdCommentCount,
            "hdAddressCity" => $hairdresser->hdAddressCity,
            "hdAddressRegion" => $hairdresser->hdAddressRegion,
            "hdAddressNeighborhood" => $hairdresser->hdAddressNeighborhood,
            "hdAddressStreet" => $hairdresser->hdAddressStreet,
            "hdAddressOtherInfo" => $hairdresser->hdAddressOtherInfo,
            "hdServices" => $hairdresser_services,
            "hdWorkHours" => $hairdresser_work_hours,
            "hdEmployees" => $hairdresser_employees,
            "hdGallery" => $hairdresser_gallery,
            "hdImpPhoto" => $impPhoto,
            "hdContacts" => $hairdresser_contact,
            "hdReservations" => $hairdresser_reservation
        );

        $data = array(
            'status' => 'ok',
            'data' => $final_data
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});