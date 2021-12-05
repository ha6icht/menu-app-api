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
include_once '../objects/handleApiUser.php';

$database = new Database();
$db = $database->getConnection();

$hau = new HandleApiUser($db);

// get posted data
$data = json_decode(file_get_contents('php://input'));

if(!empty($data->username) &&
!empty($data->email) &&
!empty($data->firstname) &&
!empty($data->lastname) &&
!empty($data->user_password)){
    $hau->username = $data->username;
    $hau->email = $data->email;
    $hau->firstname = $data->firstname;
    $hau->lastname = $data->lastname;
    $hau->user_password = $data->user_password;
    $hau->created = date('Y-m-d H:i:s');

    if (!$hau->getUser()) {
        if ($hau->setUser()) {
            http_response_code(200);
            echo json_encode(['message' => 'User inserted']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'User not inserted!']);
        }
    } else {
        if ($hau->updateUser()) {
            http_response_code(200);
            echo json_encode(['message' => 'User updated']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'User not updated!']);
        }
    }
} else {
    http_response_code(400);
    echo json_encode(["message"=> "Could not set user. Data incomplete."]);
}
