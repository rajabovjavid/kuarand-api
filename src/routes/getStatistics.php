<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get statistics
$app->get('/api/getStatistics', function (Request $request, Response $response) {

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $hairdresser_query = $db->prepare("SELECT COUNT(*) as hd_count FROM Hairdresser");
        $hairdresser_query->execute();
        $hairdressers_count = $hairdresser_query->fetch(PDO::FETCH_OBJ);

        $customer_query = $db->prepare("SELECT COUNT(*) as cus_count FROM Customer");
        $customer_query->execute();
        $customers_count = $customer_query->fetch(PDO::FETCH_OBJ);

        $reservation_query = $db->prepare("SELECT COUNT(*) as res_count FROM Reservation");
        $reservation_query->execute();
        $reservations_count = $reservation_query->fetch(PDO::FETCH_OBJ);

        $admin_query = $db->prepare("SELECT COUNT(*) as admin_count FROM Admin");
        $admin_query->execute();
        $admins_count = $admin_query->fetch(PDO::FETCH_OBJ);

        $commentReply_query = $db->prepare("SELECT COUNT(*) as reply_count FROM CommentReply");
        $commentReply_query->execute();
        $commentReplies_count = $commentReply_query->fetch(PDO::FETCH_OBJ);

        $employee_query = $db->prepare("SELECT COUNT(*) as emp_count FROM Employee");
        $employee_query->execute();
        $employees_count = $employee_query->fetch(PDO::FETCH_OBJ);

        $employeeServices_query = $db->prepare("SELECT COUNT(*) as emp_ser_count FROM EmployeeServices");
        $employeeServices_query->execute();
        $employeeServices_count = $employeeServices_query->fetch(PDO::FETCH_OBJ);

        $hairdresserServices_query = $db->prepare("SELECT COUNT(*) as hd_ser_count FROM HairdresserServices");
        $hairdresserServices_query->execute();
        $hairdresserServices_count = $hairdresserServices_query->fetch(PDO::FETCH_OBJ);

        $hdAddress_query = $db->prepare("SELECT COUNT(*) as hd_add_count FROM HdAddress");
        $hdAddress_query->execute();
        $hdAddresses_count = $hdAddress_query->fetch(PDO::FETCH_OBJ);

        $hdComment_query = $db->prepare("SELECT COUNT(*) as hd_comment_count FROM HdComment");
        $hdComment_query->execute();
        $hdComments_count = $hdComment_query->fetch(PDO::FETCH_OBJ);

        $hdContact_query = $db->prepare("SELECT COUNT(*) as hd_cont_count FROM HdContact");
        $hdContact_query->execute();
        $hdContacts_count = $hdContact_query->fetch(PDO::FETCH_OBJ);

        $hdGallery_query = $db->prepare("SELECT COUNT(*) as hd_gal_count FROM HdGallery");
        $hdGallery_query->execute();
        $hdGalleries_count = $hdGallery_query->fetch(PDO::FETCH_OBJ);

        $service_query = $db->prepare("SELECT COUNT(*) as ser_count FROM Service");
        $service_query->execute();
        $services_count = $service_query->fetch(PDO::FETCH_OBJ);

        $hdPromotion_query = $db->prepare("SELECT COUNT(*) as hd_promo_count FROM HdPromotion");
        $hdPromotion_query->execute();
        $hdPromotions_count = $hdPromotion_query->fetch(PDO::FETCH_OBJ);

        $hdWorkHour_query = $db->prepare("SELECT COUNT(*) as hd_work_hour_count FROM HdWorkHour");
        $hdWorkHour_query->execute();
        $hdWorkHours_count = $hdWorkHour_query->fetch(PDO::FETCH_OBJ);



        $data = array(
            'status' => 'ok',
            'hairdressers_count' => $hairdressers_count->hd_count,
            'customers_count' => $customers_count->cus_count,
            'reservations_count' => $reservations_count->res_count,
            'admins_count' => $admins_count->admin_count,
            'commentReplies_count' => $commentReplies_count->reply_count,
            'employees_count' => $employees_count->emp_count,
            'hairdresserServices_count' => $hairdresserServices_count->hd_ser_count,
            'hdAddresses_count' => $hdAddresses_count->hd_add_count,
            'hdComments_count' => $hdComments_count->hd_comment_count,
            'hdContacts_count' => $hdContacts_count->hd_cont_count,
            'hdGalleries_count' => $hdGalleries_count->hd_gal_count,
            'services_count' => $services_count->ser_count,
            'hdPromotions_count' => $hdPromotions_count->hd_promo_count,
            'hdWorkHours_count' => $hdWorkHours_count->hd_work_hour_count,
            'employeeServices_count' => $employeeServices_count->emp_ser_count,
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});