<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// get employeeServices by id
$app->get('/api/employeeServices/getEmployeeServicesById', function (Request $request, Response $response){

    $employeeId =$request->getQueryParams()["emp_id"];
    $serId =$request->getQueryParams()["ser_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $employeeServices_query = $db->prepare(
            "SELECT *
                      FROM EmployeeServices
                      WHERE employeeId=:emp_id AND serId=:ser_id");
        $employeeServices_query->execute(array(
            'emp_id' => $employeeId,
            'ser_id' => $serId
        ));
        $employeeServices = $employeeServices_query->fetch(PDO::FETCH_OBJ);

        $data = array(
            'status' => 'ok',
            'data' => $employeeServices
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
