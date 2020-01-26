<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


require '../vendor/autoload.php';
require '../src/config/db.php';
require '../src/config/write_to_file.php';

$app = new Slim\App;


function callAPI($method, $url, $data){
    $curl = curl_init();

    switch ($method){
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'APIKEY: 111111111111111111111',
        'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    // EXECUTE:
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    return $result;
}

// admin
require '../src/routes/Admin/addAdmin.php';
require '../src/routes/Admin/updateAdmin.php';
require '../src/routes/Admin/deleteAdmin.php';
require '../src/routes/Admin/getAllAdmins.php';
require '../src/routes/Admin/getAdminById.php';
require '../src/routes/Admin/signinAdmin.php';

//customer routes
require '../src/routes/Customer/addCustomer.php';
require '../src/routes/Customer/deleteCustomer.php';
require '../src/routes/Customer/updateCustomer.php';
require '../src/routes/Customer/filterCustomersWithName.php';
require '../src/routes/Customer/getAllCustomers.php';
require '../src/routes/Customer/getCustomerById.php';

// commentReply routes
require '../src/routes/CommentReply/addCommentReply.php';
require '../src/routes/CommentReply/deleteCommentReply.php';
require '../src/routes/CommentReply/updateCommentReply.php';
require '../src/routes/CommentReply/getAllCommentReplies.php';
require '../src/routes/CommentReply/getCommentReplyById.php';

// employee routes
require '../src/routes/Employee/addEmployee.php';
require '../src/routes/Employee/deleteEmployee.php';
require '../src/routes/Employee/updateEmployee.php';
require '../src/routes/Employee/getAllEmployees.php';
require '../src/routes/Employee/getEmployeeById.php';
require '../src/routes/Employee/getEmployeePhotoById.php';
require '../src/routes/Employee/filterEmployeesByHd.php';


// employee services routes
require '../src/routes/EmployeeServices/addEmployeeService.php';
require '../src/routes/EmployeeServices/deleteEmployeeService.php';
require '../src/routes/EmployeeServices/updateEmployeeService.php';
require '../src/routes/EmployeeServices/getAllEmployeeServices.php';
require '../src/routes/EmployeeServices/getEmployeeServicesById.php';


require '../src/routes/custo_mer/get_all.php';
require '../src/routes/custo_mer/get_name_by_email.php';
require '../src/routes/custo_mer/get_customer_by_id.php';
require '../src/routes/custo_mer/signup.php';
require '../src/routes/custo_mer/signin.php';

//hairdresser routes
require '../src/routes/Hairdresser/addHairdresser.php';
require '../src/routes/Hairdresser/updateHairdresser.php';
require '../src/routes/Hairdresser/deleteHairdresser.php';
require '../src/routes/Hairdresser/getAllHairdressers.php';
require '../src/routes/Hairdresser/getHairdresserById.php';
require '../src/routes/Hairdresser/getHairdressersByStatus.php';
require '../src/routes/Hairdresser/filterHairdressersWithName.php';
require '../src/routes/Hairdresser/signinHairdresser.php';


require '../src/routes/haird_resser/signup.php';
require '../src/routes/haird_resser/add_address.php';
require '../src/routes/haird_resser/add_contact.php';
require '../src/routes/haird_resser/signin.php';
require '../src/routes/haird_resser/get_hds_count.php';

// hairdresser services routes
require '../src/routes/HairdresserServices/addHairdresserService.php';
require '../src/routes/HairdresserServices/deleteHairdresserService.php';
require '../src/routes/HairdresserServices/updateHairdresserService.php';
require '../src/routes/HairdresserServices/getAllHairdresserServices.php';
require '../src/routes/HairdresserServices/getHairdresserServicesById.php';

// hdAddress routes
require '../src/routes/HdAddress/addHdAddress.php';
require '../src/routes/HdAddress/deleteHdAddress.php';
require '../src/routes/HdAddress/updateHdAddress.php';
require '../src/routes/HdAddress/getAllHdAddresses.php';
require '../src/routes/HdAddress/getHdAddressById.php';

// hdComment routes
require '../src/routes/HdComment/addHdComment.php';
require '../src/routes/HdComment/deleteHdComment.php';
require '../src/routes/HdComment/updateHdComment.php';
require '../src/routes/HdComment/getAllHdComments.php';
require '../src/routes/HdComment/getHdCommentById.php';

// hdContact routes
require '../src/routes/HdContact/addHdContact.php';
require '../src/routes/HdContact/deleteHdContact.php';
require '../src/routes/HdContact/updateHdContact.php';
require '../src/routes/HdContact/getAllHdContacts.php';
require '../src/routes/HdContact/getHdContactById.php';

//HdGallery routes
require '../src/routes/HdGallery/addHdGallery.php';
require '../src/routes/HdGallery/deleteHdGallery.php';
require '../src/routes/HdGallery/updateHdGallery.php';
require '../src/routes/HdGallery/getAllHdGalleries.php';
require '../src/routes/HdGallery/getHdGalleryById.php';
require '../src/routes/HdGallery/getHdGalleryPhotoById.php';


//HdPromotion routes
require '../src/routes/HdPromotion/addHdPromotion.php';
require '../src/routes/HdPromotion/deleteHdPromotion.php';
require '../src/routes/HdPromotion/updateHdPromotion.php';
require '../src/routes/HdPromotion/getAllHdPromotions.php';
require '../src/routes/HdPromotion/getHdPromotionById.php';


//HdWorkHour routes
require  '../src/routes/HdWorkHour/addHdWorkHour.php';
require  '../src/routes/HdWorkHour/deleteHdWorkHour.php';
require  '../src/routes/HdWorkHour/updateHdWorkHour.php';
require  '../src/routes/HdWorkHour/getAllHdWorkHours.php';
require  '../src/routes/HdWorkHour/getHdWorkHourById.php';

//Reservation routes
require  '../src/routes/Reservation/addReservation.php';
require  '../src/routes/Reservation/deleteReservation.php';
require  '../src/routes/Reservation/updateReservation.php';
require  '../src/routes/Reservation/getAllReservations.php';
require  '../src/routes/Reservation/getReservationById.php';

//Service routes
require '../src/routes/Service/addService.php';
require '../src/routes/Service/deleteService.php';
require '../src/routes/Service/updateService.php';
require  '../src/routes/Service/getAllServices.php';
require  '../src/routes/Service/getServiceById.php';

//other
require '../src/routes/getStatistics.php';


$app->run();