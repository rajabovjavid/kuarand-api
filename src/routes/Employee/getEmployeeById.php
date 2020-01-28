<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get employee by id
$app->get('/api/employee/getEmployeeById', function (Request $request, Response $response){

    $employeeId = $request->getQueryParams()["emp_id"];

    // Get DB Object
    $db = new db();
    // Connect
    $db = $db->connect();


    $employeeId_query = $db->prepare("SELECT employeeId, hdId, employeeName, employeePhoto, employeeGender FROM Employee WHERE employeeId=:employee_id");
    $employeeId_query->execute(array(
        'employee_id' => $employeeId
    ));

    $employeeId_query->bindColumn(1, $empId, PDO::PARAM_INT);
    $employeeId_query->bindColumn(2, $hdId, PDO::PARAM_INT);
    $employeeId_query->bindColumn(3, $employeeName, PDO::PARAM_STR);
    $employeeId_query->bindColumn(4, $employeePhoto, PDO::PARAM_LOB);
    $employeeId_query->bindColumn(5, $employeeGender, PDO::PARAM_INT);
    $employeeId_query->fetch(PDO::FETCH_BOUND);

    $data_array = array(
        "employeeId" => $empId,
        "hdId" => $hdId,
        "employeeName" => $employeeName,
        "employeePhoto" => $employeePhoto,
        "employeeGender" => $employeeGender
    );

    $data = array(
        'status' => 'ok',
        'data' =>  $data_array
    );
    return $response->withJson($data);

});

