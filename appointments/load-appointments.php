<?php
    require("../include/database.php");
    include("../include/funciones.php");

    $countDes = 0;
    $json = array();

    $params = $columns = $totalRecords = array();

	$params = $_REQUEST;

    $columns = array( 
		0 => 'idcita',
		1 => 'user',
		2 => 'clinica', 
		3 => 'paciente',
		4 => 'contacto',
		5 => 'reason',
		6 => 'fecha',
		7 => 'hora',
		8 => 'estado',
		9 => 'lab',
		10 => 'action'
	);

    $where = $sqlTot = $sqlRec = "";

	// check search value exist
	if( !empty($params['search']['value']) ) {   
		$where .=" AND";
		$where .=" ( citas.cita_fecha LIKE '%".$params['search']['value']."%' ";
		$where .=" OR citas.cita_hora LIKE '%".$params['search']['value']."%' ";
		$where .=" OR citas.id_user LIKE '%".$params['search']['value']."%' ";
		$where .=" OR clinicas.clinica_nombre LIKE '".$params['search']['value']."%' ";
		$where .=" OR citas.id_paciente LIKE '".$params['search']['value']."%' ";
		#$where .=" OR CONCAT(paciente.paciente_nombres,' ',paciente.paciente_apellidos LIKE '".$params['search']['value']."%' ";
		$where .=" OR paciente.paciente_nombres LIKE '".$params['search']['value']."%' ";
		$where .=" OR paciente.paciente_apellidos LIKE '".$params['search']['value']."%' ";
		$where .=" OR paciente.paciente_contacto LIKE '".formatTelefono($params['search']['value'])."%' ";
		$where .=" OR paciente.id_paciente_eagle LIKE '".$params['search']['value']."%' ";
		$where .=" OR appt_types.type_description LIKE '%".$params['search']['value']."%' ";
		$where .=" OR citas_estado.estado_cita LIKE '%".$params['search']['value']."%' ";
		$where .=" OR citas.id_reason LIKE '%".$params['search']['value']."%' )";
	}

	// getting total number records without any search
	$result = 'SELECT 
    citas.id_cita as idcita,
    citas.api_id as api_id,
    citas.id_user as user,
    citas.id_paciente as idpaciente,
    citas.id_reason as reason,
    citas.cita_fecha as fecha,
    citas.cita_hora as hora,
    citas.cita_scheduled as scheduled, 
    citas_estado.estado_cita as estado,
    citas_estado.estado_color as color,
    clinicas.clinica_nombre as clinica,
    CONCAT(paciente.paciente_nombres," ",paciente.paciente_apellidos) as paciente,
    paciente.id_paciente_eagle as ideagle,
    paciente.paciente_contacto as contacto,
    calendario.id_app_type,
    appt_types.type_description as reason2,
    appt_types.type_color_hex as colorhex2, 
    citas_reason.reason_color as colorhex,citas.id_clinica as idclinic FROM citas 
    INNER JOIN citas_estado ON citas.id_cita = citas_estado.id_cita
    INNER JOIN clinicas ON citas.id_clinica = clinicas.id_clinica
    INNER JOIN paciente ON citas.id_paciente = paciente.id_paciente
    INNER JOIN calendario ON citas.id_cita = calendario.id_cita
    LEFT JOIN appt_types ON calendario.id_app_type = appt_types.type_id
    LEFT JOIN citas_reason ON citas.id_reason = citas_reason.reason_nombre
    WHERE YEAR(cita_fecha) = YEAR(CURRENT_DATE()) AND MONTH(cita_fecha) >= MONTH(CURRENT_DATE()) 
    AND citas.id_paciente !="MS2"';

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

        $app_codigo=$reg['idcita'];
        $app_api_id = $reg['api_id'];
        $app_idclinic=$reg['idclinic'];
        $app_idpatsoft=$reg['idpaciente'];
        $app_idpateagle=$reg['ideagle'];
        $app_user=$reg['user'];
        $app_patient=$reg['paciente'];
        $app_clinic=$reg['clinica']; 
        $app_reason=$reg['reason']; 
        $app_reason2=$reg['reason2']; 
        $app_date=$reg['fecha']; 
        $app_time=$reg['hora']; 
        $app_state=$reg['estado']; 
        $app_contacto=$reg['contacto'];
        $app_colorhex=$reg['colorhex'];
        $app_colorhex2=$reg['colorhex2'];

        if(!empty($reg['paciente'])){
            $paciente = ucwords($reg['paciente']);
        }

        $color_cita = $reg['color'];
        if($color_cita == "#3ac47d"){
            $estado_cita = "success";
        }
        else if($color_cita == "#16aaff"){
            $estado_cita = "info";
        }
        else if($color_cita == "#f7b924"){
            $estado_cita = "warning";
        }
        else if($color_cita == "#dcdcdc"){
            $estado_cita = "light";
        }
        else if($color_cita == "#343a40"){
            $estado_cita = "dark";
        }
        else if($color_cita == "#d92550"){
            $estado_cita = "danger";
        }
        else if($color_cita == "#f775f0"){
            $estado_cita = "canceled";
        }
        else{
            $estado_cita = "info";
        }

        $reason = "";
        $reason_color = "";
        if(empty($app_reason)){
            $reason = $app_reason2;
            $reason_color = $app_colorhex2;
        }else{
            $reason = $app_reason;
            $reason_color = $app_colorhex;
        }

        $actualhour = date("h:i A", strtotime($app_time));
        $actualdate = date("F j, Y", strtotime($app_date));
        $actualdate_show = date("m-d-Y", strtotime($app_date));

        if(strpos($app_idpateagle, 'M') !== false){
            $id_paciente_eagle = str_replace("M", "", $app_idpateagle);
        }else{
            $id_paciente_eagle = $app_idpateagle;
        }


        $patient = '<div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left flex2">
                                <div class="widget-heading">
                                    '.$paciente.'</div>
                                <div class="widget-subheading opacity-9">
                                    <b><a id="'.$app_idpatsoft.'" class="account text-alternate" href="javascript:void(0)">'.$app_idpatsoft.'</b></a> (Eagle: '.$id_paciente_eagle.')
                                </div>
                            </div>
                        </div>
                    </div>';

        $contacto = formatTelefono($app_contacto);

        $reason_text = '<svg width="25" height="15" viewBox="0 0 250 150">
                            <rect x="70" y="25" height="100" width="120"
                                style="stroke:#000; fill: '.$reason_color.'">
                            </rect>
                        </svg> '.$reason.'';

        $action = '<div class="row">
                        <div class="btn-group">
                            <button type="button" id="detailsF" data-toggle="modal"
                            data-target=".modalDetails"
                            data-idapp="'.$app_codigo.'"
                            class="details btn btn-primary">
                                <i class="fa fa-search-plus"></i>
                            </button>
                            <button type="button" id="editF" data-toggle="modal"
                                data-target=".update-app"
                                data-id="'.$app_codigo.'"
                                data-chart="'.$app_idpatsoft.'"
                                data-paciente="'.$paciente.'"
                                data-fecha="'.$app_date.'"
                                data-reason="'.$reason.'"
                                data-actualhour="'.$actualhour.'"
                                data-actualdate="'.$actualdate.'"
                                data-contacto="'.$contacto.'"
                                data-clinic="'.$app_idclinic.'"
                                class="edit btn btn-warning"><i class="fa fa-pencil-alt"></i>
                                </button>

                                <button 
                                    type="button" 
                                    id="broken" 
                                    data-id="'.$app_api_id .'"
                                    class="broken btn btn-danger">
                                <i class="fas fa-ban"></i>
                                </button>
                        </div>
                    </div>';

        $state = '<div class="badge badge-'.$estado_cita.'">'.$app_state.'</div>';

        $lab = '<div class="custom-checkbox custom-control">
                    <input type="checkbox" disabled id="exampleCustomCheckbox" class="custom-control-input">
                    <label class="custom-control-label" for="exampleCustomCheckbox"></label>
                </div>';

        $json[] =array(
            'idcita' => $app_codigo,
            'user' => $app_user,
            'clinica' => $app_clinic,
            'paciente' => $patient,
            'contacto' => $contacto,
            'reason' => $reason_text,
            'fecha' => $actualdate_show,
            'hora' => date('h:i A',strtotime($app_time)),
            'estado' => $state,
            'lab' => $lab,
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
