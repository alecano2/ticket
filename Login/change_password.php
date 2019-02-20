<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
$password = $nickname = $errpssw = $conferma_password = $messaggio = "";

if (!empty($_GET['nickname'])) {
    $nickname = $_GET['nickname'];
}
if (!empty($_GET['dbType']))
    $connessione = $_GET['dbType'];


if (!empty($_POST['conferma'])) {


    $config = parse_ini_file('../config.ini');
        
        
        

        $mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['dbname']);
        if ($mysqli->connect_error) {
            die('Errore di connessione (' . $mysqli->connect_errno . ') '
                    . $mysqli->connect_error);
        }


        $password = $_POST['password'];
        $conferma_password = $_POST['conferma_password'];

        if ($password == $conferma_password) {


            $queryRicerca = ("UPDATE " . $config['tableName'] . " SET `password`= '" . $password . "' WHERE  `username`='" . $nickname . "'");

            echo $queryRicerca;

            if (!$mysqli->query($queryRicerca)) {
                die($mysqli->error);
            } else {
                $messaggio = "Password cambiata con successo. Tra 5 secondi verrai reindirizzato alla pagina di login";
                sleep(5);
                header("location:../index.php?nickname=$nickname");
            }
        } else {
            $errpssw = "Password errata";
            $password = "";
            $conferma_password = "";
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


        <title>Login PHP</title>


    </head>





    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <form method="post">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" required class="form-control" id="password"  placeholder="Inserisci una nuova password" value= <?= $password ?>> <!--<span class=error id="erroreUser"><?php echo $errorUser ?>--></span>
                        </div>
                        <div class="form-group">
                            <label for="conferma_password">Conferma password</label>
                            <input type="password" class="form-control" id="conferma_password" placeholder="Conferma password" name="conferma_password"  value= <?php echo $conferma_password ?> > <span class=error id="errorePassword"> <?php echo $errpssw ?> </span>
                        </div>
                        <div class="form-inline">
                            <button type="submit" name="conferma" value="Conferma" class="btn btn-primary">Conferma</button>
                        </div>
                    </form>
                    <?php echo $messaggio ?>
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

