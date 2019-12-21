<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/api/employeeServices/addEmployeeService', function (Request $request, Response $response) {

    $employeeId = $request->getParam('employee_id');
    $serviceId = $request->getParam('service_id');


    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();


        // add employee service
        $add_employeeService_query = $db->prepare("CALL addEmployeeServices(?, ?)");
        $add_employeeService_query->bindParam(1, $employeeId, PDO::PARAM_INT);
        $add_employeeService_query->bindParam(2, $serviceId, PDO::PARAM_INT);

        $add = $add_employeeService_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'employee service is not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'employee service is added'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});