<?php 

    //Buscar Paciente Registrado
    //include("../include/eagle_con.php");
    //require("../include/database.php");
    include("../include/funciones.php");

    /*$conexion->query('CREATE TEMPORARY TABLE `patients` (
        `patient_id` varchar(10) NOT NULL,
        `first_name` varchar(10) NOT NULL,
        `last_name` varchar(150) NOT NULL,
        `home_phone` varchar(150) NOT NULL,
        `birth_date` date NOT NULL
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;');

    #REGISTROS DESDE HACE DOS YEAR
    $query = "SELECT patient_id,first_name,last_name,home_phone,birth_date FROM patient WHERE date_entered BETWEEN dateadd(YEAR,-8,NOW()) AND NOW() ORDER BY patient_id DESC;";
    $patient_to_insert = array();
    $result = odbc_exec($conexion_anywhere,$query);

    while (odbc_fetch_row($result)) 
    {
        $patient_id = odbc_result($result,"patient_id");
        $first_name = odbc_result($result,"first_name");
        $last_name = odbc_result($result,"last_name");
        $home_phone = odbc_result($result,"home_phone");
        $birth_date = odbc_result($result,"birth_date");
        if (strpos($patient_id, 'M') === FALSE && strpos($patient_id, 'F') === FALSE && strpos($patient_id, 'W') === FALSE) {
            $id_pat_to_insert = trim('M'.$patient_id);
        }else{
            $id_pat_to_insert = trim($patient_id);
        }

        $parentesis = '("'.$id_pat_to_insert.'", "'.$first_name.'", "'.$last_name.'", "'.$home_phone.'", "'.$birth_date.'")';

        array_push($patient_to_insert,$parentesis);
    }

    $array_string = implode(",",$patient_to_insert);

    $conexion->query('INSERT INTO '.$tabla_patients.' (patient_id, first_name, last_name, home_phone, birth_date) VALUES '.$array_string.';');

    */
    global $conexion;
    $search = $_GET['term'];
    $dob = date('Y-m-d',strtotime($search));
    $result = $conexion->query('SELECT * FROM '.$tabla_pacientes.' WHERE paciente_fechanac = "'.$dob.'" LIMIT 15;');
    $json = array();

    #MySQL
    while ($row = $result->fetch_assoc()) 
    {
        $pat_id = $row['id_paciente'];
        $name = $row['paciente_nombres'];
        $last = $row['paciente_apellidos'];
        $birth = date("m/d/Y",strtotime($row['paciente_fechanac']));
        $contact = $row['paciente_contacto'];

        if (strpos($pat_id, 'F') !== false) {
            $id_paciente = str_replace('F','FX',$pat_id);
            $clinica = 1;
        }
        else if (strpos($pat_id, 'W') !== false) {
            $id_paciente = str_replace('W','WE',$pat_id);
            $clinica = 3;
        }
        else if (strpos($pat_id, 'M') !== false){
            $id_paciente = str_replace('M','MS',$pat_id);
            $clinica = 2;
        }

        $json[]=array(
            'value' => $id_paciente,
            'label' => $id_paciente .': '.$name.', '.$last.' - '.$contact,
            'id' => $id_paciente,
            'name' => $name,
            'last' => $last,
            'phone' => $contact,
            'dob' => $birth
        );
    }

    $jsonstring = json_encode($json);
    echo $jsonstring;
?>