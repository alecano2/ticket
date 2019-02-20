<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
session_start();

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

$tabella="";


$config = parse_ini_file('../../config.ini');

try {
    $connessione = new PDO($config['dbType'] . "host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
    $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$query = "select * from laboratorio";
$result = $connessione->query($query);

foreach ($result as $row) {
    $tabella = $tabella . '<tr><th scope="row">' . $row['id_laboratorio'] . '</th>
        <td>' . $row['descrizione'] . '</td>
        <td><button type="submit" name="modifica_laboratorio" value="' . $row['id_laboratorio'] . '" class="btn btn-danger" >Modifica</button></td>
        <td><button type="submit" name="elimina_laboratorio" value="' . $row['id_laboratorio'] . '" class="btn btn-danger" >Elimina</button></td></tr>';
}

if (!empty($_POST['elimina_laboratorio'])) {
    

    $query_elimina = "DELETE FROM `".$config['dbname']."`.`laboratorio` WHERE  `id_laboratorio`=" . $_POST['elimina_laboratorio'];

    $result = $connessione->query($query_elimina);

    if ($result) {
        $messaggio = "AVVISO: Eliminazione del laboratorio avvenuta con successo";
        echo "<script language='javascript'>"
        . "alert('$messaggio');"
        . "</script>";
    } else {
        echo 'errore nella eliminazione del laboratorio';
    }
    
    $tabella="";

    $query = "select * from laboratorio";
    $result = $connessione->query($query);

    foreach ($result as $row) {
        $tabella = $tabella . '<tr><th scope="row">' . $row['id_laboratorio'] . '</th>
        <td>' . $row['descrizione'] . '</td>
        <td><button type="submit" name="modifica_laboratorio" value="' . $row['id_laboratorio'] . '" class="btn btn-danger" >Modifica</button></td>
        <td><button type="submit" name="elimina_laboratorio" value="' . $row['id_laboratorio'] . '" class="btn btn-danger" >Elimina</button></td></tr>';
    }
} else if (!empty($_POST['modifica_laboratorio'])) {
    header("location:modifica_laboratorio.php?id_laboratorio=$_POST[modifica_laboratorio]");
} else if (!empty($_POST['nuovo'])) {
    header("location:nuovo_laboratorio.php");
} else if (!empty($_POST['esci'])) {
    $_SESSION['login'] = false;
    header("location:../../index.php");
} else if (!empty($_POST['pagina_principale'])) {
    header("location:../../admin_page.php");
}
?>
<html>
    <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../dataTables/jquery.dataTables.min.css">
        <title>Gestione laboratori</title>
    </head>
    <body>


        <div class="container">
            <div class="row">
                <form method="post">
                    <div class="form-inline">
                        <button type="submit" name="esci" value="esci" class="btn btn-primary">esci</button>
                        <h6>&emsp;&emsp;</h6>
                        <button type="submit" name="nuovo" value="nuovo" class="btn btn-primary">Nuovo laboratorio</button>
                        <h6>&emsp;&emsp;</h6>
                        <button type="submit" name="pagina_principale" value="pagina_principale" class="btn btn-primary">Pagina principale</button>
                        <h6>&emsp;&emsp;</h6>
                    </div>
                    <br>
                    <br>
                    <div class="form-inline">
                        <table id="laboratori" class="table table-striped table-bordered display " cellspacing="0" width="100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">ID</th>                            
                                    <th scope="col">Nome laboratorio</th>                      
                                    <th scope="col">Modifica</th>                            
                                    <th scope="col">Elimina</th>
                                </tr>
                            </thead>
                            <?= $tabella ?>
                        </table>
                    </div>
                </form>
            </div>
        </div>



        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="../../jquery/jquery-3.3.1.js"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>-->
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../dataTables/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function () {
                $('#laboratori').DataTable({
                    paging: true
                });
            });
        </script>
    </body>
</html>
