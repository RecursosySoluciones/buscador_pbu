<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/main.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="assets/js/main.js"></script>
</head>
<body class="container">
<h1>Prestación Básica Universal (PBU)</h1>
<hr></hr>
<H3>Clientes alcanzados</H3>
<br>
<hr>
    <form class="mt-2">
        <div class="form-group">
            <input class="form-control" type="text" id="buscador" />
        </div>
    </form>

    <table class="table" id="resultados">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre y Apellido</th>
                <th>Genero</th>
                <th>Aplica</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <button class="btn btn-primary" id="vermas">Ver Mas</button>
    
    <div class="logo">
    
        <p>by Soluciones Digitales®</p>
        
        <img src="assets/css/img/logos_teco.png" >


    
    </div>
</body>

<script>
    let content = [];
    let url_save = {
        q: "",
        offset: 0,
        limit: 0
    }
    function armar_tabla() {
        for(let { document, full_name, gender, situation } of content) {
            let classes = "table-danger";
            if(situation === "1") {
                classes = "table-success";
            }
            let markup = `<tr class="${classes}">`;
            markup += `<td>${document}</td>`;
            markup += `<td>${full_name}</td>`;
            markup += `<td>${gender}</td>`;
            markup += `<td>${situation === '1' ? "SI" : 'NO'}</td>`;
            markup += `</tr>`;

            let table_body = $("#resultados tbody");
            table_body.append(markup) 
        }
        content = [];

    }

    function eliminar_tabla() {
        $("#resultados > tbody").empty();
    }

    $(document).ready(function($) {

        $('#vermas').on('click', function() {
            const { q, limit, offset } = url_save;
            let url = `WS/search.php?limit=${limit}&offset=${offset}`;
            if(q) {
                url += `&q=${q}`;
            }
            $.get(url, function(e) {
                const { Data } = e;
                if(Data instanceof Array) {
                    content = Data;
                }

                url_save = {
                    q,
                    limit,
                    offset: offset + Data.length
                }

                armar_tabla();
            });

        })

        $('#buscador').on('input',function(e){
            const { value } = e.target;
            let limit = 15;
            let offset = 0;
            let url = `WS/search.php?limit=${limit}&offset=${offset}`;

            if(value) {
                url += `&q=${value}`;
                eliminar_tabla();
                url_save = {};
            }


            $.get(url, function(e) {
                const { Data } = e;
                if(Data instanceof Array) {
                    content = Data;
                }

                url_save = {
                    q: value,
                    limit,
                    offset: offset + Data.length
                }

                armar_tabla();
            });
        })
    })
</script>
</html>
