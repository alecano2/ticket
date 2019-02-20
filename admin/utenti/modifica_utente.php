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
$id_utente = $nuovo_id_utente = $tipo_utente = $username = $nome = $cognome = $sesso = $email = $password = $telefono = $id_utente = $sessoM = $sessoF = "";
if (!empty($_GET['id_utente'])) {
    $id_utente = $_GET['id_utente'];
}


$config = parse_ini_file('../../config.ini');

try {
    $connessione = new PDO($config['dbType'] . "host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
    $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$query_utente = "select u.id_utente, u.id_tipo_utente, u.username, u.nome, u.cognome, u.sesso, u.email, u.telefono, u.password
from utente u join tipo_utente tu on(u.id_tipo_utente=tu.id_tipo_utente) where id_utente=" . $id_utente;


$result = $connessione->query($query_utente);

foreach ($result as $row) {
    $id_utente = $row['id_utente'];

    //tipo utente

    $query_tipo_utente = "select * from tipo_utente where id_tipo_utente=" . $row['id_tipo_utente'];

    $result1 = $connessione->query($query_tipo_utente);

    foreach ($result1 as $row1) {
        $tipo_utente = $tipo_utente . '<option value="' . $row['id_tipo_utente'] . '">' . $row1['descrizione'] . '</option>';
    }
    $query_tipo_utente_esclusivo = "select * from tipo_utente where id_tipo_utente!=" . $row['id_tipo_utente'];

    $result1 = $connessione->query($query_tipo_utente_esclusivo);

    foreach ($result1 as $row1) {
        $tipo_utente = $tipo_utente . '<option value="' . $row['id_tipo_utente'] . '">' . $row1['descrizione'] . '</option>';
    }

    //fine tipo utente


    $username = $row['username'];
    $nome = $row['nome'];
    $cognome = $row['cognome'];

    //sesso
    if ($row['sesso'] == 'M') {
        $sessoM = "selected";
        $sessoF = "";
    } else if ($row['sesso'] == 'F') {
        $sessoM = "";
        $sessoF = "selected";
    }
    //fine sesso


    $email = $row['email'];
    $telefono = $row['telefono'];
    $password = $row['password'];
}
if (!empty($_POST['conferma'])) {
    $nuovo_id_utente = $_POST['id_utente'];
    $tipo_utente = $_POST['tipo_utente'];
    $username = $_POST['username'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $sesso = $_POST['sesso'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $password = $_POST['password'];
    
 

    $query_modifica = "UPDATE `" . $config['dbname'] . "`.`utente` SET "
            . "`id_utente`='" . $nuovo_id_utente . "', `id_tipo_utente`='" . $tipo_utente . "', "
            . "`username`='" . $username . "', `nome`='" . $nome . "', `cognome`='" . $cognome . "', "
            . "`sesso`='" . $sesso . "', `email`='" . $email . "', `telefono`='" . $telefono . "', "
            . "`password`='" . $password . "'"
            . " WHERE  `id_utente`=" . $id_utente;


    $result = $connessione->query($query_modifica);

    if($result){
        echo "<script> alert('Modifica avvenuta con successo');window.location.href = './gestione_utenti.php';</script>";
    }
    
    
}
?>
<html>
    <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <title>Modifica utente</title>
    </head>
    <body>


        <div class="container">
            <form method="post" class="needs-validation"  novalidate>
                <div class="form-row">


                    <div class="col-md-2 mb-1">
                        <label for="validationCustomUsername">id </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                            </div>
                            <input type="text" name="id_utente" class="form-control" id="id_utente" placeholder="ID Utente"   required value=<?php echo $id_utente ?> >
                            <div class="invalid-feedback">
                                Inserisci l'id del nuovo utente
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="validationCustom02">Tipo utente</label>

                        <div class="form-inline">
                            <select name="tipo_utente" >
<?php echo $tipo_utente; ?>
                            </select>
                        </div>

                        <div class="invalid-feedback">
                            Inserisci il tipo di utente
                        </div>
                    </div>


                    <div class="col-md-4 mb-3">
                        <label for="validationCustomUsername">Username</label>
                        <div class="input-group">
                            <input type="text" name="username" class="form-control" id="username" placeholder="Username"   required value=<?php echo $username ?> >
                            <div class="invalid-feedback">
                                Scegli un username
                            </div>
                        </div>
                    </div>





                </div>
                <div class="form-row">

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



                    <div class="col-md-4 mb-3">
                        <label for="validationCustom02">Sesso</label>

                        <div class="form-inline">
                            <select name="sesso" >
                                <option value="1" <?php echo $sessoM; ?>>M</option>
                                <option value="2" <?php echo $sessoF; ?>>F</option>
                            </select>
                        </div>

                        <div class="invalid-feedback">
                            Inserisci il sesso
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="validationCustom02">Telefono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" required placeholder="Numero di telefono" value=<?php echo $telefono ?> >
                        <div class="invalid-feedback">
                            Inserisci un numero di telefono
                        </div>
                    </div>


                    <div class="col-md-3 mb-3">
                        <label for="validationCustom05">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required value=<?php echo $email ?>>
                        <div class="invalid-feedback">
                            Inserisci una email
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="validationCustom05">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required  value=<?php echo $password ?>>
                        <div class="invalid-feedback">
                            Inserisci una Password
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary" name="conferma"  value="conferma" type="submit">Conferma</button>
            </form>
        </div>


        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="../../jquery/jquery-3.3.1.js"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>-->
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../dataTables/jquery.dataTables.min.js"></script>
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
    </body>
</html>