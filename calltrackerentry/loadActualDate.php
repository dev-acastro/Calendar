<?php 
    require("../include/database.php");
    include("../include/funciones.php");

        $result = $conexion->query('SELECT CURDATE() as date;');
        while ($row=$result->fetch_assoc()) 
        {
            echo $row['date'];
        }
?>