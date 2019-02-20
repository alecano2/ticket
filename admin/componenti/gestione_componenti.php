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
$erroreLaboratorio = $tabella = $componente = "";




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

$config = parse_ini_file('../../config.ini');

try {
    $connessione = new PDO($config['dbType'] . "host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
    $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}





$querytabella = "select c.n_inventario inventario, tc.descrizione tipo_componente, l.descrizione laboratorio
from componente c join tipo_componente tc join laboratorio l
on(c.id_tipo_componente=tc.id_tipo_componente and c.id_laboratorio=l.id_laboratorio)";

$result = $connessione->query($querytabella);

foreach ($result as $row) {
    $tabella = $tabella . '<tr><th scope="row">' . $row['inventario'] . '</th>
        <td>' . $row['tipo_componente'] . '</td>
        <td>' . $row['laboratorio'] . '</td>'
            . '<td><button type="submit" name="modifica_componente" value="' . $row['inventario'] . '" class="btn btn-danger" >Modifica</button></td>'
            . '<td><button type="submit" name="elimina_componente" value="' . $row['inventario'] . '" class="btn btn-danger" >Elimina</button></td>'
            . '</tr>';
}


if (!empty($_POST['elimina_componente'])) {
    $componente = $_POST['elimina_componente'];

    $query_verifica_segnalazioni = "select * from segnalazione where id_componente_incriminato =" . $componente;


    $result = $connessione->query($query_verifica_segnalazioni);


    if ($result->fetch(PDO::FETCH_ASSOC)) {
//        echo '<script>'
//        . 'var domanda = confirm("Il componente che vuoi eliminare presenta delle segnalazioni a suo carico. Sicuro di volerlo eliminale?");
//            
//            var prova="";
//            
//            if (domanda === true) {
//                    
//                
//            } else {
//                alert("Operazione annullata");
//                prova="2";            
//                }'
//        . '</script>';

        echo 'Impossibile eliminare il componente selezionato poichè soggetto di una o più segnalazioni';
        
//        echo '<script>'
//        . 'elimina();'
//        . 'alert("Il componente selezionato e le segnalazioni a suo carico sono state eliminate correttamente");'
//        . '</script>';
        
    } else {
        $query_elimina_componente = "DELETE FROM `" . $config['dbname'] . "`.`componente` WHERE  `n_inventario`=" . $componente;

        $connessione->query($query_elimina_componente);

        echo 'Componente eliminato con successo';

        $tabella = "";

        $querytabella = "select c.n_inventario inventario, tc.descrizione tipo_componente, l.descrizione laboratorio
        from componente c join tipo_componente tc join laboratorio l
        on(c.id_tipo_componente=tc.id_tipo_componente and c.id_laboratorio=l.id_laboratorio)";

        $result = $connessione->query($querytabella);

        foreach ($result as $row) {
            $tabella = $tabella . '<tr><th scope="row">' . $row['inventario'] . '</th>
        <td>' . $row['tipo_componente'] . '</td>
        <td>' . $row['laboratorio'] . '</td>'
                    . '<td><button type="submit" name="modifica_componente" value="' . $row['inventario'] . '" class="btn btn-danger" >Modifica</button></td>'
                    . '<td><button type="submit" name="elimina_componente" value="' . $row['inventario'] . '" class="btn btn-danger" >Elimina</button></td>'
                    . '</tr>';
        }
    }
} else if (!empty($_POST['modifica_componente'])) {
    header("location:dettagli_componente.php?inventario=$_POST[modifica_componente]");
} else if (!empty($_POST['nuovo'])) {
    header("location:nuovo_componente.php");
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
        <title>Gestione componenti</title>
    </head>
    <body>

        <div class="container">
            <div class="row">
                <form method="post">
                    <div class="form-inline">
                        <button type="submit" name="esci" value="esci" class="btn btn-primary">esci</button>
                        <h6>&emsp;&emsp;</h6>
                        <button type="submit" name="nuovo" value="nuovo" class="btn btn-primary">Nuovo Componente</button>
                        <h6>&emsp;&emsp;</h6>
                        <button type="submit" name="pagina_principale" value="pagina_principale" class="btn btn-primary">Pagina principale</button>
                        <h6>&emsp;&emsp;</h6>
                    </div>
                    <br>
                    <br>
                    <div class="form-inline">
                        <table id="componenti" class="table table-striped table-bordered display " cellspacing="0" width="100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Numero inventario</th>
                                    <th scope="col">Tipo componente</th>
                                    <th scope="col">Laboratorio</th>
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
                $('#componenti').DataTable({
                    paging: true
                });
            });

            
        </script>

        <script>
            function elimina() {
                var d = confirm("Il componente che vuoi eliminare presenta delle segnalazioni a suo carico. Sicuro di volerlo eliminale?");
                
                if (d === true) {
                    // 
//                        $connessione->query("DELETE FROM `".$config['dbname']."`.`segnalazione` WHERE  `id_componente_incriminato`=".$componente);
//                        $connessione->query("DELETE FROM `" . $config['dbname'] . "`.`componente` WHERE  `n_inventario`=" . $componente);
//                    
                    alert("Il componente selezionato e le segnalazioni a suo carico sono state eliminate correttamente");
                } else {
                    alert("Operazione annullata");
                }
            }
        </script>

    </body>
</html>
