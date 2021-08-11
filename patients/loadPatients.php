<?php
    require("../include/database.php");
    include("../include/funciones.php");

    $countDes = 0;
    $json = array();

    $params = $columns = $totalRecords = array();

	$params = $_REQUEST;

    $columns = array( 
		0 => 'id',
		1 => 'first_name', 
		2 => 'last_name',
		3 => 'clinic',
		4 => 'contact',
		5 => 'dob',
		6 => 'insurance',
		7 => 'action'
	);

    $where = $sqlTot = $sqlRec = "";

	// check search value exist
	if( !empty($params['search']['value']) ) {   
		$where .=" AND";
		$where .=" ( job_number.job_number LIKE '%".$params['search']['value']."%' ";    
		$where .=" OR clinicas.clinica_nombre LIKE '".$params['search']['value']."%' ";
		$where .=" OR job_number.id_user LIKE '".$params['search']['value']."%' ";
		$where .=" OR job_number.id_paciente LIKE '".$params['search']['value']."%' ";
		$where .=" OR paciente.paciente_nombres LIKE '".$params['search']['value']."%' ";
		$where .=" OR paciente.paciente_apellidos LIKE '".$params['search']['value']."%' ";

		$where .=" OR job_fecha LIKE '%".$params['search']['value']."%' )";
	}

	// getting total number records without any search
	$result = "SELECT job_number.id_paciente as id_paciente,job_number.id_user as usuario,job_number.job_number as job,job_number.job_fecha as job_fecha,job_number.job_treatmentplanid as tp_id,tp_sign.tp_sign_doc as tpsdoc,tp_sign.tp_sign_pat as tpspat,paciente.paciente_nombres as pat_name,paciente.paciente_apellidos as pat_last,clinicas.clinica_nombre as clinica,jnf.job_sign_doc as jnfdoc,jnf.job_sign_pat as jnfpat FROM `job_number` 
    INNER JOIN paciente ON paciente.id_paciente = job_number.id_paciente
    INNER JOIN clinicas ON clinicas.clinica_char = job_number.id_clinica
    INNER JOIN tp_sign ON tp_sign.tp_id = job_number.job_treatmentplanid 
    LEFT JOIN (SELECT `id_file`, `job_number`, `job_sign_doc`, `job_sign_pat` FROM `job_number_file` WHERE `job_sign_pat` is NOT NULL GROUP BY `job_number`) jnf ON jnf.job_number = job_number.job_number WHERE job_fecha > date('2020-08-23')";

	$sqlTot .= $result;
	$sqlRec .= $result;
	//concatenate search sql if value exist
	if(isset($where) && $where != '') {

		$sqlTot .= $where;
		$sqlRec .= $where;
	}

 	$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";

	$queryTot = $conexion->query($sqlTot) or die("database error:". mysqli_error($conexion));

	$totalRecords = $queryTot->num_rows;

	$queryRecords = $conexion->query($sqlRec) or die("error to fetch job numbers data");

    while($reg=$queryRecords->fetch_assoc()){
        
        $num_pp = cantidad('id_jobnumber',$reg["job"],$tabla_job_pp);
        if($num_pp > 0){
            if(empty($reg['jnfpat'])){
                $pp = '<form action="paymentplan_sign.php" method="POST" target="_blank" rel="noopener noreferrer">
                            <input type="hidden" name="enviar_hdn" value="'. $reg['job'].'" />
                            <button type="submit" class="btn btn-danger btn-sm">Sign PP</button>
                        </form>';
            }
            else if(!empty($reg['jnfpat'])){
                $pp = '<form action="paymentplan/printpdf.php" method="POST" target="_blank" rel="noopener noreferrer">
                            <input type="hidden" name="getpdf" value="'.$reg['job'].'" />
                            <button type="submit" class="btn btn-alternate btn-sm" id="getpdf" >
                                <span><i class="fa fa-file-pdf"></i></span> Get PP File</button>
                        </form>';
            }
        }else{
            $pp = "";
        }

        //firmar o pdf de TX

        if(empty($reg['tpspat'])){
            $tx = '<form action="treatmentplan_sign.php" method="POST" target="_blank" rel="noopener noreferrer">
            <input type="hidden" name="enviar_hdn"
                value="'. $reg["tp_id"].'" />
                <button type="submit" class="btn btn-dark btn-sm">Sign TX</button>
             </form>';
        }
        else if(!empty($reg['tpspat'])){
            $tx = '<form action="treatmentplan/printpdf.php" method="POST" target="_blank" rel="noopener noreferrer">
                <input type="hidden" name="getpdf" value="'.$reg['tp_id'].'" />
                <button type="submit" class="btn btn-primary btn-sm" id="getpdf">
                    <span><i class="fa fa-file-pdf"></i></span> Get TX File</button>
            </form>';
        }

        if(!empty($reg['pat_name']) && !empty($reg['pat_last'])){
            $patient_name = $reg['pat_name'].' '.$reg['pat_last'];
        }

        $patient = '<div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left flex2">
                                <div class="widget-heading">
                                    '.$reg['id_paciente'].'</div>
                                <div class="widget-subheading opacity-7">
                                    '.$patient_name.'
                                </div>
                            </div>
                        </div>
                    </div>';

        $action = '<form action="job_account.php" method="POST" target="_blank" rel="noopener noreferrer">
                        <input type="hidden" name="enviar_hdn" value="'.$reg['job'].'" />
                        <button type="submit" class="job-details btn btn-warning btn-sm" data-toggle="modal" id="job-details"
                            data-target="">Account</button>
                    </form>';

        $json[] =array(
            'job_fecha' => $reg['job_fecha'],
            'job' => $reg['job'],
            'patient' => $patient,
            'clinica' => $reg['clinica'],
            'usuario' => $reg['usuario'],
            'tx' => $tx,
            'pp' => $pp,
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
