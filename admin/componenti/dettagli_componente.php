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

$tipo_componente = $laboratori = $tipo_componente_modificato = "";

if (!empty($_GET['inventario'])) {
    $componente = $_GET['inventario'];

    $config = parse_ini_file('../../config.ini');

    try {
        $connessione = new PDO($config['dbType'] . "host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
        $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    $query_ricerca_componente = "select * from componente where n_inventario=" . $componente;

    $result = $connessione->query($query_ricerca_componente);

    foreach ($result as $row) {

        $query_tipo_componente = "select * from tipo_componente where id_tipo_componente=" . $row['id_tipo_componente'];

        $result1 = $connessione->query($query_tipo_componente);

        foreach ($result1 as $row1) {
            $tipo_componente = $tipo_componente . '<option value="' . $row1['id_tipo_componente'] . '">' . $row1['descrizione'] . '</option>';
        }


        $query_tipo_componente_esclusivo = "select * from tipo_componente where id_tipo_componente!=" . $row['id_tipo_componente'];

        $result1 = $connessione->query($query_tipo_componente_esclusivo);

        foreach ($result1 as $row1) {
            $tipo_componente = $tipo_componente . '<option value="' . $row1['id_tipo_componente'] . '">' . $row1['descrizione'] . '</option>';
        }








        $query_laboratori = "select * from laboratorio where id_laboratorio=" . $row['id_laboratorio'];

        $result1 = $connessione->query($query_laboratori);

        foreach ($result1 as $row1) {
            $laboratori = $laboratori . '<option value="' . $row1['id_laboratorio'] . '">' . $row1['descrizione'] . '</option>';
        }


        $query_laboratori_esclusivo = "select * from laboratorio where id_laboratorio!=" . $row['id_laboratorio'];

        $result1 = $connessione->query($query_laboratori_esclusivo);

        foreach ($result1 as $row1) {
            $laboratori = $laboratori . '<option value="' . $row1['id_laboratorio'] . '">' . $row1['descrizione'] . '</option>';
        }
    }
}

if (!empty($_POST['conferma'])) {

    
    $laboratorio = $_POST['laboratorio'];
    $tipo_componente_modificato = $_POST['tipo_componente'];

    $query_modifica = "UPDATE `" . $config['dbname'] . "`.`componente` SET `id_tipo_componente`='" . $tipo_componente_modificato . "', `id_laboratorio`='" . $laboratorio . "' WHERE  `n_inventario`=" . $componente;
    echo $query_modifica;
    $connessione->query($query_modifica);

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
        <link rel="stylesheet" href="../../dataTables/jquery.dataTables.min.css">
        <title>Dettagli componente</title>
    </head>
    <body>


        <form method="post">
            <label>Componente: <?php echo $componente; ?></label>
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
