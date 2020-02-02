<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->post('/api/reservation/addReservation', function (Request $request, Response $response) {

    $customerID = $request->getParam('customer_id');
    $hdId = $request->getParam('hd_id');
    $serId = $request->getParam('ser_id');
    $reservationDate = $request->getParam('reservation_date');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $newDateTime = explode(" ", $reservationDate);
        $dayofweek = date('w', strtotime($newDateTime[0]));
        $dayofweek = ($dayofweek+6)%7;

//        $endTime = strtotime("+".$hdReservation["serMinTime"]." minutes", strtotime($dateTime[1]));

        $work_hour_query = $db->prepare("
                        SELECT startHour, finishHour 
                        FROM HdWorkHour
                        WHERE hdId='$hdId' and day='$dayofweek'");
        $work_hour_query->execute();
        $work_hour = $work_hour_query->fetch(PDO::FETCH_OBJ);

        $service_query = $db->prepare("
                        SELECT serMinTime 
                        FROM HairdresserServices 
                        WHERE hdId='$hdId' and serId='$serId'");
        $service_query->execute();
        $newSerMinTime = $service_query->fetch(PDO::FETCH_OBJ);

        $summed_Time = strtotime("+" . $newSerMinTime->serMinTime . " minutes", strtotime($newDateTime[1]));
        $summed_Time = date("H:i:s",$summed_Time);

        $add_bool = 1;

        if(strtotime($work_hour->startHour)<strtotime($newDateTime[1]) && strtotime($work_hour->finishHour)>strtotime($summed_Time)){
            $add_bool = 1;
        }else $add_bool = 0;

        $reservation_query = $db->prepare(
            "SELECT R.hdId, reservationDate, isFinished, serMinTime
                      FROM Reservation R, HairdresserServices HS
                      WHERE R.hdId='$hdId' and R.hdId=HS.hdId and isFinished=0");
        $reservation_query->execute();
        $reservations = $reservation_query->fetchAll(PDO::FETCH_OBJ);




        foreach ($reservations as $reservation) {
            $oldDateTime = explode(" ", $reservation->reservationDate);
            if (strtotime($newDateTime[0]) == strtotime($oldDateTime[0])) {
                if (strtotime($oldDateTime[1]) > strtotime($newDateTime[1])) {
                    $summedTime = strtotime("+" . $newSerMinTime->serMinTime . " minutes", strtotime($newDateTime[1]));
                    $summedTime = date("H:i:s",$summedTime);
                    if (strtotime($summedTime) < strtotime($oldDateTime[1])) $add_bool = 1;
                    else $add_bool = 0;
                } elseif (strtotime($oldDateTime[1]) < strtotime($newDateTime[1])) {
                    $summedTime = strtotime("+" . $reservation->serMinTime . " minutes", strtotime($oldDateTime[1]));
                    $summedTime = date("H:i:s",$summedTime);
                    if (strtotime($summedTime) <= strtotime($newDateTime[1])) $add_bool = 1;
                    else $add_bool = 0;
                }
                else $add_bool = 0;
            }
        }


        if ($add_bool == 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'reservation is not added, because date is not proper'
            );
            return $response->withJson($data);
        }

        // add reservation
        $add_reservation_query = $db->prepare("CALL addReservation(?, ?, ?, ?)");
        $add_reservation_query->bindParam(1, $customerID, PDO::PARAM_INT);
        $add_reservation_query->bindParam(2, $hdId, PDO::PARAM_INT);
        $add_reservation_query->bindParam(3, $serId, PDO::PARAM_INT);
        $add_reservation_query->bindParam(4, $reservationDate, PDO::PARAM_STR);
        $add = $add_reservation_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'reservation is not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'reservation is added'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});