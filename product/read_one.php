<?php
// headers requeridos
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// incluir configuracion de db y objeto productos
include_once '../config/database.php';
include_once '../objects/product.php';

// instanciamiento de db y productos
$database = new Database();
$db = $database->getConnection();

// inicializacion de objeto productos
$product = new Product($db);

// setear id de producto a leer
$product->id = isset($_GET['id']) ? $_GET['id'] : die();

// leer detalle de producto a editar
$product->readOne();

if ($product->name != null) {
    // crear array
    $product_arr = array(
        "id" => $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "category_id" => $product->category_id,
        "category_name" => $product->category_name,

    );

    // establecer codigo de respuesta - 200 - OK
    http_response_code(200);

    // mostrar datos en formato JSON
    echo json_encode($product_arr);
} else {
    // establecer codigo de respuesta - 400
    http_response_code(404);

    // notificar api - no existe producto
    echo json_encode(array("message" => "Product does not exist."));
}
