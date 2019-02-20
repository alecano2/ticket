<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
session_start();


if($_SESSION['login']==FALSE){
    header("location:./index.php");
}

if($_SESSION['id_utente']!=2){
    switch ($_SESSION['id_tipo_utente']){
    case 2:
        header("location:./insegnanti.php");
        break;
    case 3:
        header("location:./tecnici.php");
        break;
    }
}

if (!empty($_POST['laboratori'])) {
    header("location:./admin/laboratori/gestione_laboratori.php");
} else if(!empty($_POST['componenti'])){
    header("location:./admin/componenti/gestione_componenti.php");
}else if(!empty($_POST['utenti'])){
    header("location:./admin/utenti/gestione_utenti.php");
}else if(!empty($_POST['segnalazioni'])){
    header("location:./admin/segnalazioni/gestione_segnalazioni.php");
} else if (!empty($_POST['esci'])) {
    $_SESSION['login'] = false;
    header("location:./index.php");
}
?>
<html>
    <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <title>Dashboard Amministratore</title>
    </head>
    <body>
        <?php
        echo 'Benvenuto Amministratore';
        ?>

        <div class="container">
            <div class="row">
                <form method="post">
                    
                    
                    
                    <button type="submit" name="esci" value="esci" class="btn btn-primary">esci</button>
                    <button type="submit" name="laboratori" value="laboratori" class="btn btn-primary">laboratori</button>
                    <button type="submit" name="componenti" value="componenti" class="btn btn-primary">componenti</button>
                    <button type="submit" name="utenti" value="utenti" class="btn btn-primary">utenti</button>
                    <button type="submit" name="segnalazioni" value="segnalazioni" class="btn btn-primary">segnalazioni</button>
                    
                    
                </form>
            </div>
        </div>


        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="./jquery/jquery-3.3.1.js"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>-->
        <script src="./js/bootstrap.min.js"></script>
        <script src="./dataTables/jquery.dataTables.min.js"></script>
    </body>
</html>