<?php
session_start();

if ($_SESSION['login'] == false) {
    header("location:./index.php");
}

echo 'benvenuto ' . $_SESSION['username'];

//$componente_disabilitato = "disabled";
//$laboratorio_selezionato = "Laboratori";

$laboratori = $componente_incriminato = $componenti = $problema = "";


$config = parse_ini_file('./config.ini');
// Connessione al database
$dbType = $config['dbType'];

$mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['dbname']);
if ($mysqli->connect_error) {
    die('Errore di connessione (' . $mysqli->connect_errno . ')'
            . $mysqli->connect_error);
}


$query = $mysqli->query("select * from laboratorio");

foreach ($query as $row) {

    $laboratori = $laboratori . '<option value="' . $row['id_laboratorio'] . '" >' . $row['descrizione'] . '</option>';
}

$query_problema = $mysqli->query("select * from tipo_problema ");

foreach ($query_problema as $row) {
    $problema = $problema . '<option value="' . $row['id_tipo_problema'] . '" >' . $row['descrizione'] . '</option>';
}

if (!empty($_POST['conferma'])) {
    $lab_inserimento = $_POST['laboratorio'];
    //echo date("H:i:s");
    $descrizione = $_POST['descrizione'];
    $componente_incriminato = $_POST['componenti'];
    //echo "INSERT INTO `ticket`.`segnalazione` ( `descrizione`, `id_componente_incriminato`, `data`, `ora`, `id_insegnante`,  `id_problema`, `id_stato_segnalazione`) VALUES ( '".$descrizione."', '".$componente_incriminato."', '".date("Y-m-d")."', '".date("H:i:s")."', '".$_SESSION['id_utente']."', '".$_POST['problema']."', '1')";    
    $query_conferma = $mysqli->query("INSERT INTO `" . $config['dbname'] . "`.`segnalazione` ( `descrizione`, `id_componente_incriminato`, `data`, `ora`, `id_insegnante`,  `id_problema`, `id_stato_segnalazione`) VALUES ( '" . $descrizione . "', '" . $componente_incriminato . "', '" . date("Y-m-d") . "', '" . date("H:i:s") . "', '" . $_SESSION['id_utente'] . "',  '" . $_POST['problema'] . "', '1')");

    $messaggio = "AVVISO: Inserimento del nuovo ticket avvenuto con successo";
    echo "<script language='javascript'>"
    . "alert('$messaggio');"
    . "window.location.href = './insegnanti.php';"
    . "</script>";
}
?>

<!DOCTYPE html>
<!--<html>
    <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS 
<link rel="stylesheet" href="./css/bootstrap.min.css">


<link rel="stylesheet" href="./css/mycss.css">


<title>Nuovo ticket</title>


</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <form method="post">
                <div class="form-group">
                    <label for="descrizione">Dettagli segnalazione</label>
                    <textarea name="descrizione" class="form-control" id="descrizione" rows="4"></textarea>
                </div>
                <div class="form-inline">
                    <select name="laboratorio" onchange="getId(this.value)">
                        <option value="0">Seleziona laboratorio</option>
<?php //echo $laboratori  ?>
                    </select>

                    <h6>&emsp;&emsp;</h6>
                    <select name="componenti" id="componenti" >
                        <option value="0">Seleziona componente</option>
<?php //echo $componenti  ?>
                    </select>
                </div>
                <div class="form-inline">
                    <select name="problema" >
<?php //echo $problema  ?>
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
    function getId(val){
        $.ajax({
           type: "POST",
           url: "getdata.php",
           data: "id_laboratorio="+val,
           success: function (data){
               $("#componenti").html(data);
           }
        });
    }
</script>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS 
<script src="./jquery/jquery-3.3.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
</body>
</html>-->


<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Crea ticket</title>

        <!-- CORE UI -->
        <link rel="stylesheet" href="./coreui/coreui.min.css">
        <link rel="stylesheet" href="https://unpkg.com/@coreui/icons/css/coreui-icons.min.css">
        <link rel="stylesheet" href="./css/simple-line-icons.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!--<link rel="stylesheet" href="./coreui/coreui.min.css">
                <link rel="stylesheet" href="./coreui/coreui-icons.min.css">
                <link rel="stylesheet" href="./css/simple-line-icons.css">
                <link rel="stylesheet" href="./css/font-awesome.min.css">-->
        <style type="text/css">
            textarea {
                resize: none;
            }
            body {
                background: #f9f9f9;

            }
            select {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
            }
            .select-wrapper {
                position: relative;
            }
            .fa-caret-down {
                position: absolute;
                top: 12px;
                right: 15px;
                opacity: 0.5;
                pointer-events: none;
            }
            .card{
                border-radius: 10px;
                border-color: #c4c4c4;
                box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);

            }
            .btn-primary{
                border-radius: 20px;
                background: #1f4362;
                border: #1f4362;
            }
        </style>
    </head>

    <!-- Alternate style for background: background-color: #eeeff7; -->
    <body class="app header-fixed">
        <header class="app-header navbar">
            <a class="navbar-brand mr-auto" href="#">
                <img class="navbar-brand-full" src="assets/img/logo.gif" height="25" alt="logo">
                <img class="navbar-brand-minimized" src="assets/img/logo.gif" width="30" height="30" alt="logo">
            </a>

            <ul class="nav navbar-nav d-md-down-none">
                <li class="nav-item px-3">
                    <a class="nav-link" href="#"><i class="cui-user mr-2"></i>Mario Rossi</a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link" href="#"><i class="cui-account-logout mr-2"></i>Esci</a>
                </li>
            </ul>
        </header>

        <div class="app-body">
            <!-- MAIN CONTENT -->
            <main class="main">
                <div class="container pt-5">
                    <div class="row mb-5">
                        <div class="col-md-6 mx-auto">
                            <div class="card mb-0">
                                <form method="post">
                                    <div class="card-body">
                                        <h3 class="card-title text-center">Crea un nuovo ticket</h3>
                                        <br>
                                        <div class="form-group">
                                            <label for="descrizione">Descrizione:</label>
                                            <textarea id="descrizione" name="descrizione" rows="4" class="form-control" placeholder="Descrivi brevemente la segnalazione..."></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="">Laboratorio</label>
                                                <div class="select-wrapper">
                                                    <select name="laboratorio" class="form-control" onchange="getId(this.value)">
                                                        <option value="0">Seleziona laboratorio</option>
                                                        <?php echo $laboratori ?>
                                                    </select>
                                                    <i class="fa fa-caret-down"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="">Componente</label>
                                                <div class="select-wrapper">
                                                    <select name="componenti" id="componenti" class="form-control" >
                                                        <option value="0">Seleziona componente</option>
                                                        <?php echo $componenti ?>
                                                    </select>
                                                    <i class="fa fa-caret-down"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="">Tipo problema</label>
                                            <div class="select-wrapper">
                                                <select name="problema" class="form-control">
                                                    <?php echo $problema ?>
                                                </select>
                                                <i class="fa fa-caret-down"></i>
                                            </div>
                                        </div>

                                        <div class="form-group text-center mb-0">
                                            <button class="btn btn-block btn-primary" id="button" name="conferma" value="conferma">Crea ticket</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>

        <!-- FOOTER -->
        <footer class="app-footer">
            <div>
                <span>&copy; 2019 I.I.S. Levi-Ponti</span>
            </div>
        </footer>

        <!-- CORE SCRIPTS -->
        <script src="./jquery/jquery-3.3.1.js"></script>
        <script src="./popper/popper.min.js"></script>
        <script src="./css/bootstrap.min.css"></script>
        <script src="./coreui/coreui.min.css"></script>
        <script type="text/javascript">
                                                    $(".collapse").collapse({toggle: true});
        </script>

        <script>
            function getId(val) {
                $.ajax({
                    type: "POST",
                    url: "getdata.php",
                    data: "id_laboratorio=" + val,
                    success: function (data) {
                        $("#componenti").html(data);
                    }
                });
            }
        </script>
    </body>
</html>