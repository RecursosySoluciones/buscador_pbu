<?php
require './functions_db.php';

function crear_tabla_users () {
    $sql = "CREATE TABLE IF NOT EXISTS`users` 
        (`id` INT NOT NULL AUTO_INCREMENT, 
        `document` VARCHAR(20) NOT NULL, 
        `full_name` VARCHAR(100) NOT NULL, 
        `gender` VARCHAR(2) NULL DEFAULT NULL, 
        `situation` INT NOT NULL, 
        PRIMARY KEY (`id`), 
        UNIQUE `document` (`document`)) ENGINE = InnoDB;";

    $connection = ConectarDb();
    $new_table  = EjecutarConsulta($connection, $sql);
    cerrarConexion($connection);

    return $new_table;
}

$return_data = [
    "Success" => false,
    "Data" => [],
    "Message" => ""
];
try {
    if(!isset($_FILES["file"])) {
        throw new Exception("Debe enviar un archivo");
    }
    $file = $_FILES["file"];

    if($file["type"] != "text/csv") { 
        throw new Exception("El archivo solo puede ser CSV");
    }

    if ($file["error"] > 0) {
        throw new Exception("Return Code: " . $_FILES["file"]["error"] . "<br />");
    }

    $file_name = getdate()[0] . ".csv";
    $file_name = "../files_uploaded/" . $file_name;
    $f = move_uploaded_file($_FILES["file"]["tmp_name"], $file_name);
    if(!$f) { throw new Exception("Error al subir el archivo"); }

    if(!crear_tabla_users()) { throw new Exception("Error al crear la base de datos"); }

    if (($handle = fopen($file_name, "r")) !== FALSE) {
        $row = 1;

        $total_registers = [ "err" => 0, "success" => 0 ];

        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if($row !== 1) {

                $situation = $data[3] == "ALCANZADO" ? true : false;
                // Insertamos de a 100 registros
                $sql = "INSERT INTO `users` (`document`, `full_name`, `gender`, `situation`) 
                VALUES ('$data[0]','$data[1]','$data[2]', $situation)";
                // else {
                //     $sql += ",($data[0],$data[1],$data[2],$data[3])";
                //     $insert_registers++;
                // }
                $connection = ConectarDb();
                $insert  = EjecutarConsulta($connection, $sql);
                cerrarConexion($connection);
                if($insert) {
                    $total_registers["success"] += 1;
                } else {
                    $total_registers["err"] += 1;
                }
            };
            $row++;
        }
        fclose($handle);
    }

    $return_data["Data"]["total_registers"] = $total_registers;
    header("Location: /buscador/upload.php?err=false");

} catch (Exception $e) {
    $return_data["Message"] = $e->getMessage();
    // echo json_encode($return_data);
    header("Location: /buscador/upload.php?err=" . $return_data["Message"]);

}
