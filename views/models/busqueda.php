<?php
 include ("model/conexion.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<form action="" method="GET" style=" padding: 5px 8px; margin-left: 5px; margin-top: 25px; width: 250px">

<div class="col-auto">
    <input type="text" name="busqueda" class="form-control"  placeholder="Buscar...">
</div>

<div class="col-auto" style="margin-top: 10px;">
    <button type="submit" name="enviar" class="btn btn-primary mb-3">Enviar</button>
</div>
</form>

<?php
if (isset($_GET['enviar'])) {
    $busqueda = $_GET['busqueda'];

    $consult= $conn->sqlsrv_query(" SELECT Encabezado.nit,  Cast(Encabezado.Fecha As Varchar(11)) As 'fecha' FROM Factura1_2000 Encabezado WHERE Encabezado.nit LIKE '%busqueda%' ");

    while ($row = $consul->fetch_array()) {
            echo  $row['Encabezado.nit'];
    }
}

?>

</body>
</html>