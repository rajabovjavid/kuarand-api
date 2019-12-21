<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->delete('/api/employee/deleteEmployee', function (Request $request, Response $response) {

    $employeeId = $request->getParam('employee_id');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();


        // delete admin
        $delete_employee_query = $db->prepare("CALL deleteEmployee(?)");
        $delete_employee_query->bindParam(1, $employeeId, PDO::PARAM_INT);
        $delete = $delete_employee_query->execute();


        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'employee is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'employee is deleted'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});