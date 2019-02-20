<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php

session_start();

if($_SESSION['login']==false){
    header("location:../../index.php");
}


$laboratori = $tipo_componente = "";
$config = parse_ini_file('../../config.ini');

try {
    $connessione = new PDO($config['dbType'] . "host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
    $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$query_laboratori = "select * from laboratorio";

$result = $connessione->query($query_laboratori);

foreach ($result as $row) {
    $laboratori = $laboratori . '<option value="' . $row['id_laboratorio'] . '" >' . $row['descrizione'] . '</option>';
}

$query_tipo_componente = $connessione->query("select * from tipo_componente ");

foreach ($query_tipo_componente as $row) {
    $tipo_componente = $tipo_componente . '<option value="' . $row['id_tipo_componente'] . '" >' . $row['descrizione'] . '</option>';
}

if (!empty($_POST['conferma'])) {
    $componente = $_POST['inventario'];

    $connessione->query("INSERT INTO `" . $config['dbname'] . "`.`componente` (`n_inventario`, `id_tipo_componente`, `id_laboratorio`) VALUES ('" . $componente . "', '" . $_POST['tipo_componente'] . "', '" . $_POST['laboratorio'] . "');");

    $messaggio = "AVVISO: Inserimento del nuovo ticket avvenuto con successo";
    echo "<script language='javascript'>"
    . "alert('$messaggio');"
    . "window.location.href = './gestione_componenti.php';"
    . "</script>";

}
?>
<html>
    <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <title>Nuovo componente</title>
    </head>
    <body>



        <form method="post">
            <div class="form-inline">
                <label>Componente:</label>
                <h6>&emsp;&emsp;</h6>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="inventario" name="inventario">
                </div>
            </div>
            <br>
            <select name="tipo_componente" id="tipo_componente" >
<?php echo $tipo_componente; ?>
            </select>
            <br>
            <br>
            <select name="laboratorio" id="laboratorio" >
<?php echo $laboratori; ?>
            </select>
            <br>
            <br>
            <button type="submit" name="conferma" value="conferma" class="btn btn-primary">Conferma</button>
        </form>




        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="../../jquery/jquery-3.3.1.js"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>-->
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../dataTables/jquery.dataTables.min.js"></script>
    </body>
</html>
