<?php
$nickname = $nome = $cognome = $data_nascita = $luogo_nascita = $email = $password = $conferma = "";

$nickname = $_GET['nickname'];
$connessione = $_GET['dbType'];

if (!empty($_POST)) {
// Connessione al database


    $config = parse_ini_file('./config.ini');


    $nickname = $_POST['nickname'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $data_nascita = $_POST['data_nascita'];
    $luogo_nascita = $_POST['luogo_nascita'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($connessione == "mysql") {

        $mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['dbname']);
        if ($mysqli->connect_error) {
            die('Errore di connessione (' . $mysqli->connect_errno . ') '
                    . $mysqli->connect_error);
        }



//        $query = "INSERT INTO ".$config['tableName']."  (`user`, `nome`, `cognome`, `data_nascita`,"
//                . " `luogo_nascita`, `email`, `password`) VALUES ('$nickname', '$nome', '$cognome', "
//                . "'$data_nascita', '$luogo_nascita', '$email', '$password');";
//// Esecuzione della query e controllo degli eventuali errori
//        if (!$mysqli->query($query)) {
//            die($mysqli->error);
//        } else {
//            header("location:index.php?nickname=$_POST[nickname]&dbType=$connessione");
//        }
        
        
        
        
        
        
//        $query = "INSERT INTO ".$config['tableName']."  (`user`, `nome`, `cognome`, `data_nascita`,"
//                . " `luogo_nascita`, `email`, `password`) VALUES (?,?,?,?,?,?,?);";
// Esecuzione della query e controllo degli eventuali errori
        
        $query= $mysqli->prepare("INSERT INTO ".$config['tableName']."  (`user`, `nome`, `cognome`, `data_nascita`,"
                . " `luogo_nascita`, `email`, `password`) VALUES (?,?,?,?,?,?,?);");
        
        
        $query->bind_param("sssssss", $nickname, $nome, $cognome, $data_nascita, $luogo_nascita, $email, $password);
        
        
        if (!$query->execute()) {
            die($mysqli->error);
        } else {
            header("location:index.php?nickname=$_POST[nickname]&dbType=$connessione");
        }
    } else if ($connessione == "pdo") {


        $dbType = $config['dbType'];
        try {
            
            // stringa di connessione al DBMS

            $pdo = new PDO($config['dbType'] . "host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            
            $query = "INSERT INTO ".$config['tableName']." (`user`, `nome`, `cognome`, `data_nascita`,"
                    . " `luogo_nascita`, `email`, `password`) VALUES ('$nickname', '$nome', '$cognome', "
                    . "'$data_nascita', '$luogo_nascita', '$email', '$password'');";

            
            $pdo->exec($query);
            
            
            header("location:index.php?nickname=$_POST[nickname]&dbType=$connessione");

            // chiusura della connessione
            
            $connessione = null;
            
        } catch (PDOException $e) {
            // notifica in caso di errore nel tentativo di connessione
            echo $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Registrazione</title>


        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="./css/bootstrap.min.css">


        <link rel="stylesheet" href="./css/mycss.css">
    </head>
    <body>

        <div class="container">
            <form method="post" class="needs-validation"  novalidate>
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="validationCustomUsername">Nickname</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                            </div>
                            <input type="text" name="nickname" class="form-control" id="nickname" placeholder="Nickname"   required value=<?php echo $nickname ?> >
                            <div class="invalid-feedback">
                                Scegli un username
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom01">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required placeholder="Nome" value=<?php echo $nome ?> >
                        <div class="invalid-feedback">
                            Inserisci un nome
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom02">Cognome</label>
                        <input type="text" class="form-control" id="cognome" name="cognome" required placeholder="Cognome" value=<?php echo $cognome ?> >
                        <div class="invalid-feedback">
                            Inserisci un cognome
                        </div>
                    </div>

                </div>
                <div class="form-row">




                    <div class="col-md-6 mb-3">
                        <label for="validationCustom03">Data di nascita</label>
                        <input type="text" name="data_nascita" class="form-control" id="datat_nascita" placeholder="Data di nascita" required  value=<?php echo $data_nascita ?>>
                        <div class="invalid-feedback">
                            Inserisci una data
                        </div>
                    </div>





                    <div class="col-md-3 mb-3">
                        <label for="validationCustom04">Luogo di nascita</label>
                        <input type="text" class="form-control" id="luogo_nascita" name="luogo_nascita" placeholder="Luogo di nascita" required value=<?php echo $luogo_nascita ?>>
                        <div class="invalid-feedback">
                            Inserisci un luogo di nascita
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="validationCustom05">Password</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required value=<?php echo $email ?>>
                        <div class="invalid-feedback">
                            Inserisci una email
                        </div>
                    </div>


                </div>
                <div class="form-row">

                    <div class="col-md-3 mb-3">
                        <label for="validationCustom05">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required  value=<?php echo $password ?>>
                        <div class="invalid-feedback">
                            Inserisci una Password
                        </div>
                    </div>

                </div>


                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                        <label class="form-check-label" for="invalidCheck">
                            Accetta termini e condizioni
                        </label>
                        <div class="invalid-feedback">
                            Devi accettare i termini e le condizioni per registrarti
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" name="registrati" type="submit">Registrati</button>
            </form>

            <h6 id="conferma"><?php echo $conferma ?></h6>
        </div>



       
        <script>
            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (function () {
                'use strict';
                window.addEventListener('load', function () {
                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    var forms = document.getElementsByClassName('needs-validation');
                    // Loop over them and prevent submission
                    var validation = Array.prototype.filter.call(forms, function (form) {
                        form.addEventListener('submit', function (event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        </script>








        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="./jquery/jquery-3.3.1.js"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>-->
        <script src="./js/bootstrap.min.js"></script>
    </body>
</html>
