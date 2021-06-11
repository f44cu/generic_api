<?php
// headers requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// incluir archivos de configuracion, bd y productos
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/product.php';

// utilities
$utilities = new Utilities();

// instanciamiento de db, obtengo conexion
$database = new Database();
$db = $database->getConnection();

// instanciamiento de productos
$product = new Product($db);

// query de productos
$stmt = $product->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// controlo cantidad de resultados
if ($num > 0) {

    // array de productos
    $products_arr = array();
    $products_arr["records"] = array();
    $products_arr["paging"] = array();
    //recorro resultados
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
        //agrego item al final del array
        array_push($products_arr["records"], $product_item);
    }

    // paginacion
    $total_rows = $product->count();
    $page_url = "{$home_url}product/read_paging.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $products_arr["paging"] = $paging;

// establecer codigo de respuesta - 200 - OK
    http_response_code(200);

    // json
    echo json_encode($products_arr);
} else {

    // establecer codigo de respuesta - 400 - OK
    http_response_code(400);

    //notifico que no hay productos
    echo json_encode(
        array("message" => "No products found.")
    );
}
