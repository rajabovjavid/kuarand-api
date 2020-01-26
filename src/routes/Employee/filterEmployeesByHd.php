<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get all employees
$app->get('/api/employee/filterEmployeeByHd', function (Request $request, Response $response) {

    $hdId =$request->getQueryParams()["hd_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $employee_query = $db->prepare(
            "SELECT employeeId, hdId, employeeName, employeeGender
                      FROM Employee
                      WHERE hdId=:hd_id");
        $employee_query->execute(array(
            'hd_id' => $hdId
        ));
        $employees = $employee_query->fetchAll(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $employees
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
