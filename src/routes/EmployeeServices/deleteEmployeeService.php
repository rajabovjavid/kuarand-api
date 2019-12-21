<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->delete('/api/employeeServices/deleteEmployeeService', function (Request $request, Response $response) {

    $employeeId = $request->getParam('employee_id');
    $serviceId = $request->getParam('service_id');


    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();


        // delete employee service
        $delete_employeeService_query = $db->prepare("CALL deleteEmployeeServices(?, ?)");
        $delete_employeeService_query->bindParam(1, $employeeId, PDO::PARAM_INT);
        $delete_employeeService_query->bindParam(2, $serviceId, PDO::PARAM_INT);

        $delete = $delete_employeeService_query->execute();

        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'employee service is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'employee service is deleted'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});