<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
session_start();


$tabella = "";

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


$config = parse_ini_file('../../config.ini');

try {
    $connessione = new PDO($config['dbType'] . "host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
    $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$query = "select s.id_segnalazione segnalazione, s.Descrizione, s.id_componente_incriminato, tc.descrizione componente, s.`data`, s.ora, u.username insegnante, s.id_tecnico tecnico, tp.descrizione tipo_di_problema, st.descrizione stato_segnalazione
from segnalazione s join componente c join utente u join tipo_problema tp join stato_segnalazione st join tipo_componente tc
on(s.id_componente_incriminato=c.n_inventario and s.id_insegnante=u.id_utente and s.id_problema=tp.id_tipo_problema and s.id_stato_segnalazione=st.id_stato_segnalazione and c.id_tipo_componente=tc.id_tipo_componente)";

$result = $connessione->query($query);

foreach ($result as $row) {

    if ($row['tecnico'] != NULL) {
        $prova = "select username from utente where id_utente =" . $row['tecnico'];

        $result1 = $connessione->query($prova);

        foreach ($result1 as $row1) {
            $row['tecnico'] = $row1['username'];
        }
    } else {
        $row['tecnico'] = "Non ancora preso in carico";
    }

    $query_laboratorio = "select  l.descrizione descrizione
        from componente c join laboratorio l on(c.id_laboratorio=l.id_laboratorio)
        where c.n_inventario=" . $row['id_componente_incriminato'];

    $result2 = $connessione->query($query_laboratorio);

    foreach ($result2 as $row2) {
        $lab = $row2['descrizione'];
    }

    $tabella = $tabella . '<tr><th scope="row">' . $row['segnalazione'] . '</th>
        <td>' . $row['Descrizione'] . '</td>
        <td>' . $row['id_componente_incriminato'] . '</td>
        <td>' . $row['componente'] . '</td>
        <td>' . $lab . '</td>
        <td>' . $row['data'] . '</td>
        <td>' . $row['ora'] . '</td>
        <td>' . $row['insegnante'] . '</td>
        <td>' . $row['tecnico'] . '</td>
        <td>' . $row['tipo_di_problema'] . '</td>
        <td>' . $row['stato_segnalazione'] . '</td>
        <td><button type="submit" name="modifica_segnalazione" value="' . $row['segnalazione'] . '" class="btn btn-danger" >Modifica</button></td>
        <td><button type="submit" name="elimina_segnalazione" value="' . $row['segnalazione'] . '" class="btn btn-danger" >Elimina</button></td></tr>';
}



if (!empty($_POST['elimina_segnalazione'])) {
    
    
    
    $segnalazione =$_POST['elimina_segnalazione'];
    
    $query_elimina_segnalazione="DELETE FROM `".$config['dbname']."`.`segnalazione` WHERE  `id_segnalazione`=".$segnalazione;
    
    $connessione->query($query_elimina_segnalazione);
    
    echo 'Segnalazione eliminata con successo';
    
    $tabella="";
    
    $query = "select s.id_segnalazione segnalazione, s.Descrizione, s.id_componente_incriminato, tc.descrizione componente, s.`data`, s.ora, u.username insegnante, s.id_tecnico tecnico, tp.descrizione tipo_di_problema, st.descrizione stato_segnalazione
from segnalazione s join componente c join utente u join tipo_problema tp join stato_segnalazione st join tipo_componente tc
on(s.id_componente_incriminato=c.n_inventario and s.id_insegnante=u.id_utente and s.id_problema=tp.id_tipo_problema and s.id_stato_segnalazione=st.id_stato_segnalazione and c.id_tipo_componente=tc.id_tipo_componente)";

$result = $connessione->query($query);

foreach ($result as $row) {

    if ($row['tecnico'] != NULL) {
        $prova = "select username from utente where id_utente =" . $row['tecnico'];

        $result1 = $connessione->query($prova);

        foreach ($result1 as $row1) {
            $row['tecnico'] = $row1['username'];
        }
    } else {
        $row['tecnico'] = "Non ancora preso in carico";
    }

    $query_laboratorio = "select  l.descrizione descrizione
        from componente c join laboratorio l on(c.id_laboratorio=l.id_laboratorio)
        where c.n_inventario=" . $row['id_componente_incriminato'];

    $result2 = $connessione->query($query_laboratorio);

    foreach ($result2 as $row2) {
        $lab = $row2['descrizione'];
    }

    $tabella = $tabella . '<tr><th scope="row">' . $row['segnalazione'] . '</th>
        <td>' . $row['Descrizione'] . '</td>
        <td>' . $row['id_componente_incriminato'] . '</td>
        <td>' . $row['componente'] . '</td>
        <td>' . $lab . '</td>
        <td>' . $row['data'] . '</td>
        <td>' . $row['ora'] . '</td>
        <td>' . $row['insegnante'] . '</td>
        <td>' . $row['tecnico'] . '</td>
        <td>' . $row['tipo_di_problema'] . '</td>
        <td>' . $row['stato_segnalazione'] . '</td>
        <td><button type="submit" name="modifica_segnalazione" value="' . $row['segnalazione'] . '" class="btn btn-danger" >Modifica</button></td>
        <td><button type="submit" name="elimina_segnalazione" value="' . $row['segnalazione'] . '" class="btn btn-danger" >Elimina</button></td></tr>';
}
    
    
    
    
} else if (!empty($_POST['modifica_segnalazione'])) {
    header("location:modifica_segnalazione.php?id_segnalazione=$_POST[modifica_segnalazione]");
} else if (!empty($_POST['nuovo'])) {
    header("location:nuova_segnalazione.php");
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
        <title>Gestione segnalazioni</title>
    </head>
    <body>


        <div class="container">
            <div class="row">
                <form method="post">
                    <div class="form-inline">
                        <button type="submit" name="esci" value="esci" class="btn btn-primary">esci</button>
                        <h6>&emsp;&emsp;</h6>
                        <button type="submit" name="nuovo" value="nuovo" class="btn btn-primary">Nuova segnalazione</button>
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
                                    <th scope="col">Segnalazione</th>
                                    <th scope="col">Descrizione</th>
                                    <th scope="col">Componente</th>
                                    <th scope="col">Tipo componente</th>
                                    <th scope="col">Laboratorio</th>
                                    <th scope="col">Data</th>                            
                                    <th scope="col">Ora</th>
                                    <th scope="col">Insegnate</th>
                                    <th scope="col">Tecnico</th>
                                    <th scope="col">Tipo di problema</th>
                                    <th scope="col">Stato della segnalazione</th>                            
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
    </body>
</html>
