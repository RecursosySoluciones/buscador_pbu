<?php

$response = null;
$classes = "d-none";
if(isset($_GET['err'])) {
    $response = $_GET['err'] == "false" ? true : $_GET['err'];

    if($response === true) {
        $classes = "alert alert-success mt-5";
        $response = "Archivo subido correctamente";
    } else {
        $classes = "alert alert-danger mt-5";

    }
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/main.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="assets/js/main.js"></script>
</head>
<body class="container">
    <form class="mt-2" method="POST" action="./WS/upload.php" enctype="multipart/form-data">
        <div class="form-group">
            <input required type="file" name="file"/>
        </div>
        <button class="btn btn-primary" type="submit">Enviar</button>
    </form>
    <div class="<?php echo $classes; ?>">
        <?php echo $response;  ?>
    </div>
</body>
</html>
