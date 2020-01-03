<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get employee photo
$app->get('/api/employee/getEmployeePhotoById', function (Request $request, Response $response) {

    $employeeId = $request->getQueryParams()["employee_id"];

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();


        $employeeId_query = $db->prepare("SELECT employeePhoto FROM Employee WHERE employeeId=:employee_id");
        $employeeId_query->execute(array(
            'employee_id' => $employeeId
        ));

        $employeeId_query->bindColumn(1, $photo, PDO::PARAM_LOB);
        $employeeId_query->fetch(PDO::FETCH_BOUND);


        $data = array(
            'status' => 'ok',
            'data' =>  base64_encode($photo)
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});