<?php 
    require("../include/database.php");
    include("../include/funciones.php");

    $search = $_GET['term'];
    $dob = date('Y-m-d',strtotime($search));
    $result = $conexion->query('SELECT
                                    pat.id_paciente as id_paciente_soft,
                                    pat.patient_id as id_paciente_eagle,
                                    pat.first_name as first_name,
                                    pat.last_name as last_name,
                                    pac.paciente_contacto as contact,
                                    pac.id_clinica as clinic,
                                    pac.paciente_fechanac as dob,
                                    pat.prim_employer_id as emp_id,
                                    pac.paciente_idioma as lang,
                                    pac.paciente_genero as genre,
                                    e.insurance_company_id as ins_id,
                                    ic.name as ins_name
                                FROM
                                    patient pat
                                INNER JOIN paciente pac ON pat.id_paciente = pac.id_paciente
                                LEFT JOIN employer e ON e.employer_id = pat.prim_employer_id
                                LEFT JOIN insurance_company ic ON e.insurance_company_id = ic.insurance_company_id
                                WHERE pac.paciente_fechanac LIKE "'.$dob.'%" LIMIT 20;');
    $json = array();

    #MySQL
    while ($row = $result->fetch_assoc()) 
    {
        $id_paciente_soft = $row['id_paciente_soft'];
        $id_paciente_eagle = $row['id_paciente_eagle'];
        $clinic = $row['clinic'];
        $name = $row['first_name'];
        $last = $row['last_name'];
        $birth_to_show = date("m/d/Y",strtotime($row['dob']));
        $birth= $row['dob'];
        $contact = $row['contact'];
        $lang = $row['lang'];
        $genre = $row['genre'];
        $emp_id = $row['emp_id'];
        $ins_name = $row['ins_name'];

        $json[]=array(
            'value' => $id_paciente_soft,
            'label' => $id_paciente_soft .': '.$name.' '.$last.' - '.$contact.' - '.$birth_to_show,
            'id' => $id_paciente_soft,
            'name' => $name,
            'last' => $last,
            'phone' => $contact,
            'ins' => $ins_name,
            'emp' => $emp_id,
            'lang' => $lang,
            'dob' => $birth,
            'dobtoshow' => $birth_to_show
        );
    }

    function jsonEncodeArray( $array ){
        array_walk_recursive( $array, function(&$item) { 
           $item = utf8_encode( $item ); 
        });
        return json_encode( $array );
    }
    echo jsonEncodeArray($json);
?>