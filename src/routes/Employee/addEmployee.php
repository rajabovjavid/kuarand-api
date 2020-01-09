<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/employee/addEmployee', function (Request $request, Response $response) {

    $hdId = $request->getParam('hd_id');
    $employeeName = $request->getParam('employee_name');
    $employeePhoto = fopen($request->getParam('employee_photo'), "rb");
    $employeeGender = $request->getParam('employee_gender');

    $data = array(
        'status' => 'ok',
        'data' => 1,
        'message' => 'employee is added'
    );
    return $response->withJson($data);

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();


        // add employee
        $add_employee_query = $db->prepare("CALL addEmployee(?, ?, ?, ?)");
        $add_employee_query->bindParam(1, $hdId, PDO::PARAM_INT);
        $add_employee_query->bindParam(2, $employeeName, PDO::PARAM_STR);
        $add_employee_query->bindParam(3, $employeePhoto, PDO::PARAM_LOB);
        $add_employee_query->bindParam(4, $employeeGender, PDO::PARAM_INT);
        $add = $add_employee_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'employee is not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'data' => $db->lastInsertId(),
            'message' => 'employee is added'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});