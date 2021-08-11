<?php
    require("../include/database.php");
    include("../include/funciones.php");

    $channel = escapar($_POST["id_channel"]);

    $result = $conexion->query('SELECT * FROM '. $tabla_call_referal.' WHERE id_channel = "'.$channel.'" ORDER BY referal_name');
    while($referal=$result->fetch_assoc())
    {
        echo '<option value="' .$referal["id_referal"]. '">' .$referal["referal_name"]. '</option>';
    }
?>