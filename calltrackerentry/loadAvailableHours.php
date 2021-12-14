<?php 
    require("../include/database.php");
    include("../include/funciones.php");

    $clinica = $_POST['id_clinica'];
    $selected_date = date('Y-m-d',strtotime($_POST['date']));

    $operatoriesQuery = $conexion->query('SELECT shortName, id_clinica FROM operatories');
    $operatories = $operatoriesQuery->fetch_all(MYSQLI_ASSOC);
    $clinicOperatories = [];
    foreach ($operatories as $operatory) {
        $clinicOperatories[$operatory->id_clinica] = $operatory["shortName"];
    }

    

    #MySQL
    $result = $conexion->query('SELECT bh_start,bh_end FROM '.$tabla_calendario_horas.' WHERE id_clinica = '.$clinica.' AND bh_date = "'.$selected_date.'";');
    $json = array();

    function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);
    
        while( $current <= $last ) {
    
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
    
        return $dates;
    }
    
    $select_hour = array();

    while($row=$result->fetch_assoc())
    {
        $hora_inicio = $row['bh_start'];
        $hora_final = $row['bh_end'];
        $horas_habiles = date_range($hora_inicio, $hora_final, "+60 min", "H:i:s");

        if($clinica == 1){
            $limite = 3;
        }else{
            $limite = 4;
        }

        foreach($horas_habiles as $hour){
             $disponible = $conexion->query('SELECT citas_seat as operatory from '.$tabla_citas.' WHERE cita_fecha = "'.$selected_date.'" AND cita_hora = "'.$hour.'" AND id_clinica = '.$clinica.' ;');
             $citasOperatory = $disponible->fetch_all(MYSQLI_ASSOC);
             $operatoriesAvailable = array_diff($clinicOperatories[$clinica], $citasOperatory);
            if(empty($disponible['total'])){
                $cantidad = 0;
            }else{
                $cantidad = $disponible['total'];
            }
            if($cantidad < $limite){
                array_push($select_hour,$hour);

            } 

            $json[]=array(
                'time' => date('g:i a',strtotime($hour))
            );
        }

        /* foreach($horas_habiles as $hour){
            $disponible = queryOne('SELECT count(id_cita) as total from '.$tabla_citas.' WHERE cita_fecha = "'.$selected_date.'" AND cita_hora = "'.$hour.'" AND id_clinica = '.$clinica.' group by cita_fecha;');
           if(empty($disponible['total'])){
               $cantidad = 0;
           }else{
               $cantidad = $disponible['total'];
           }
           if($cantidad < $limite){
               array_push($select_hour,$hour);

           } 

           $json[]=array(
               'time' => date('g:i a',strtotime($hour))
           );
       } */

         foreach($select_hour as $h){
            $cupo = queryOne('SELECT count(citas.id_cita) as total from citas INNER JOIN citas_estado ON citas.id_cita = citas_estado.id_cita WHERE estado_cita!="Canceled" AND cita_fecha = "'.$selected_date.'" AND cita_hora = "'.$h.'" AND id_clinica = '.$clinica.' group by cita_fecha;');

            if(empty($cupo['total'])){
                $cantidad = 0;
            }else{
                $cantidad = $cupo['total'];
            }

            $available = $limite - $cantidad;

            $json[]=array(
                'time' => date('g:i a',strtotime($h)),
                'available' => $available
            );
        } 
    }

    $jsonstring = json_encode($json);
    echo $jsonstring;

?>