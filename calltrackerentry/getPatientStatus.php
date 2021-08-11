<?php 
    require("../include/database.php");
    include("../include/funciones.php");

    if(isset($_POST['idPaciente'])){
        global $conexion;
	    $query = $conexion->query('SELECT NOW()');
	    $fecha = $query->fetch_assoc()["NOW()"];
	    $fecha=date("Y-m-d",strtotime("+2 day",strtotime($fecha)));
	    
	        $fecha=date("Y-m-d",strtotime("+1 day",strtotime($fecha)));
	        $consulta='SELECT * FROM citas AS c INNER JOIN citas_estado AS ce ON ce.id_cita=c.id_cita WHERE c.id_paciente = "'.$_POST['idPaciente'].'" AND ce.estado_cita="No Show Up"';
	        $query = $conexion->query($consulta);
	        
	    $cantidad=$query->num_rows;
	    
	    echo json_encode(array("num"=>$cantidad));
	        
	}

?>