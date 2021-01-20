<?php
//POR ARCHIVO INCLUIMOS SISTEMA DE LA BASE DE DATOS
date_default_timezone_set("America/Argentina/Buenos_Aires");
if($_SERVER["SERVER_NAME"]!="localhost"){
    define("ORIGENDB","mysql");// valores gladius o mysql
    define("HOST", "localhost"); //define("HOST","localhost"); 
    define("USERNAME", "root"); //define("USERNAME","root");
    define("PASSWORD", "root"); //define("PASSWORD","");
    define("BASEDEDATOS","buscador_pbu");	
}
if($_SERVER["SERVER_NAME"]=="localhost"){
define("ORIGENDB","mysql");// valores gladius o mysql
define("HOST","localhost"); //define("HOST","localhost"); 
define("USERNAME",'root'); //define("USERNAME","root");
define("PASSWORD",'root'); //define("PASSWORD","");
define("BASEDEDATOS",'buscador_pbu');	
}

function ConectarDb(){
    $G = mysqli_connect(HOST, USERNAME, PASSWORD) or die ('Error de coneccion mySql');
    mysqli_select_db($G , BASEDEDATOS) or die ('Error al seleccionar base de datos');		
    return $G;
}

function cerrarConexion($G){
    $G = mysqli_close ($G);
    return $G;
}	

function EjecutarConsulta($G, $sql){	
    $resultados = "";
    $resultados = mysqli_query($G, $sql);
    if(!is_bool($resultados)){
        mysqli_data_seek ($resultados, 0);
        while($caca[] = mysqli_fetch_assoc($resultados));
        array_pop($caca);  // pop the last row off, which is an empty row
        $resultados= $caca;
        } 
    return $resultados;
}

function corregirPrecio($dato)
{
    // Esta función recibe un string como parámetro, por ejemplo "$1066,5" y devuelve 1066.5
    $patronespe = array();
    
    $patronespe[0]   = '/\,/';
    $patronespe[1]   = '/\$/';
    $sustitucionespe = array();
    
    $sustitucionespe[0] = '.';
    $sustitucionespe[1] = '';
    if (strpos($dato, ',') && strpos($dato, '.')) {
        $dato = str_replace('.', '', $dato);
    }
    
    $dato = trim(preg_replace($patronespe, $sustitucionespe, $dato));
    if ($dato == 0 && $dato != "") {
        $dato = "-";
    }
    return $dato;
}
?>