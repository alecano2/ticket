<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include('sendmail.php');
$password = $errore = $messaggio = $code = "";
if (!empty($_GET['nickname']))
    $nickname = $_GET['nickname'];




if (!empty($_POST['recupera'])) {
    $nickname = $_POST['nickname'];

    $config = parse_ini_file('../config.ini');


    $mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['dbname']);
    if ($mysqli->connect_error) {
        die('Errore di connessione (' . $mysqli->connect_errno . ') '
                . $mysqli->connect_error);
    }

    $code = (string) mt_rand(1000000000, mt_getrandmax());


    $createCode = $mysqli->query("UPDATE " . $config['tableName'] . " SET `code`= '" . $code . "' WHERE  `username`='" . $nickname . "'");





    $queryRicerca = $mysqli->query("SELECT * FROM " . $config['tableName'] . " WHERE username = "
            . "'$nickname'");
    if ($queryRicerca->num_rows) {

        while ($row = $queryRicerca->fetch_assoc()) {
            $emailaddress = $row['email'];
        }

        sendmail($emailaddress, $nickname, $code);


        header("location:recovery_password.php?nickname=$_POST[nickname]& dbType=$connessione");
    } else {
        $errore = "L'username non esiste";
    }
} else if (!empty($_POST['home'])) {
    header("location:../index.php?nickname=$_POST[nickname]");
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
                            <label for="user">Inserisci l'username per recuperare la password</label>
                            <input type="text" name="nickname" required class="form-control" id="username"  placeholder="Inserisci user name" value= <?= $nickname ?>> <span class=error id="errore"> <?php echo $errore ?> </span>
                            <br>
                            <div class="form-inline">
                                <button type="submit" name="recupera" value="Recupera" class="btn btn-primary">Recupera</button>
                                <h6>&emsp;</h6>
                                <button type="submit" name="home" value="home" class="btn btn-primary">HOME</button>

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
