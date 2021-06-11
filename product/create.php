<?php
// headers requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// obtener configuracion de bd
include_once '../config/database.php';

// instanciar objeto productos
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

// obtener post
$data = json_decode(file_get_contents("php://input"));

// validacion de datos vacios
if (
    !empty($data->name) &&
    !empty($data->price) &&
    !empty($data->description) &&
    !empty($data->category_id)
) {

    // seteo de atributos de objeto producto
    $product->name = $data->name;
    $product->price = $data->price;
    $product->description = $data->description;
    $product->category_id = $data->category_id;
    $product->created = date('Y-m-d H:i:s');

    // creacion de producto
    if ($product->create()) {

        // establecer codigo de respuesta - 201
        http_response_code(201);

        // notificacion api
        echo json_encode(array("message" => "Product was created."));
    }

    // Si no se pudo crear producto, notificar api
    else {
        // establecer codigo de respuesta - 503
        http_response_code(503);
        // notificacion api
        echo json_encode(array("message" => "Unable to create product."));
    }
}

// notificacion api - datos incompletos
else {

    // establecer codigo de respuesta - 400
    http_response_code(400);

    // notificacion api
    echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
}
