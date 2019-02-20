<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
session_start();

$laboratori = $problema = $componenti = $descrizione = $laboratorio = $stato_segnalazione = $tecnico = $insegnante = "";

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
if (!empty($_GET['id_segnalazione'])) {
    $id_segnalazione = $_GET['id_segnalazione'];
}




$config = parse_ini_file('../../config.ini');

try {
    $connessione = new PDO($config['dbType'] . "host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
    $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}




//laboratorio



$query_laboratori = "select * from laboratorio";
$result2 = $connessione->query($query_laboratori);
foreach ($result2 as $row2) {
    $laboratori = $laboratori . '<option value="' . $row2['id_laboratorio'] . '">' . $row2['descrizione'] . '</option>';
}


//stato_segnalazione
$query_stato_segnalazione = "select *
        from stato_segnalazione ";
$result1 = $connessione->query($query_stato_segnalazione);

foreach ($result1 as $row1) {
    $stato_segnalazione = $stato_segnalazione . '<option value="' . $row1['id_stato_segnalazione'] . '">' . $row1['descrizione'] . '</option>';
}





//tipo_problema
$query_problema = "select *
        from tipo_problema ";
$result1 = $connessione->query($query_problema);

foreach ($result1 as $row1) {
    $problema = $problema . '<option value="' . $row1['id_tipo_problema'] . '">' . $row1['descrizione'] . '</option>';
}



//insegnanti

$query_insegnanti = "select *
        from utente 
        where id_tipo_utente=2";
$result1 = $connessione->query($query_insegnanti);

foreach ($result1 as $row1) {
    $insegnante = $insegnante . '<option value="' . $row1['id_utente'] . '">' . $row1['cognome'] . " " . $row1['nome'] . '</option>';
}


//tecnici



$query_tecnici_null = "select *
        from utente 
        where id_tipo_utente=3";
$result1 = $connessione->query($query_tecnici_null);

foreach ($result1 as $row1) {
    $tecnico = $tecnico . '<option value="' . $row1['id_utente'] . '">' . $row1['cognome'] . " " . $row1['nome'] . '</option>';
}





var_dump($_POST);
if (!empty($_POST['conferma'])) {

    $descrizione = $_POST['descrizione'];
    $insegnante = $_POST['insegnante'];
    $componente = $_POST['componenti'];
    $tecnico = $_POST['tecnico'];
    $problema = $_POST['problema'];
    $stato_segnalazione = $_POST['stato_segnalazione'];

    if ($tecnico != 0) {
        $conferma = "INSERT INTO `".$config['dbname']."`.`segnalazione` "
                . "( `descrizione`, `id_componente_incriminato`, `data`, `ora`,"
                . " `id_insegnante`,`id_tecnico`,  `id_problema`, `id_stato_segnalazione`) "
                . "VALUES ( '".$descrizione."', '".$componente."', "
                . "'".date("Y-m-d")."', '".date("H:i:s")."', '".$insegnante."', '".$tecnico."',"
                . " '".$problema."', '".$stato_segnalazione."')";
    } else {
        $conferma = "INSERT INTO `".$config['dbname']."`.`segnalazione` "
                . "( `descrizione`, `id_componente_incriminato`, `data`, `ora`,"
                . " `id_insegnante`,  `id_problema`, `id_stato_segnalazione`) "
                . "VALUES ( '".$descrizione."', '".$componente."', "
                . "'".date("Y-m-d")."', '".date("H:i:s")."', '".$insegnante."',"
                . " '".$problema."', '".$stato_segnalazione."')";
    }


    $result = $connessione->query($conferma);

    if ($result) {

        $messaggio = "AVVISO: Inserimento del ticket avvenuto con successo";
        echo "<script language='javascript'>"
        . "alert('$messaggio');"
        . "window.location.href = './gestione_segnalazioni.php';"
        . "</script>";
    }else {
    echo 'errore nella modifica dei dati';
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
        <title>Nuova segnalazione</title>
    </head>
    <body>


        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <form method="post">
                        <div class="form-group">
                            <label for="descrizione">Dettagli segnalazione</label>
                            <textarea name="descrizione" class="form-control" id="descrizione" rows="4"><?php echo $descrizione; ?></textarea>
                        </div>
                        <div class="form-inline">
                            <select name="laboratorio" onchange="getId(this.value)">
                                <option value="0">Seleziona laboratorio</option>
<?php echo $laboratori; ?>
                            </select>

                            <h6>&emsp;&emsp;</h6>
                            <select name="componenti" id="componenti" >
                                <option value="0">Seleziona componente</option>
<?php echo $componenti; ?>
                            </select>
                        </div>
                        <div class="form-inline">
                            <select name="problema" >
                                <option value="0">Seleziona il tipo di problema</option>
<?php echo $problema; ?>
                            </select>
                        </div>
                        <div class="form-inline">
                            <select name="stato_segnalazione" >
                                <option value="0">Seleziona stato della segnalazione</option>
<?php echo $stato_segnalazione; ?>
                            </select>
                        </div>
                        <div class="form-inline">
                            <select name="insegnante" >
                                <option value="0">Seleziona insegnante</option>
<?php echo $insegnante; ?>
                            </select>
                        </div>
                        <div class="form-inline">
                            <select name="tecnico" >
                                <option value="0">Seleziona tecnico</option>
<?php echo $tecnico; ?>
                            </select>
                        </div>
                        <div class="form-inline">
                            <button type="submit" name="conferma" value="conferma" class="btn btn-primary">Conferma</button>
                        </div>
                    </form>
                </div>
            </div>                
        </div>

        <script>
            function getId(val) {
                $.ajax({
                    type: "POST",
                    url: "../../getdata.php",
                    data: "id_laboratorio=" + val,
                    success: function (data) {
                        $("#componenti").html(data);
                    }
                });
            }
        </script>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="../../jquery/jquery-3.3.1.js"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>-->
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../dataTables/jquery.dataTables.min.js"></script>
    </body>
</html>
