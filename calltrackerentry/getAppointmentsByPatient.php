<?php
    require('../include/database.php');
    require('../include/funciones.php');
    require('../include/eagle_con.php');

    if(isset($_POST['patid'])){
        $pat_id = escapar($_POST['patid']);

        if (strpos($pat_id, 'FX') !== false) {
            $id_paciente = str_replace('FX','F',$pat_id);
        }
        else if (strpos($pat_id, 'WE') !== false) {
            $id_paciente = str_replace('WE','W',$pat_id);
        }
        else if (strpos($pat_id, 'MS') !== false){
            $id_paciente = str_replace('MS','',$pat_id);
        }

        $app = 1;
        $status = '';
        $status_color = '';
        
        $query = "SELECT * FROM appointment INNER JOIN provider ON provider_id = scheduled_by INNER JOIN appt_types ON appt_types.type_id = appointment_type_id WHERE patient_id = '".$id_paciente."'  ORDER BY date_appointed DESC";
        $result = odbc_exec($conexion_anywhere,$query);

        while (odbc_fetch_row($result)) 
        {
            /* patient */
            $discount_id = odbc_result($result,'appointment_id');

            /* appointment */
            $appointment_id = odbc_result($result,'appointment_id');
            $date_appointed = odbc_result($result,'date_appointed');
            $start_time = odbc_result($result,'start_time');
            $end_time = odbc_result($result,'end_time');
            $patient_id = odbc_result($result,'patient_id');
            $description = odbc_result($result,'description'); //pat name
            $location_id = odbc_result($result,'location_id'); // clinic
            $scheduled_by = odbc_result($result,'scheduled_by');
            $arrival_status = odbc_result($result,'arrival_status'); //show?
            $confirmation_status = odbc_result($result,'confirmation_status');
            $type_id = odbc_result($result,'type_id');

            $user = queryOne('SELECT * FROM calendario cal INNER JOIN citas c ON c.id_cita = cal.id_cita WHERE cal.id_cita_eagle = '.$appointment_id.';');

            //$agent = ucfirst($user['id_user']);

            if($confirmation_status == 0){$icon = "question"; $color_icon = "danger";}
            else if($confirmation_status == 1){$icon = "check"; $color_icon = "success";}
            else if($confirmation_status == 2){$icon = "paper-plane"; $color_icon = "primary";}
            else if($confirmation_status == 3){$icon = "phone"; $color_icon = "dark";}

            $rea = queryOne('SELECT type_description,type_color_hex as color FROM '.$tabla_appt_types.' WHERE type_id = '.$type_id.';');
            if(empty($rea["type_description"])){
                $reason = "";
            }else{
                $reason = $rea["type_description"];
                $app_color = $rea["color"];
            }

            if($location_id == 1){$clinic = "Manassas";}
            else if($location_id == 2){$clinic = "Manassas";}
            else if($location_id == 3){$clinic = "Manassas";}
            else if($location_id == 5){$clinic = "Manassas";}
            else if($location_id == 16){$clinic = "Manassas";}
            else if($location_id == 7){$clinic = "Fairfax";}
            else if($location_id == 8){$clinic = "Fairfax";}
            else if($location_id == 9){$clinic = "Fairfax";}
            else if($location_id == 10){$clinic = "Fairfax";}
            else if($location_id == 11){$clinic = "Woodbridge";}
            else if($location_id == 12){$clinic = "Woodbridge";}
            else if($location_id == 13){$clinic = "Woodbridge";}
            else if($location_id == 14){$clinic = "Woodbridge";}
            else {$clinic = "No records";}

            if($arrival_status == 4){$status = "Finished"; $status_color = "light";}
            if($arrival_status == 1){$status = "No Show Up"; $status_color = "dark";}
            if($arrival_status == 2){$status = "IN PROCESS"; $status_color = "alternate";}
            if($arrival_status == 0 || is_null($arrival_status) || $arrival_status == ""){$status = "Scheduled"; $status_color = "success";}

            echo '<tr>
                    <td class="text-center text-muted" scope="row">'.$app.'</td>
                    <td class="text-center">'.date('m/d/Y',strtotime($date_appointed)).'</td>
                    <td class="text-left"><b class="text-primary">'.$patient_id.':</b> '.$description.'</td>
                    <td class="text-center">'.$clinic.'</td>
                    <td class="text-left">
                        <svg width="25" height="15" viewBox="0 0 250 150">
                            <rect x="70" y="25" height="100" width="120" style="stroke:#000; fill: '.$app_color.'"></rect>
                        </svg> '.$reason.'
                    </td>
                    <td class="text-center">'.date('m/d/Y',strtotime($start_time)).'</td>
                    <td class="text-center">'.date('h:i A',strtotime($start_time)).'</td>
                    <td class="text-center">
                        <div class="badge badge-'.$status_color.'">'.$status.'</div>
                    </td>
                    <th class="text-center"><span class="text-'.$color_icon.'"><i class="fa fa-'.$icon.'"</span></th>
                </tr>';

            $app++;
        }
    }
?>