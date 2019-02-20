<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
session_start();

$id_laboratorio = $descrizione = "";

if ($_SESSION['login'] == false) {
    header("location:../../index.php");
}

if ($_SESSION['id_utente'] != 1) {
    switch ($_SESSION['id_tipo_utente']) {
        case 2:
            header("location:../../insegnanti.php");
            break;
        case 3:
            header("location:../../tecnici.php");
            break;
    }
}
if (!empty($_GET['id_laboratorio'])) {
    $id_laboratorio = $_GET['id_laboratorio'];
}




$config = parse_ini_file('../../config.ini');

try {
    $connessione = new PDO($config['dbType'] . "host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
    $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}


$query_laboratorio = "select * from laboratorio where id_laboratorio = " . $id_laboratorio;


$result = $connessione->query($query_laboratorio);

foreach ($result as $row) {
    $descrizione = $row['descrizione'];
}

if (!empty($_POST['conferma'])) {
    $query_conferma = "UPDATE `".$config['dbname']."`.`laboratorio` SET `descrizione`='".$_POST['descrizione']."' WHERE  `id_laboratorio`=".$id_laboratorio;

    $result = $connessione->query($query_conferma);

    if ($result) {
        $messaggio = "AVVISO: Modifica del laboratorio avvenuta con successo";
        echo "<script language='javascript'>"
        . "alert('$messaggio');"
        . "window.location.href = './gestione_laboratori.php';"
        . "</script>";
    } else {
        echo 'errore nella modifica del laboratorio';
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../dataTables/jquery.dataTables.min.css">
        <title>Modifica laboratorio</title>
    </head>
    <body>



        <div class="container">
            <form method="post">
                <div class="form-group">
                    <label for="descrizione">Nome laboratorio</label>
                    <textarea name="descrizione" class="form-control" id="descrizione" rows="4"><?php echo $descrizione; ?></textarea>
                </div>
                <div class="form-inline">
                    <button type="submit" name="conferma" value="conferma" class="btn btn-primary">Conferma</button>
                </div>

            </form>
        </div>







        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="../../jquery/jquery-3.3.1.js"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>-->
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../dataTables/jquery.dataTables.min.js"></script>
    </body>
</html>
