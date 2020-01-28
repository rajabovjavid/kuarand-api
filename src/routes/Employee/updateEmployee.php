<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->put('/api/employee/updateEmployee', function (Request $request, Response $response) {

    $employeeId = $request->getParam('employee_id');
    $employeeName = $request->getParam('employee_name');

    if($request->getParam('employee_photo') == "") $employeePhoto=$request->getParam('employee_photo_base64');
    else $employeePhoto =  base64_encode(file_get_contents($request->getParam('employee_photo')));


    $employeeGender = $request->getParam('employee_gender');


    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();


        // update employee
        $update_employee_query = $db->prepare("CALL updateEmployee(?, ?, ?, ?)");
        $update_employee_query->bindParam(1, $employeeId, PDO::PARAM_INT);
        $update_employee_query->bindParam(2, $employeeName, PDO::PARAM_STR);
        $update_employee_query->bindParam(3, $employeePhoto, PDO::PARAM_LOB);
        $update_employee_query->bindParam(4, $employeeGender, PDO::PARAM_INT);
        $update = $update_employee_query->execute();


        if (!$update) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'employee is not updated'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'data' => $employeeId,
            'message' => 'employee is updated'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});