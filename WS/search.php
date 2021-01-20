<?php
require './functions_db.php';
/**
 * Este es un script para obtener datos de clientes desde la base de datos MySQL\
 */

// Inicializamos las variables
$q      = "";
$limit  = 50;
$offset = 0;

if($_GET['q']) {
    $q = $_GET['q'];
}
if($_GET['limit']) {
    $limit = (int) $_GET['limit'];
}
if($_GET['offset']) {
    $offset = (string) $_GET['offset'];
}
if($q != ""){
    $sql = "SELECT * FROM users WHERE `document` LIKE '%$q%' OR `full_name` LIKE '%$q%' LIMIT $offset, $limit";
} else {
    $sql = "SELECT * FROM users LIMIT $offset, $limit";
}
 
$connection = ConectarDb();
$query  = EjecutarConsulta($connection, $sql);
cerrarConexion($connection);

$return_data = [
    "Success" => true,
    "Data" => $query
];

header('content-type: application/json');
echo json_encode($return_data);
?>