<?php
// headers requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// incluir archivos de db y productos
include_once '../config/database.php';
include_once '../objects/product.php';
  
// obtener conexion de db
$database = new Database();
$db = $database->getConnection();
  
// iniciarlizar objeto producto
$product = new Product($db);
  
// obtener el id indicado mediante get
$data = json_decode(file_get_contents("php://input"));
  
// setar id de producto a eliminar
$product->id = $data->id;
  
// eliminar producto utilizando metodo delete
if($product->delete()){
  
    // establecer codigo de respuesta - 200
    http_response_code(200);
  
    // notificar exito
    echo json_encode(array("message" => "Product was deleted."));
}
  
// si no se puede eliminar producto, notifico
else{
  
    // establecer codigo de respuesta - 503
    http_response_code(503);
  
    // notificar
    echo json_encode(array("message" => "Unable to delete product."));
}
?>