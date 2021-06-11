<?php
// muestro reporte de errores en true
ini_set('display_errors', 1);
error_reporting(E_ALL);
  
// home page
$home_url="http://localhost/QR/api/";
  
// pagina obtenida del post / por defecto es 1
$page = isset($_GET['page']) ? $_GET['page'] : 1;
  
// numero de datos por pagina
$records_per_page = 5;
  
// calculo para el LIMIT de los querys
$from_record_num = ($records_per_page * $page) - $records_per_page;
?>