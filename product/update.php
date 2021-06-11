<?php
// headers requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// incluir configuracion de db y objeto productos
include_once '../config/database.php';
include_once '../objects/product.php';

// obtener conexion a bd
$database = new Database();
$db = $database->getConnection();

// inicializar objeto producto
$product = new Product($db);

// obtener id de producto a editar desde get
$data = json_decode(file_get_contents("php://input"));

// seteo id obtenida a objeto producto
$product->id = $data->id;

// seteo valores de producto
$product->name = $data->name;
$product->price = $data->price;
$product->description = $data->description;
$product->category_id = $data->category_id;

// utilizo metodo de update para modificar datos
if ($product->update()) {

    // establecer codigo de respuesta - 200 - OK
    http_response_code(200);

    // notifico api - exito
    echo json_encode(array("message" => "Product was updated."));
}

// si no se puede hacer update, notifico
else {

    // establecer codigo de respuesta - 503
    http_response_code(503);

    // notifico api - error
    echo json_encode(array("message" => "Unable to update product."));
}
