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
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

// get posted data
$data = json_decode(file_get_contents('php://input'));

// make sure data is not empty
if (
    !empty($data->menu_name) &&
    !empty($data->diet_type)
) {
    // set product property values
    $product->menu_name = $data->menu_name;
    $product->diet_type = $data->diet_type;
    $product->created = date('Y-m-d H:i:s');

    //$value = $product->count($product->menu_name);
    //echo json_encode(['message' => $value]);
    if ($product->countMenuName($product->menu_name) == 0) {
        // create the product
        if ($product->create()) {
            // set response code - 201 created
            http_response_code(201);

            // tell the user
            echo json_encode(['message' => 'Product was created.']);
        }

        // if unable to create the product, tell the user
        else {
            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(['message' => 'Unable to create product.']);
        }
    }
    else {
        // set response code - 507 insufficient storage
        http_response_code((507));

        //tell the user
        echo json_encode(['message' => 'Unable to create product. Data already exists.']);
    }
}

// tell the user data is incomplete
else {
    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(['message' => 'Unable to create product. Data is incomplete.']);
}
