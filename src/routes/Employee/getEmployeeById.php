<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get employee by id
$app->get('/api/employee/getEmployeeById', function (Request $request, Response $response){

    $employeeId =$request->getQueryParams()["emp_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $employee_query = $db->prepare(
            "SELECT *
                      FROM Employee
                      WHERE employeeId=:emp_id");
        $employee_query->execute(array(
            'emp_id' => $employeeId
        ));
        $employees = $employee_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $employees
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});

