<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include('sendmail.php');
$inputcode = $nickname = $errore = $messaggio = "";
if (!empty($_GET['nickname']))
    $nickname = $_GET['nickname'];

if (!empty($_POST['verifica'])) {

    $config = parse_ini_file('../config.ini');

    $mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['dbname']);
    if ($mysqli->connect_error) {
        die('Errore di connessione (' . $mysqli->connect_errno . ') '
                . $mysqli->connect_error);
    }


    $inputcode = $_POST['inputcode'];

    echo $inputcode;

    $queryRicerca = $mysqli->query("SELECT * FROM " . $config['tableName'] . " WHERE username = "
            . "'$nickname'");


    if ($queryRicerca->num_rows) {

        while ($row = $queryRicerca->fetch_assoc()) {
//                session_start();
//                $_SESSION['email_address']= "alecanova00@icloud.com";
//                $_SESSION['nickname']=$nickname;

            $code = $row['code'];
        }

        echo $code;

        if ($code == $inputcode) {
            $messaggio = "Codice corretto. Tra 5 secondi verrai reinderizzato nella pagina per cambiare la tua password";
            sleep(5);
            header("location:change_password.php?nickname=$nickname");
        } else {
            $errore = "Codice errato";
        }
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../css/bootstrap.min.css">


        <link rel="stylesheet" href="../css/mycss.css">


        <title>Recupero Password</title>
    </head>
    <body>


        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <form method="post">
                        <div class="form-group">
                            <label for="user">Inserisci il codice ricevuto via email</label>
                            <input type="text" name="inputcode" required class="form-control" id="inputcode"  placeholder="Inserisci il codce ricevuto" value= <?= $inputcode ?>> <span class=error id="errore"> <?php echo $errore ?> </span>
                            <br>
                            <div class="form-inline">
                                <button type="submit" name="verifica" value="Verifica" class="btn btn-primary">Verifica</button>
                                <h6>&emsp;</h6>

                            </div>
                            <br>

                        </div>
                        <?php echo $messaggio ?>
                    </form>
                </div>
            </div>
        </div>



        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="../jquery/jquery-3.3.1.js"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>-->
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>
