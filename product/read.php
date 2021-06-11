<?php
// headers requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// incluir configuracion de db y objeto productos
include_once '../config/database.php';
include_once '../objects/product.php';

// instanciamiento de db y productos
$database = new Database();
$db = $database->getConnection();

// inicializacion de productos
$product = new Product($db);

// query de productos
$stmt = $product->read();
// cantidad de productos
$num = $stmt->rowCount();

// comprobar que existan resultados
if ($num > 0) {

    // array de productos
    $products_arr = array();
    $products_arr["records"] = array();

    // recorrer resultados
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // por cada iteracion crear item y sumarlo al array.
        extract($row); // para usar directamente los nombres
        $product_item = array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name,
        );
        //agrego al final del array
        array_push($products_arr["records"], $product_item);
    }

     // establecer codigo de respuesta - 200 - OK
    http_response_code(200);

    // muestro resultados en formato JSON
    echo json_encode($products_arr);
} else {
     // establecer codigo de respuesta - 404
    http_response_code(404);
    // notifico que no hay productos
    echo json_encode(
        array("message" => "No products found.")
    );
}
