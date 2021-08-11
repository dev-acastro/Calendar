<?php
    require("../include/database.php");
    include("../include/funciones.php");

    $countDes = 0;
    $json = array();

    $params = $columns = $totalRecords = array();

	$params = $_REQUEST;

    $columns = array( 
		0 => 'id',
		1 => 'patient',
		2 => 'clinic', 
		3 => 'contact',
		4 => 'dob',
		5 => 'language',
		6 => 'state',
		7 => 'action'
	);

    $where = $sqlTot = $sqlRec = "";

	// check search value exist
	if( !empty($params['search']['value']) ) {   
		$where .=" AND";
		$where .=" ( clinicas.clinica_nombre LIKE '%".$params['search']['value']."%' ";
		$where .=" OR paciente.id_paciente LIKE '".$params['search']['value']."%' ";
		#$where .=" OR CONCAT(paciente.paciente_nombres,' ',paciente.paciente_apellidos LIKE '".$params['search']['value']."%' ";
		$where .=" OR paciente.paciente_nombres LIKE '".$params['search']['value']."%' ";
		$where .=" OR paciente.paciente_apellidos LIKE '".$params['search']['value']."%' ";
		$where .=" OR paciente.paciente_contacto LIKE '".formatTelefono($params['search']['value'])."%' ";
		$where .=" OR paciente.id_paciente_eagle LIKE '".$params['search']['value']."%' ";
		$where .=" OR paciente.paciente_idioma LIKE '%".$params['search']['value']."%' ";
		$where .=" OR paciente.paciente_fechanac LIKE '%".$params['search']['value']."%'  )";
	}

	// getting total number records without any search
	$result = 'SELECT 
    CONCAT(paciente.paciente_nombres," ",paciente.paciente_apellidos) as paciente,
    paciente.id_paciente as id,
    paciente.id_paciente_eagle as ideagle,
    paciente.paciente_contacto as contacto,
    paciente.paciente_fechanac as dob,
    paciente.paciente_idioma as language,
    patient.prim_employer_id as employer,
    clinicas.clinica_nombre as clinic
    FROM paciente 
    LEFT JOIN patient ON patient.id_paciente = paciente.id_paciente 
    INNER JOIN clinicas ON clinicas.id_clinica = paciente.id_clinica';

	$sqlTot .= $result;
	$sqlRec .= $result;
	//concatenate search sql if value exist
	if(isset($where) && $where != '') {

		$sqlTot .= $where;
		$sqlRec .= $where;
	}

 	#$sqlRec .=  " ORDER BY citas.cita_scheduled DESC LIMIT ".$params['start']." ,".$params['length']." ";
 	$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";

	$queryTot = $conexion->query($sqlTot) or die("database error:". mysqli_error($conexion));

	$totalRecords = $queryTot->num_rows;

    /* echo $totalRecords;
    echo $sqlRec;
    print_r($queryTot); */

	$queryRecords = $conexion->query($sqlRec) or die("error to fetch appointments data");

    while($reg=$queryRecords->fetch_assoc()){

        $pat_codigo=$reg['id'];
        $pat_insurance=$reg['employer'];
        $pat_dob=$reg['dob'];
        $pat_idpateagle=$reg['ideagle'];
        $pat_language=$reg['language'];
        $pat_patient=$reg['paciente'];
        $pat_clinic=$reg['clinic']; 
        //$pat_state=$reg['estado']; 
        $pat_contacto=$reg['contacto'];

        if(!empty($reg['paciente'])){
            $paciente = ucwords($reg['paciente']);
        }

        if($pat_insurance == 0){$color_row = "black";}
        else if($pat_insurance > 0){$color_row = "primary";}

        if(strpos($pat_idpateagle, 'M') !== false){
            $id_paciente_eagle = str_replace("M", "", $pat_idpateagle);
        }else{
            $id_paciente_eagle = $pat_idpateagle;
        }

        $patient = '<div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left flex2">
                                <div class="widget-heading text-'.$color_row.'">'.$paciente.'</div>
                                <div class="widget-subheading opacity-7">
                                    <b>Eagle: '.$id_paciente_eagle.'</b>
                                </div>
                            </div>
                        </div>
                    </div>';

        $contacto = formatTelefono($pat_contacto);

        $action = '<button type="button" id="'.$pat_codigo.'" class="account btn btn-info btn-sm"><i class="fa fa-external-link"></i> Account</button>';

        /* $state = '<div class="badge badge-'.$estado_cita.'">'.$pat_state.'</div>'; */

        $json[] =array(
            'id' => $pat_codigo,
            'patient' => $patient,
            'clinic' => $pat_clinic,
            'contact' => $contacto,
            'dob' => date('m-d-Y',strtotime($pat_dob)),
            'language' => $pat_language,
            'state' => '',
            'action' => $action
        );

    }

    $json_data = array(
        "draw"            => intval( $params['draw'] ),   
        "recordsTotal"    => intval( $totalRecords ),  
		"recordsFiltered" => intval($totalRecords),
        "data"            => $json   // total data array
    );

    echo json_encode($json_data);
