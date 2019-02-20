<?php

    $config = parse_ini_file('./config.ini');
    // Connessione al database
    $dbType = $config['dbType'];

    $mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['dbname']);
    if ($mysqli->connect_error) {
        die('Errore di connessione (' . $mysqli->connect_errno . ')'
                . $mysqli->connect_error);
    }
    
    if(!empty($_POST['id_laboratorio'])){
        $id_laboratorio = $_POST['id_laboratorio'];
        $query = $mysqli->query("select * from componente where id_laboratorio =" . $id_laboratorio);
        $componente_disabilitato="";
        foreach ($query as $row){
         ?>
<option value="<?php echo $row['n_inventario']; ?>"><?php echo $row['n_inventario'];?></option>
<?php
        }
    }
?>
