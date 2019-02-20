<?php
session_start();
$errorUser = $errpssw = $nickname = $pssw = $connessione = $dbType = $config = "";


$disabledRecupero = "disabled";
//var_dump($_SESSION);

if (!empty($_SESSION['login'])) {
    if ($_SESSION['login'] == true) {
        if ($_SESSION['id_tipo_utente'] == 1) {
            header("location:admin_page.php");
        } else if ($_SESSION['id_tipo_utente'] == 2) {
            header("location:insegnanti.php");
        } else if ($_SESSION['id_tipo_utente'] == 3) {
            header("location:tecnici.php");
        }
    }
}
session_abort();


if (!empty($_GET)) {
    $nickname = $_GET['nickname'];
}


if (!empty($_POST['accedi'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (empty($_POST['pssw'])) {
            $errpssw = "Inserire password";
            $nickname = $_POST['nickname'];
        } else if (!empty($_POST['nickname']) && !empty($_POST['pssw'])) {

            $config = parse_ini_file('./config.ini');
            // Connessione al database
            $dbType = $config['dbType'];

            $mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['dbname']);
            if ($mysqli->connect_error) {
                die('Errore di connessione (' . $mysqli->connect_errno . ')'
                        . $mysqli->connect_error);
            }


            $nickname = $_POST['nickname'];
            $password = $_POST['pssw'];
            $query = $mysqli->prepare("SELECT id_utente, username, password, id_tipo_utente, cognome FROM " . $config['tableName'] . " WHERE username = ? AND password = ?");

            $query->bind_param("ss", $nickname, $password);

            if (!($query->execute())) {
                die($mysqli->error);
            } else {
                $query->bind_result($id_utente, $username, $password, $id_tipo_utente, $cognome);
                $query->fetch();

                session_start();

                $_SESSION['username'] = $username;
                $_SESSION['cognome'] = $cognome;
                $_SESSION['id_utente'] = $id_utente;
                $_SESSION['id_tipo_utente'] = $id_tipo_utente;
                $_SESSION['login'] = true;

                if ($id_tipo_utente == 1) {
                    header("location:admin_page.php");
                } else if ($id_tipo_utente == 2) {
                    header("location:insegnanti.php");
                } else if ($id_tipo_utente == 3) {
                    header("location:tecnici.php");
                } else {
                    $errpssw = "Password o user name errati";
                    $disabledRecupero = "";
                    $_SESSION['login'] = false;
                    session_abort();
                }
            }
        } else {
            $nickname = $_POST['nickname'];
            $errpssw = "Inserisci la password";
        }
    }
} else if (!empty($_POST['recupero'])) {
    header("location:./Login/recupero.php?nickname=$_POST[nickname]& dbType=$_POST[connessione]");
}
?>
<!DOCTYPE html>
<!--<html>
    <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        
        <link rel="stylesheet" href="./css/bootstrap.min.css">


        <link rel="stylesheet" href="./css/mycss.css">


        <title>Segnalazione ticket</title>


    </head>





    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <form method="post">
                        <div class="form-group">
                            <label for="user">User name</label>
                            <input type="text" name="nickname" required class="form-control" id="nickname"  placeholder="Inserisci user name" value= <?php //$nickname    ?>> <span class=error id="erroreUser"><?php //echo $errorUser    ?></span>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Password" name="pssw"  value= <?php //echo $pssw    ?> > <span class=error id="errorePassword"> <?php //echo $errpssw    ?> </span>
                        </div>
                        <div class="form-inline">
                            <button type="submit" name="accedi" value="Accedi" class="btn btn-primary">Accedi</button>
                            <h6>&emsp;&emsp;</h6>
                            <button type="submit" name="recupero" value="Recupera password" class="btn btn-primary" <?php //echo $disabledRecupero;    ?>>Recupera Password</button>
                        </div>
                        <br>
                    </form>
                </div>
            </div>                
        </div>



<!--Optional JavaScript 
jQuery first, then Popper.js, then Bootstrap JS 
<script src="./jquery/jquery-3.3.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
</body>
</html>-->


<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Accedi</title>
        <!-- CSS -->
        <link rel="stylesheet" href="./css/bootstrap.min.css" >
        <link rel="stylesheet" href="./css/style_register.css">
        <link rel="stylesheet" href="./css/all.css" >
        <!-- CSS -->
    </head>
    <!-- This snippet uses Font Awesome 5 Free as a dependency. You can download it at fontawesome.io! -->

    <body>
        <div class="container-fluid" align="center">
            <div class="row">
                <div class="col-lg-10 col-xl-9 mx-auto">
                    <div class="card card-signin flex-row my-5">
                        <div class="card-body">

                            <img src="./img/lateral3.gif" style="max-width:50%;">
                            <h6 class="card-title text-center">Accedi</h6>
                            <form method="POST">

                                <div class="form-label-group">
                                    <input type="text" id="nickname" name="nickname" class="form-control" placeholder="Username" required autofocus <?php echo $nickname; ?>> <span class=error id="erroreUser"><?php echo $errorUser; ?></span>
                                    <label for="nickname">Username</label>
                                </div>
                                <div class="form-label-group">
                                    <input type="password" id="inputPassword" class="form-control" placeholder="Password" required name="pssw" value= <?php echo $pssw; ?> > <span class=error id="errorePassword"> <?php echo $errpssw; ?> </span>
                                    <label for="inputPassword">Password</label>
                                </div>
<!--                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                    <label class="custom-control-label" for="customCheck1">Ricorda password</label>
                                </div>-->

                                <button class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit" name="accedi" value="accedi">Accedi</button>
                                <div class="text-center">
                                    <hr>
                                    
                                    <a class="small" href="./Login/recupero.php">Hai dimenticato la password?</a></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </body>
</html>