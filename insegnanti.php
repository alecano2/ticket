<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
session_start();

//var_dump($_SESSION);
//
//if (!empty($_SESSION['login'])){
//    echo '1';
//    if ($_SESSION['login'] == false) {
//        echo '2';
//        header("location:index.php");
//    }
//}
//session_abort();

if ($_SESSION['login'] == false) {
    header("location:./index.php");
}



if ($_SESSION['id_utente'] != 2) {
    switch ($_SESSION['id_tipo_utente']) {
        case 1:
            header("location:./admin_page.php");
            break;
        case 3:
            header("location:./tecnici.php");
            break;
    }
}



$segnalazioni=$segnalazioni_archiviate="";
$collapse=1;








$tabella = $dati_tabella = "";
$config = parse_ini_file('./config.ini');

try {
    $connessione = new PDO($config['dbType'] . "host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
    $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$query = "select s.id_segnalazione segnalazione, s.Descrizione, s.id_componente_incriminato, tc.descrizione componente, s.`data`, s.ora, u.username insegnante, s.id_tecnico tecnico, tp.descrizione tipo_di_problema, st.descrizione stato_segnalazione
from segnalazione s join componente c join utente u join tipo_problema tp join stato_segnalazione st join tipo_componente tc 
on(s.id_componente_incriminato=c.n_inventario and s.id_insegnante=u.id_utente and s.id_problema=tp.id_tipo_problema and s.id_stato_segnalazione=st.id_stato_segnalazione and c.id_tipo_componente=tc.id_tipo_componente) where s.id_insegnante=" . $_SESSION['id_utente']." order by s.id_segnalazione" ;
//echo $query;
$result = $connessione->query($query);

//$tabella = '<table id="segnalazioni" class="table table-striped table-bordered display " cellspacing="0" width="100%">
//                    <thead class="thead-dark">
//                        <tr>
//                            <th scope="col">Segnalazione</th>
//                            <th scope="col">Descrizione</th>
//                            <th scope="col">Componente</th>
//                            <th scope="col">Tipo componente</th>
//                            <th scope="col">Laboratorio</th>
//                            <th scope="col">Data</th>                            
//                            <th scope="col">Ora</th>
//                            <th scope="col">Insegnate</th>
//                            <th scope="col">Tecnico</th>
//                            <th scope="col">Tipo di problema</th>
//                            <th scope="col">Stato della segnalazione</th>
//                        </tr>
//                    </thead>';

foreach ($result as $row) {

    if ($row['tecnico'] != NULL) {
        $query_tecnico = "select username from utente where id_utente =" . $row['tecnico'];

        $result1 = $connessione->query($query_tecnico);

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
    
    $query_insegnante="select nome, cognome from utente where username='".$row['insegnante']."'";
    
    $result3=$connessione->query($query_insegnante);
    
    foreach ($result3 as $row3){
        $nome_insegnante=$row3['nome'];
        $cognome_insegnante=$row3['cognome'];
    }


//    $dati_tabella = $dati_tabella . '<tr><th scope="row">' . $row['segnalazione'] . '</th>
//                    <td width="30%">' . $row['Descrizione'] . '</td>
//                        <td>' . $row['id_componente_incriminato'] . '</td>
//                        <td>' . $row['componente'] . '</td>
//                        <td>' . $lab . '</td>
//                        <td>' . $row['data'] . '</td>
//                            <td>' . $row['ora'] . '</td>
//                                <td>' . $row['insegnante'] . '</td>
//                                <td>' . $row['tecnico'] . '</td>
//                                <td>' . $row['tipo_di_problema'] . '</td>
//                                    <td>' . $row['stato_segnalazione'] . '</td></tr>';


	if($row['stato_segnalazione']=='RISOLTO'){
		$segnalazioni_archiviate=$segnalazioni_archiviate.'<a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between" data-toggle="collapse" data-target="#collapse-'.$collapse.'" >
                <p class="mb-0">ID: '.$row['segnalazione'].'  '.'Priorità: <span class="badge badge-pill badge-warning">Media</span></p>
                <span> 
                    <small class="text-muted align-text-top">3 days ago</small>
                    <i class="fa fa-caret-down ml-1"></i>
                </span>
            </div>
            <div class="collapse pt-4" id="collapse-'.$collapse.'">
                <div class="row">
                    <div class="col-md-5">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Segnalato da:</b> '.$cognome_insegnante.' '.$nome_insegnante.'</li>
                            <li class="list-group-item"><b>Data:</b> '.$row['data'].'  '.$row['ora'].'</li>
                            <li class="list-group-item"><b>Zona:</b> '.$lab.'</li>
                            <li class="list-group-item"><b>Componente:</b> ' . $row['componente'].'  '. $row['id_componente_incriminato'] . '</li>
                            <li class="list-group-item"><b>Tipologia:</b> '.$row['tipo_di_problema'].'</li>
                            <li class="list-group-item"><b>Tecnico:</b> '.$row['tecnico'].'</li>
                            <li class="list-group-item"><b>Stato della segnalazione:</b> '.$row['stato_segnalazione'].'</li>
                        </ul>
                    </div>
                    <div class="col-md-7">
                        <p class="mb-2 mt-3"><b>Descrizione</b></p>
                        <p class="mb-3">
                            '.$row['Descrizione'].'
                        </p>
                    </div>
                </div>

                <div class="d-flex w-100 justify-content-end mb-1">
                    <input class="btn btn-pill px-4 btn-primary" type="button" value="Gestisci">
                </div>
            </div>
        </a>';
	}else{
		$segnalazioni=$segnalazioni.'<a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between" data-toggle="collapse" data-target="#collapse-'.$collapse.'" >
                <p class="mb-0">ID: '.$row['segnalazione'].'  '.'Priorità: <span class="badge badge-pill badge-warning">Media</span></p>
                <span> 
                    <small class="text-muted align-text-top">3 days ago</small>
                    <i class="fa fa-caret-down ml-1"></i>
                </span>
            </div>
            <div class="collapse pt-4" id="collapse-'.$collapse.'">
                <div class="row">
                    <div class="col-md-5">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Segnalato da:</b> '.$cognome_insegnante.' '.$nome_insegnante.'</li>
                            <li class="list-group-item"><b>Data:</b> '.$row['data'].'  '.$row['ora'].'</li>
                            <li class="list-group-item"><b>Zona:</b> '.$lab.'</li>
                            <li class="list-group-item"><b>Componente:</b> ' . $row['componente'].'  '. $row['id_componente_incriminato'] . '</li>
                            <li class="list-group-item"><b>Tipologia:</b> '.$row['tipo_di_problema'].'</li>
                            <li class="list-group-item"><b>Tecnico:</b> '.$row['tecnico'].'</li>
                            <li class="list-group-item"><b>Stato della segnalazione:</b> '.$row['stato_segnalazione'].'</li>
                        </ul>
                    </div>
                    <div class="col-md-7">
                        <p class="mb-2 mt-3"><b>Descrizione</b></p>
                        <p class="mb-3">
                            '.$row['Descrizione'].'
                        </p>
                    </div>
                </div>

                <div class="d-flex w-100 justify-content-end mb-1">
                    <input class="btn btn-pill px-4 btn-primary" type="button" value="Gestisci">
                </div>
            </div>
        </a>';
	}
    
    
    
	$collapse=$collapse+1;
}



if (!empty($_POST['nuovo_ticket'])) {
    session_start();
    header("location:nuovo_ticket.php");
} else if (!empty($_POST['esci'])) {
    $_SESSION['login'] = false;
    header("location:index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Ticket</title>


        <link rel="stylesheet" href="./coreui/coreui.min.css">
        <link rel="stylesheet" href="https://unpkg.com/@coreui/icons/css/coreui-icons.min.css">
        <link rel="stylesheet" href="./css/simple-line-icons.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <link rel="stylesheet" href="./css/style_dashboard.css">
    </head>


    <body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
        <header class="app-header navbar">
            <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
                <span class="navbar-toggler-icon"></span>
            </button>
            <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand mr-auto" href="#">
                <img class="navbar-brand-full" src="./assets/img/logo.gif" height="25" alt="logo">
                <img class="navbar-brand-minimized" src="./assets/img/logo.gif" width="30" height="30" alt="logo">
            </a>

            <ul class="nav navbar-nav d-md-down-none">
                <li class="nav-item px-3">
					<form method="post">
						<button class="nav-link" value="nuovo_ticket" name="nuovo_ticket"><i class="cui-account-logout mr-2"></i>Nuovo ticket</button>
					</form>
                
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link" ><i class="cui-user mr-2"></i><?php echo $_SESSION['cognome'];?></a>
                </li>
                <li class="nav-item px-3">
					<form method="post">
						<button class="nav-link" value="esci" name="esci"><i class="cui-account-logout mr-2"></i>Esci</button>
					</form>
				</li>
            </ul>
        </header>

        <div class="app-body">


            <div class="sidebar">
                <nav class="sidebar-nav">
                    <ul class="nav">
                        <li class="nav-title">Ticket</li>
                        <li class="nav-item">
                            <a class="nav-link" href="colors.html">Priorità alta<span class="badge badge-pill badge-danger">3</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="colors.html">Priorità media<span class="badge badge-pill badge-warning">8</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="colors.html">Priorità bassa<span class="badge badge-pill badge-success">12</span></a>
                        </li>
                    </ul>
                </nav>
            </div>


            <main class="main">
                <div class="container py-3">
                    <div class="row mb-5">
                        <div class="col-md-9 mx-auto">
                            <h2 class="border-bottom pb-2 mb-3">Le mie segnalazioni</h2>

                            <div class="list-group mb-3">
                                
                                <?php echo $segnalazioni?>
                            </div>



                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9 mx-auto">
                            <h2 class="border-bottom pb-2 mb-3">Archiviati</h2>

                            <div class="list-group mb-3">
                                <?php echo $segnalazioni_archiviate; ?>
                            </div>



                        </div>
                    </div>
                </div>
            </main>
        </div>


        <footer class="app-footer">
            <div>
                <span>&copy; 2019 I.T.I.S. Levi-Ponti</span>
            </div>
        </footer>
        
        <script src="./jquery/jquery-3.3.1.js"></script>
        <script src="./popper/popper.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
        <script src="./coreui/coreui.min.css"></script>

<!--        <script src="./jquery/jquery-3.3.1.js"></script>
        <script src="./popper/popper.min.js"></script>
        <script src="./css/bootstrap.min.css"></script>
        <script src="./coreui/coreui.min.css"></script>-->
        <script type="text/javascript">
            $(".collapse").collapse({toggle: false});
        </script>
    </body>
</html>



<!--<html>
    <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <link rel="stylesheet" href="./dataTables/jquery.dataTables.min.css">

        <title>Dashboard</title>
    </head>
    <body>


        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">
                    <form method="post">
                        <button type="submit" name="nuovo_ticket" value="nuovo_ticket" class="btn btn-primary">Crea nuovo ticket</button>
                        <button type="submit" name="esci" value="esci" class="btn btn-primary">esci</button>
                    </form>
                </div>

            </div>



<?php
//echo 'Benvenuto Prof.' . $_SESSION['cognome'];
?>
            <br>
            <br>
            <div class="col-sm-12">
<?php
//                echo $tabella;
//                echo $dati_tabella;
//                echo '</table>';
?>
            </div>


        </div>



       
        <script src="./jquery/jquery-3.3.1.js"></script>
        <script src="./js/bootstrap.min.js"></script>
        <script src="./dataTables/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function () {
                $('#segnalazioni').DataTable({
                    paging: true
                });
            });
        </script>
    </body>
</html>-->