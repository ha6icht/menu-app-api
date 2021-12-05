<?php

// required headers
header('Access-Control-Allow-Origin: https://menu.rolfkarlen.ch');
header('Vary: Origin');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// get database connection
include_once '../config/database.php';

// instantiate product object
include_once '../objects/jwt.php';

$database = new Database();
$db = $database->getConnection();

$jwt = new JWT($db);

// get posted data
$data = json_decode(file_get_contents('php://input'));
if(!empty($data->sub)&&
!empty($data->username)){
    $jwt->sub = $data->sub;
    $jwt->username = $data->username;
    $jwt->created = date('Y-m-d H:i:s');

    if (0 == $jwt->isUsernameSet()) {
        $jwt->getSalt();
        /*echo/*$jsonWebToken =*/ $jwt->getJsonWebToken();
        $jwt->setValues();
        http_response_code(200);
        echo json_encode(['message' => 'Json Web Token set']);
    } elseif (1 == $jwt->isUsernameSet()) {
        $jwt->getSalt();
        $jwt->getJsonWebToken();
        $jwt->updateJWT();
        http_response_code(200);
        echo json_encode(['message' => 'Json Web Token updated']);
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Username already exists!']);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" =>"Could not generate JWT. Data incomplete."]);
}