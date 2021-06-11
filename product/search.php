<?php
// headers requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// incluir archivos de configuracion, bd y productos
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/product.php';

// cargo conexion a bd
$database = new Database();
$db = $database->getConnection();

// inicializo productos
$product = new Product($db);

// obtengo keyword
$keywords = isset($_GET["s"]) ? $_GET["s"] : "";

// query de productos - busqueda
$stmt = $product->search($keywords);
$num = $stmt->rowCount();

// compruebo cantidad de resultados
if ($num > 0) {

    // array de productos
    $products_arr = array();
    $products_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $product_item = array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name,
        );

        array_push($products_arr["records"], $product_item);
    }

    // establecer codigo de respuesta - 200
    http_response_code(200);

    // notificar exito
    echo json_encode($products_arr);
} else {
    // establecer codigo de respuesta - 404
    http_response_code(404);

    // notificar que no encontro datos
    echo json_encode(
        array("message" => "No products found.")
    );
}
