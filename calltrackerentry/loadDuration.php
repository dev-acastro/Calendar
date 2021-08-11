<?php 
    require("../include/database.php");
    include("../include/funciones.php");

    if(isset($_POST["reason"])){
        $search = $_POST['reason'];
        $result = $conexion->query('SELECT reason_duracion FROM '.$tabla_citas_reason.' WHERE reason_nombre = "'.$search.'";');

        while ($row = $result->fetch_assoc()) 
        {
            echo $row['reason_duracion'];
        }
    }
?>