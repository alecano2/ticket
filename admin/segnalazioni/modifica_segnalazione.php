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

$query = "select * from segnalazione where id_segnalazione =" . $id_segnalazione;

$result = $connessione->query($query);


foreach ($result as $row) {


    $descrizione = $row['Descrizione'];


    //laboratorio
    $query_id_laboratorio = "select id_laboratorio  from componente where n_inventario=" . $row['id_componente_incriminato'];
    $result1 = $connessione->query($query_id_laboratorio);

    foreach ($result1 as $row1) {
        $laboratorio = $row1['id_laboratorio'];
        $query_laboratori = "select * from laboratorio where id_laboratorio=" . $laboratorio;
        $result2 = $connessione->query($query_laboratori);
        foreach ($result2 as $row2) {
            $laboratori = $laboratori . '<option value="' . $row2['id_laboratorio'] . '">' . $row2['descrizione'] . '</option>';
        }
        $query_laboratori_esclusivo = "select * from laboratorio where id_laboratorio!=" . $row1['id_laboratorio'];

        $result3 = $connessione->query($query_laboratori_esclusivo);

        foreach ($result3 as $row3) {
            $laboratori = $laboratori . '<option value="' . $row3['id_laboratorio'] . '">' . $row3['descrizione'] . '</option>';
        }
    }


    //componenti
    $query_componenti = "select n_inventario from componente where id_laboratorio=" . $laboratorio . " and n_inventario=" . $row['id_componente_incriminato'];
    $result1 = $connessione->query($query_componenti);

    foreach ($result1 as $row1) {
        $componenti = $componenti . '<option value="' . $row1['n_inventario'] . '">' . $row1['n_inventario'] . '</option>';
    }

    $query_componenti_esclusivo = "select n_inventario from componente where id_laboratorio=" . $laboratorio . " and n_inventario!=" . $row['id_componente_incriminato'];
    $result1 = $connessione->query($query_componenti_esclusivo);

    foreach ($result1 as $row1) {
        $componenti = $componenti . '<option value="' . $row1['n_inventario'] . '">' . $row1['n_inventario'] . '</option>';
    }


    //stato_segnalazione
    $query_stato_segnalazione = "select *
        from stato_segnalazione 
        where id_stato_segnalazione=" . $row['id_stato_segnalazione'];
    $result1 = $connessione->query($query_stato_segnalazione);

    foreach ($result1 as $row1) {
        $stato_segnalazione = $stato_segnalazione . '<option value="' . $row1['id_stato_segnalazione'] . '">' . $row1['descrizione'] . '</option>';
    }

    $query_stato_segnalazione_esclusivo = "select *
        from stato_segnalazione 
        where id_stato_segnalazione!=" . $row['id_stato_segnalazione'];
    $result1 = $connessione->query($query_stato_segnalazione_esclusivo);

    foreach ($result1 as $row1) {
        $stato_segnalazione = $stato_segnalazione . '<option value="' . $row1['id_stato_segnalazione'] . '">' . $row1['descrizione'] . '</option>';
    }



    //tipo_problema
    $query_problema = "select *
        from tipo_problema 
        where id_tipo_problema=" . $row['id_problema'];
    $result1 = $connessione->query($query_problema);

    foreach ($result1 as $row1) {
        $problema = $problema . '<option value="' . $row1['id_tipo_problema'] . '">' . $row1['descrizione'] . '</option>';
    }

    $query_problema_esclusivo = "select *
        from tipo_problema 
        where id_tipo_problema!=" . $row['id_problema'];
    $result1 = $connessione->query($query_problema_esclusivo);

    foreach ($result1 as $row1) {
        $problema = $problema . '<option value="' . $row1['id_tipo_problema'] . '">' . $row1['descrizione'] . '</option>';
    }



    //insegnanti

    $query_insegnanti = "select *
        from utente 
        where id_utente=" . $row['id_insegnante'];
    $result1 = $connessione->query($query_insegnanti);

    foreach ($result1 as $row1) {
        $insegnante = $insegnante . '<option value="' . $row1['id_utente'] . '">' . $row1['cognome'] . " " . $row1['nome'] . '</option>';
    }

    $query_insegnanti_esclusivo = "select *
        from utente 
        where id_utente!=" . $row['id_insegnante'] . " and id_tipo_utente=2";
    $result1 = $connessione->query($query_insegnanti_esclusivo);

    foreach ($result1 as $row1) {
        $insegnante = $insegnante . '<option value="' . $row1['id_utente'] . '">' . $row1['cognome'] . " " . $row1['nome'] . '</option>';
    }


    //tecnici

    if ($row['id_tecnico'] == NULL) {
        $tecnico = $tecnico . '<option value="0">Seleziona tecnico</option>';

        $query_tecnici_null = "select *
        from utente 
        where id_tipo_utente=3";
        $result1 = $connessione->query($query_tecnici_null);

        foreach ($result1 as $row1) {
            $tecnico = $tecnico . '<option value="' . $row1['id_utente'] . '">' . $row1['cognome'] . " " . $row1['nome'] . '</option>';
        }
    } else {
        $query_tecnici = "select *
        from utente 
        where id_utente=" . $row['id_tecnico'];
        $result1 = $connessione->query($query_tecnici);

        foreach ($result1 as $row1) {
            $tecnico = $tecnico . '<option value="' . $row1['id_utente'] . '">' . $row1['cognome'] . " " . $row1['nome'] . '</option>';
        }
        $query_tecnici_esclusivo = "select *
        from utente 
        where id_utente!=" . $row['id_tecnico'] . " and id_tipo_utente=3";
        $result1 = $connessione->query($query_tecnici_esclusivo);

        foreach ($result1 as $row1) {
            $tecnico = $tecnico . '<option value="' . $row1['id_utente'] . '">' . $row1['cognome'] . " " . $row1['nome'] . '</option>';
        }
    }
}




if (!empty($_POST['conferma'])) {

    $descrizione = $_POST['descrizione'];
    $insegnante = $_POST['insegnante'];
    $componente = $_POST['componenti'];
    $tecnico = $_POST['tecnico'];
    $problema = $_POST['problema'];
    $stato_segnalazione = $_POST['stato_segnalazione'];

    if ($tecnico != 0) {
        $conferma = "UPDATE `" . $config['dbname'] . "`.`segnalazione` SET "
                . "`Descrizione`='" . $descrizione . "', `id_componente_incriminato`='" . $componente . "', "
                . "`id_insegnante`='" . $insegnante . "', `id_tecnico`='" . $tecnico . "', `id_problema`='" . $problema . "', "
                . "`id_stato_segnalazione`='" . $stato_segnalazione . "' WHERE  `id_segnalazione`=" . $id_segnalazione;
    } else {
        $conferma = "UPDATE `" . $config['dbname'] . "`.`segnalazione` SET "
                . "`Descrizione`='" . $descrizione . "', `id_componente_incriminato`='" . $componente . "', "
                . "`id_insegnante`='" . $insegnante . "', `id_problema`='" . $problema . "', "
                . "`id_stato_segnalazione`='" . $stato_segnalazione . "' WHERE  `id_segnalazione`=" . $id_segnalazione;
    }


    $result = $connessione->query($conferma);

    if ($result) {

        $messaggio = "AVVISO: Modifica del ticket avvenuta con successo";
        echo "<script language='javascript'>"
        . "alert('$messaggio');"
        . "window.location.href = './gestione_segnalazioni.php';"
        . "</script>";
    } else {
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
        <title>Modifica Segnalazione</title>
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
<?php echo $laboratori; ?>
                            </select>

                            <h6>&emsp;&emsp;</h6>
                            <select name="componenti" id="componenti" >
<?php echo $componenti; ?>
                            </select>
                        </div>
                        <div class="form-inline">
                            <select name="problema" >
<?php echo $problema; ?>
                            </select>
                        </div>
                        <div class="form-inline">
                            <select name="stato_segnalazione" >
<?php echo $stato_segnalazione; ?>
                            </select>
                        </div>
                        <div class="form-inline">
                            <select name="insegnante" >
<?php echo $insegnante; ?>
                            </select>
                        </div>
                        <div class="form-inline">
                            <select name="tecnico" >
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
