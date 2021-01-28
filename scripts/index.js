const mysql = require('mysql');
const fs    = require('fs');
require('dotenv').config();
const minimist = require('minimist');
const csvtojson = require('csvtojson');

function connect_database () {
    const { MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB } = process.env;
    if(!MYSQL_HOST || !MYSQL_USER || !MYSQL_PASS || !MYSQL_DB) throw new Error('Debe definir los parámetros de configuración en el archivo .ENV');

    const connection = mysql.createConnection({
        host: MYSQL_HOST,
        user: MYSQL_USER,
        password: MYSQL_PASS,
        database: MYSQL_DB
    })

    connection.connect();

    console.log('-------------------------------------------------------------');
    console.log('----- Base de datos conectada correctamente -----------------');
    console.log('-------------------------------------------------------------');

    return connection;
}

async function main() {
    try { 
        // Obtenemos los argumentos ingresados
        const { file } = minimist(process.argv);
        if(!file) throw new Error("Debe enviar un archivo para poder actualizar la base de datos");
    
        if(!fs.existsSync(file)) throw new Error("Archivo inexistente");
    
        const csv_settings = {
            delimiter: ";"
        }
        const csv_file = await csvtojson(csv_settings).fromFile(file);
    
        const connection = connect_database();
        let sql = "";
        let contador = [0,0];
        for(const f of csv_file) {
            const situacion = f['SITUACION'] == 'ALCANZADO' ? true : false;
            sql = 'INSERT INTO `users` (`document`, `full_name`, `gender`, `situation`)';
            sql += ` VALUES ('${f['NUM_DOC']}','${f['NOMBRE Y APELLIDO']}','${f['GENERO']}', ${situacion})`;

            connection.query(sql, function(err, result) {
                if(err) {
                    contador[1]++;
                } 
                if(result) {
                    contador[0]++;
                }
                console.log('Agregados: ' + contador[0], 'Errores: ' + contador[1]);
                if((contador[0] + contador[1]) === csv_file.length) {
                    process.exit();
                }
            });
        }   
    
    } catch (e) {
        console.log("Error: ", e);
    }
}

main();