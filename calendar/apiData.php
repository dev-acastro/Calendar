<?php

    
    require '../include/database.php';

    $post = [];
    $stream = [];

    if(!empty($_POST)){
        $post  = $_POST;
    }
    if(!empty($_GET)){
        $post  = $_GET;
    }

    if (!empty($post['data'])) {
        $stream = $post['data'];
    }


//Constantes a Cambiar
    $token = [];
    $baseUrl = "https://prod.hs1api.com/ascend-gateway/api/v1/";
    $baseUrlv0 = "https://prod.hs1api.com/ascend-gateway/api/v0/";
    $client_id = "kdqoHWRZ1YB5M99QVrtc9p4v2kxSct89";
    $client_secret = "IqHYGkvrsN7eFfRe";
    $OrganizationID = "5e7b7774c9e1470c0d716320";
    $locations = [
        "manassas" => "7000000000114",
        "fairfax" => "7000000000115",
    ];

    $idClinica = [
        "7000000000114" => '2',
        "7000000000115" => '1',
    ];

    
    

    function getToken($client_id, $client_secret)
    {
        $curl = curl_init('https://prod.hs1api.com/oauth/client_credential/accesstoken?grant_type=client_credentials');
        curl_setopt($curl, CURLOPT_POSTFIELDS, "client_id=" . $client_id . "&client_secret=" . $client_secret );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));

        $output = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($output);

        return $result;

    }

    function dateApiToClinic( string $date) : string
    {
        $notFormattedDateFromApi = new DateTime($date);
        $formattedDateFromApi = $notFormattedDateFromApi->format('Y-m-d H:i:s');

        $dateFormatted = new DateTime($formattedDateFromApi, new DateTimeZone('zulu'));
        $dateFormatted->setTimezone(new DateTimeZone('America/New_York'));

        return $dateFormatted->format('Y-m-d H:i:s');

    }

    function dateClinicToApi( string $date) : string
    {
        $notFormattedDateFromApi = new DateTime($date);
        $formattedDateFromApi = $notFormattedDateFromApi->format('Y-m-d H:i:s');

        $dateFormatted = new DateTime($formattedDateFromApi, new DateTimeZone('America/New_York'));
        $dateFormatted->setTimezone(new DateTimeZone('Zulu'));

        return $dateFormatted->format('Y-m-d\TH:i:s.v\Z');

    }

    function getAppointments($locations, $startDate, $endDate, $clinica, $lastSync, $byLastModified = false)
    {
        global $OrganizationID;
        global $token;


        $url = 'https://prod.hs1api.com/ascend-gateway/api/v1/appointments?filter=location.id=='.$locations[$clinica] . ',start>=' . $startDate . ",start<".$endDate;

        if ($byLastModified) {
            $url.= ',lastModified>='.$lastSync;
        }

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return $output;

    }

    function getPatientById($id)
    {
        global $OrganizationID;
        global $token;

        $curl = curl_init('https://prod.hs1api.com/ascend-gateway/api/v1/patients/'. $id);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return $output;

    }

    function getPatientByChartNumber($chartNumber)
    {
        global $OrganizationID;
        global $token;

        $curl = curl_init('https://prod.hs1api.com/ascend-gateway/api/v1/patients?filter=chartNumber=='.$chartNumber);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return $output;

    }

    function getDataByParam($url) {
        global $OrganizationID;
        global $token;

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return $output;
    }

    function getDataById($ep, $id) {
        global $OrganizationID;
        global $token;

        $curl = curl_init('https://prod.hs1api.com/ascend-gateway/api/v1/'.$ep."/".$id);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return $output;
    }

    
    function p($d) {
        print_r($d);
        die();
    }

    function updateAppointment($data, $id)
    {
        global $OrganizationID;
        global $token;
        global $baseUrl;

        $url = $baseUrl .'appointments/'.$id;

        $curl = curl_init($url);
        $body = json_encode($data);



        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
            'Accept: application/json',
            'Content-Type: application/json',
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return $output;
    }

    function createAppointment($data, $id){
        global $OrganizationID;
        global $token;

        $curl = curl_init('https://prod.hs1api.com/ascend-gateway/api/v1/appointments/'. $id);
        $body = json_encode($data);



        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);


        return $output;
    }

    function deleteAppointment($id){
        global $OrganizationID;
        global $token;

        $curl = curl_init('https://prod.hs1api.com/ascend-gateway/api/v1/appointments/'. $id);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return $output;
    }

    function postApi ($url, $data) 
    {
        global $OrganizationID;
        global $token;

        $curl = curl_init();

        $body = json_encode($data);
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$body,
            CURLOPT_HTTPHEADER => array(
                'Organization-ID: ' . $OrganizationID,
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $output = curl_exec($curl);

        curl_close($curl);


        return $output;

    }

    function findInDatabase($table, $id, $column, $return = false) {

        global $conexion;

        $sql = "SELECT * from $table WHERE $column = '$id'";
        $result = $conexion->query($sql);

        if (!$result) {
            $error = $conexion->error;
            echo $error;
            return false;
        }

        if ($result->num_rows == 0) {
            return false;
        } else {
            if ($return) {
                return $result->fetch_all(MYSQLI_ASSOC);
            }
            return true;
        }
    }

    function checkStatus($id, $status) {
        global $conexion;

        $sql = "SELECT * FROM estados_ascend WHERE cita_api_id = '$id' AND estado = '$status'";
        $result = $conexion->query($sql);

        if ($result->num_rows == 0) {
            return false;
        } 

        return true;
    }

    function findInDatabaseMax($location) {
        global $conexion;

        $sql = "SELECT max(created) from sync_times WHERE location = '" .$location . "'" ;
        $result = $conexion->query($sql);
        $row = $result -> fetch_array(MYSQLI_NUM);
        return $row[0];
    }

    function insertInDatabase($table, $data) {
        global $conexion;

        $c = implode(",", array_keys($data));
        $d = implode(", ", array_values($data));

        $template ="";
        $values = "";
        $result = [];


        foreach ($data as $columns) {
            if (!empty($template)) {
                $template .= ",?";            
            } else {
                $template .="?";
            }
            $values .="s";
        }

        $sql = "INSERT INTO $table ($c) VALUES ($template)";
        $conn = $conexion->prepare($sql);
        if (!$conn) {
            $error = $conexion->error;
            echo $error;
            return false;
        }
        $conn->bind_param($values, ...array_values($data));
        $res = $conn->execute();
        $result = $conn->get_result();
       

        if ($res) {
            return true;
        } else {
            $error = $conexion->error;
            $error2 = $conn->error;
        
           return false;
        }
    }

    function updateInDatabase($table, $data, $id, $column = null): bool {
        echo "In update Function";
        
        $values ="";
        global $conexion;

        foreach ($data as $key => $value) {
            $values .= empty($values)? "" : ",";
            $values .= "$key = '$value'" ;
        }

        //$columnId = !empty($column) ?? $id;

        $sql = "UPDATE $table SET $values WHERE $column = '$id' ";
        $result = $conexion->query($sql);

        if ($result) {
            return true;
        } else {
            $error = $conexion->error;

            return false;
        }


    }

    function retrieveLocationId($location) {

        $result = "";

        switch ($location) {
            case "FFX1":
                $result = "7";
                break;
            case "FFX2":
                $result = "8";
                break;
            case "FFX3":
                $result = "9";
                break;
            case "FFX4":
                $result = "10";
                break;
            case "OP-1":
                $result = "2";
                break;
            case "OP-2":
                $result = "3";
                break;
            case "OP-3":
                $result = "4";
                break;
            case "OP-4":
                $result = "5";
                break;
            case "WG1":
                $result = "11";
                break;
            case "WG2":
                $result = "12";
                break;
            case "WG3":
                $result = "13";
                break;
            case "WG4":
                $result = "14";
                break;

        }
        return $result;

    }

    function syncApiOperatory()
    {
        $url = "https://prod.hs1api.com/ascend-gateway/api/v1/operatories";
        $operatories = json_decode(getDataByParam($url));

        $rest = [];
        $rest['status'] = 'Failed';

        if ($operatories->statusCode == 200) {
            foreach ($operatories->data as $data) {
                //Buscar Operatory en DB
                $result = findInDatabase('operatories', $data->id, 'api_id');

                $opArray = [
                    "api_id" => $data->id,
                    'name' => $data->name,
                    'shortName' => $data->shortName,
                    'location_id' => $data->location->id,
                ];

                if (!$result) {
                    $result = insertInDatabase('operatories', $opArray);
                    $rest['status'] = "Success";
                    $rest[]= "New Operatory with id ".$data->id . " and name " . $data->name . " has been added";
                } elseif ($result) {
                    $result = updateInDatabase('operatories', $opArray, $data->id);
                    $rest['status'] = "Success";
                    $rest[]= "Operatory with id ".$data->id . " and name " . $data->name . " has been updated";
                }

            }

        }
        print_r($rest);

    }

    function syncApiLocation(){
        $url = "https://prod.hs1api.com/ascend-gateway/api/v1/locations";
        $locations = json_decode(getDataByParam($url));
        $rest = [];
        $rest['status'] = 'Failed';



        if ($locations->statusCode == 200) {
            foreach ($locations->data as $data) {
                //Buscar Operatory en DB
                $result = findInDatabase('locations', $data->id, 'api_id');

                $loArray = [
                    "api_id" => $data->id,
                    'name' => $data->name,
                ];

                if (!$result) {
                    $result = insertInDatabase('locations', $loArray);
                    $rest['status'] = "Success";
                    $rest[]= "New Location with id ".$data->id . " and name " . $data->name . " has been added";
                } elseif ($result) {
                    $result = updateInDatabase('locations', $loArray, $data->id);
                    $rest['status'] = "Success";
                    $rest[]= "Location with id ".$data->id . " and name " . $data->name . " has been updated";
                }

            }

        }

        print_r($rest);
    }

    function syncApiColorCategories()
    {
        $url = "https://prod.hs1api.com/ascend-gateway/api/v1/operatories";
        $operatories = json_decode(getDataByParam($url));

        $rest = [];
        $rest['status'] = 'Failed';

        if ($operatories->statusCode == 200) {
            foreach ($operatories->data as $data) {
                //Buscar Operatory en DB
                $result = findInDatabase('operatories', $data->id, 'operatory_id');

                $opArray = [
                    "operatory_id" => $data->id,
                    'name' => $data->name,
                    'shortName' => $data->shortName,
                    'location_id' => $data->location->id,
                ];

                if ($result->num_rows == 0) {
                    $result = insertInDatabase('operatories', $opArray);
                    $rest['status'] = "Success";
                    $rest[]= "New Operatory with id ".$data->id . " and name " . $data->name . " has been added";
                } elseif ($result->num_rows != 0) {
                    $result = updateInDatabase('operatories', $opArray, $data->id);
                    $rest['status'] = "Success";
                    $rest[]= "Operatory with id ".$data->id . " and name " . $data->name . " has been updated";
                }

            }

        }
        print_r($rest);

    }

    function syncApiAppointment($dataApi) {

        $resSync =[];
        $resSync['synced'] = false;

        foreach ($dataApi as $data) {

            $resultDB =findInDatabase("appointment", $data->id, "appointment_id");

            $operatory = getDataByParam($data->operatory->url);
            $data->operatoryData = json_decode($operatory)->data;
            //$data->resourceId = $data->operatoryData->shortName;

            $clinica = getDataByParam($data->location->url);
            $data->locationData = json_decode($clinica)->data;
            $data->operatoryData->location = $data->locationData->name;

            $location = $data->operatoryData->name;
            $locationId = retrieveLocationId($location);
            $patient_id = $data->patient->id;
            $appointment_id = $data->id;


            if($resultDB->num_rows == 0) {
                //Cita no esta en Sistema

                //* ALERT -- ALERT   *//
                //Check if ChartNumber is the Id we're gonna use for Prod
             $resultPT = findInDatabase("patient", $patient_id, "patient_id");
                 if($resultPT->num_rows == 0) {
                     //Paciente no esta en sistema

                     $patient = getPatientById($data->patient->id);
                     $data->patientData = json_decode($patient)->data;

                    //id_paciente corresponde a id conformado por ubicacion y numero ex.: MS2837, FX8734, etc
                    //patient_id corresponde al id tomado de la api
                    $PatientData = [
                        "id_paciente" => $data->patientData->chartNumber,

                        "patient_id" => $patient_id,
                        "first_name" => $data->patientData->firstName,
                        "last_name" => $data->patientData->lastName ?? "",
                        "home_phone" => $data->patientData->phones[0]->number ?? "",
                        "sex" => $data->patientData->gender ?? "",
                        "birth_date" => $data->patientData->dateOfBirth ?? "",
                    ];
                     $result = insertInDatabase('patient', $PatientData);

                 }

                $appointmentData = [
                    "id_paciente" => $data->patientData->chartNumber,
                    "start_time" => dateToClinic($data->start),
                    "end_time" => dateToClinic($data->end),
                    "patient_id" => $patient_id,

                    "location_id" => $locationId,
                    "appointment_type_id" => "1",
                    "appointment_id" => $data->id,
                    "appointment_notes" => $data->note ?? "",
                    "description" => $data->patientData->firstName . " " . $data->patientData->lastName
                ];
                $result = insertInDatabase("appointment", $appointmentData);

                if ($result) {
                    $resSync['synced'] = true;
                }
            } else {

                $appointmentData = [
                    "start_time" => dateToClinic($data->start),
                    "end_time" => dateToClinic($data->end),
                    "location_id" => $locationId,
                    "appointment_type_id" => "1",
                    "appointment_id" => $data->id,
                    "appointment_notes" => $data->note ?? "",
                ];
                $result =  updateInDatabase("appointments", $appointmentData, $id);

                if ($result) {
                    $resSync['synced'] = true;
                }
            }
        }
        print_r(json_encode($resSync));
    }

    function reasonsfromdbtoascend () {

        global $locations;
        global $conexion2;
        global $conexion;
        global $token;
        global $OrganizationID;


        $aColorsUrl = 'https://prod.hs1api.com/ascend-gateway/api/v1/colorcategories/';
        $locationsUrl = 'https://prod.hs1api.com/ascend-gateway/api/v1/locations/';
        $locationsType = 'LocationV1';
        $curl = curl_init();


        $res = $conexion->query('SELECT * FROM citas_reason');

        $rows =$res->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $row) {

            $color = substr($row['reason_color'], 1, 6);

            foreach ($locations as $location) {
                $data = [
                    "name" => $row['reason_nombre'],
                    "color" => $color,
                    "sequence" => 1,
                    "location" => [
                        "id" => $location,
                        "type" => $locationsType,
                        "url" => $locationsUrl.$location,
                    ]
                ];

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://prod.hs1api.com/ascend-gateway/api/v1/colorcategories',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>json_encode($data),
                    CURLOPT_HTTPHEADER => array(
                        'Organization-ID: ' . $OrganizationID,
                        'Authorization: Bearer ' . $token,
                        'Content-Type: application/json'
                    ),
                ));

                $output = curl_exec($curl);
                curl_close($curl);
                $output = json_decode($output);

                if ($output->statusCode == "201" ) {
                    $id = $output->data->id;
                    $name = $output->data->name;
                    $query = "UPDATE citas_reason SET api_id = $id WHERE reason_nombre = '$name'";

                    $res = $conexion->query($query);

                    if (!$res) {
                        echo "\n Error : " .$conexion->error;
                    } else {
                        echo "No Error on Query";
                    }
                } else {
                    echo "Error" . "<br>";
                    echo json_encode($output );
                }
                echo "Good" . "<br>";
                echo json_encode($output);
            }

        }
    }

    function managePatient($id, $data) {
        
        $column = "id_paciente";
        $PatientData = [
            "id_paciente" => $data['chartNumber'],
            "paciente_nombres" => $data['firstName'],
            "paciente_apellidos" => $data['lastName'] ?? "",
            "paciente_contacto" => $data['phones'][0]['number'] ?? "",
            "paciente_fechanac" => $data['dateOfBirth'] ?? "",
        ];
        $chartNumber = $PatientData['id_paciente'];

        //We check if Patient is in Database
        if (!findInDatabase('paciente', $chartNumber , 'id_paciente')) {
            //Patient is not in database
            if ( insertInDatabase('paciente', $PatientData)) {
                echo "Patient Created Successfully";
            } else {
                echo "Patient $chartNumber Could Not be Created";
            }
        } else {
            //Patient is in Database, we update it
            if (updateInDatabase('paciente', $PatientData, $chartNumber, $column)) {
                echo "Patient Updated Successfully";
            } else {
                echo "Patient $id Could Not be Updated";
            }
        }
        

    }

    function manageAppointment ($id, $data) {

        global $idClinica;
        
        $operatory = getDataByParam($data['operatory']['url']);
        $operatory = json_decode($operatory, true);
        print_r($operatory);
        $data['operatoryData'] = $operatory['data'];
        $location = $operatory['data']['shortName'];
        $idClinic = $idClinica[$operatory['data']['location']['id']];

        $appointmentData = [
            "api_id" => $data['id'],
            'id_clinica' => $idClinic,
            "cita_fecha" => date("Y-m-d",strtotime(dateApiToClinic($data['start']))),
            "cita_hora" => date("H:i:s",strtotime(dateApiToClinic($data['start']))),
            "cita_seat" => $location,
        ];

        if (isset($data['patient'])) {
            $patient = getDataByParam($data['patient']['url']);
            $patient = json_decode($patient, true);
            $data['patientData'] = $patient['data'];

            $appointmentData['id_paciente'] =  $data['patientData']['chartNumber'];
        }

        if (isset($data['practiceProcedures'])) {
            $practiceProcedure = end($data['practiceProcedures']);

            $procedure = getDataByParam($practiceProcedure['url']);
            $procedure = json_decode($procedure, true);

            
        }

        if (isset($data['note'])) {
            $appointmentData['cita_notas'] =  $data['note'];
        }

        if (isset($data['duration'])) {
            $appointmentData['cita_duracion'] =  $data['duration']. " min";
        }

        $appoId = $appointmentData['api_id'];
        $column = 'api_id';

        if (isset($data['status'])) {

            if (!checkStatus($appoId, $data['status'])) {
                $estadoData = [
                    "cita_api_id" => $appointmentData['api_id'],
                    "estado" => $data["status"],
                ];
          
                insertInDatabase("estados_ascend", $estadoData);
            }
            
           

            $appointmentData['cita_estado'] = $data['status'];
        }

        

        
        //We check if Patient is in Database
        if (!findInDatabase('citas', $appoId , 'api_id')) {
            //Patient is not in database
            if ( insertInDatabase('citas', $appointmentData)) {
                echo "Appointment Created Successfully";
            } else {
                echo "Appointment $appoId Could Not be Created";
            }
        } else {
            //Patient is in Database, we update it // ISSUE // REVISAR CAMBIO COLOR 
            if (updateInDatabase('citas', $appointmentData, $appoId, $column)) {
                echo "Appointment Updated Successfully";
            } else {
                echo "Appointment $id Could Not be Updated";
            }
        }


    }

    function newCallEntry($data) { 

        global $locations;
        global $baseUrl;
    
        $operatory = findInDatabase('operatories', $data['operatory'], 'shortName', true);
        $location_id = $operatory[0]['location_id'];
        $appointment_id = "";

        $patientData = [
            'firstName' => $data['paciente_nombres'],
            'lastName' => $data['paciente_apellidos'],
            'dateOfBirth' => $data['paciente_fechanac'],
            'chartNumber' => $data['id_paciente'],
            'contactMethod'=> 'CALL ME',
            'patientStatus' => $data['tipo_paciente'],
            'languageType' => strtoupper($data['paciente_idioma']),
            'gender' => $data['paciente_genero'],
            'address1' => 'xxxxxx',
            'city' => 'xxxxxx',
            'state' => 'VI',
            'postalCode' => '00000',
            'preferredLocation' => [
                'id' => (int)$location_id,
                'type'=>'LocationV1',
                'url'=> $baseUrl.'locations/' . $location_id,
                
            ]
        ];

        $existPatient = getPatientByChartNumber($patientData['chartNumber']);
        $existPatient = json_decode($existPatient,true);
        $existPatientData = $existPatient['data'];

        if (count($existPatientData) == 0) {
            $resultPatient = postApi($baseUrl.'patients', $patientData);
            $resultPatient = json_decode($resultPatient,true);
            $patientId = $resultPatient['data']['id'];
        } else {
            $patientId = $existPatientData[0]['id'];
        }

        //REVISAR DUPLICACION DE PACIENTES O REESCRITO 
        
        if (isset($data['withAppo']) && $data['withAppo'] == true) {
            $start = $data['cita_fecha'] . "  " .  $data['cita_hora'];

            $appointmentData = [
                'start' => dateClinicToApi($start),
                'status' => 'UNCONFIRMED',
                'practiceProcedures' => [
                    [
                        'id' => (int)'7000000344361',
                        'type' => 'PracticeProcedureV1',
                        'url' => "https://prod.hs1api.com/ascend-gateway/api/v1/locations/7000000344361"
                    ]
                ],
                'visits' => [],
                'provider' => [
                    'id' => (int)'7000000067984',
                    'type' => 'ProviderV1',
                    'url' => "https://prod.hs1api.com/ascend-gateway/api/v1/locations/7000000067984"
                ],
                'patient' => [
                    'id' => $patientId,
                    'url'=> $baseUrl.'patients/'. $patientId,
                    'type'=>'PatientV1',
                ],
                'note' => $data['cita_notas'],
                'operatory' => [
                    'id' => $operatory[0]['api_id'], // PROVIDER REVIEW
                    'url'=> $baseUrl.'operatories/'. $operatory[0]['api_id'],
                    'type'=>'OperatoryV1',
                ],

            ]; 

        $resultAppointment = postApi($baseUrl.'appointments', $appointmentData);
        $resultAppointment = json_decode($resultAppointment, true);



            if ($resultAppointment['statusCode'] == 200 || $resultAppointment['statusCode'] == 201) {
                $appointment_id = $resultAppointment['data']['id'];
            }
        }

       echo (string)$appointment_id;

    }

    function getTxCases() 
    {
        global $baseUrlv0;
        global $baseUrl;
        global $OrganizationID;
        global $token;
        global $post;

        $chartNumber = $post['chartNumber'];

        $txCasesUrl = $baseUrl.'txcases/';
        $patientProceduresUrl = $baseUrlv0.'patientprocedures/';
        $practiceProceduresUrl = $baseUrlv0.'practiceprocedures/';
        $visitUrl = $baseUrl.'visits/';
        $patientTeethUrl = $baseUrlv0. 'patientteeth';
        

        $patient = getPatientByChartNumber($chartNumber);
        $patient = json_decode($patient, true);
        $patientId = $patient['data'][0]['id'];

        $txCasesUrl .= '?filter=patient.id=='.$patientId;
        $patientProceduresUrl .= '?filter=patient.id=='.$patientId;
        $practiceProceduresUrl .= '?filter=patient.id=='.$patientId; 
        
        
    

        $txCase = getDataByParam($txCasesUrl);
        $txCase = json_decode($txCase, true)['data'];

        $visitUrl .= '?filter=txCase.id==' . $txCase[0]['id'];


        $visits = getDataByParam($visitUrl);
        $visits = json_decode($visits, true)['data'];

        $patientProcedures = getDataByParam($patientProceduresUrl);
        $patientProcedures = json_decode($patientProcedures, true)['data'];

        $practiceProcedures = [];
        $tooth = [];

        foreach ($patientProcedures as $pp) {
            $practiceProcedures[] = json_decode(getDataByParam($pp['practiceProcedure']['url']), true)['data'];
        }

        $patientTeeth = json_decode(getDataByParam($patientTeethUrl . '?filter=patient.id==' . $patientId), true)['data'];

        foreach ($patientTeeth as $teeth) {
            $tooth [$teeth['toothId']] = $teeth;
        }


        


        foreach ($practiceProcedures as $practiceProcedure) {
            $practice [$practiceProcedure['id']] = $practiceProcedure;
        }

        $response = [];


        foreach ($patientProcedures as $patientprocedure) {

            if ($patientprocedure['status'] == 'TREATMENT_PLAN' && $patientprocedure['state'] == 'ACTIVE'){
                $response[] = [
                    'date_planned' => $patientprocedure['entryDate'],
                    'code' => $practice[$patientprocedure['practiceProcedure']['id']]['adaCode'],
                    'description' =>  $practice[$patientprocedure['practiceProcedure']['id']]['description'],
                    'Tooth' => $tooth[$patientprocedure['procedureTeeth'][0]['toothId']]['toothIndex'],
                    'Surface' => $tooth[$patientprocedure['procedureTeeth'][0]['toothId']]['chartings'][0]['surfaces'],
                    'Fee' => $patientprocedure['amount'],
                    'estimated_insurance' => 0,
                    'pat' => $patientprocedure['amount'] - 0,
    
                ];
            } 
            
            
        }
        

       $response = json_encode($response);
       echo $response;

        

        
    }

    function clinicLog()
    {
        global $conexion;
        
    }



    //FINALIZAN TODAS LAS FUNCIONES. SIGUEN LAS ACCIONES Y REDIRECCIONES

    $resultAuthorization = getToken($client_id, $client_secret);
    $token = $resultAuthorization->access_token;


    if (isset($post['action']) && $post['action'] == 'getAppointments') {

        global $token;

        $clinica = $post['clinica'];

        if (!empty($post['dateArrow'])) {
            $startDate = $post['dateArrow'];
        } else {
            $startDate = date("Y-m-d");
        }

        $endDate = date("Y-m-d", strtotime('+1 day', strtotime($startDate)));

        $data = [
            'location' => strtolower($clinica),
        ];

        $lastSync = dateToApi(findInDatabaseMax(strtolower($clinica)));
        insertInDatabase("sync_times", $data);
        $result = getAppointments($locations, $startDate, $endDate,  $clinica, $lastSync, true);

        $result = json_decode($result);


        $appoinment = $result->data;

        $result = syncApiAppointment($appoinment);

       // echo json_encode($appoinment);


    }

    if (isset($post['action']) && $post['action'] == 'updateAppointment') {
        global $token;
        global $clinica;
        global $baseUrl;

        $appointmentId = $post['id'];
        $data = [];

        $operatoryUrl = $baseUrl.'operatories';


        $currentAppoinment = getDataById("appointments", $appointmentId);
        $currentAppoinment = json_decode($currentAppoinment);

        $resOpe = findInDatabase('operatories', $post['operatory'], 'shortName', true);
        
        $operatory = [
            'id' =>(int) $resOpe[0]['api_id'],
            'type' => 'OperatoryV1',
            'url' => $operatoryUrl
        ];

        $data['start'] = dateClinicToApi($post['start']);
        $data['provider'] = $currentAppoinment->data->provider;
        $data['patient'] = $currentAppoinment->data->patient;
        $data['status'] = $currentAppoinment->data->status;

        $data['patient']->id = (int) $data['patient']->id;
        $data['provider']->id = (int) $data['provider']->id;
        $data['operatory'] = $operatory;



        $result = updateAppointment($data, $appointmentId);
        
       // $res = updateInDatabase($citas, )
       // REVISAR SEATS OPERATORY
       // REVISAR CAMBIO de DURACION

        $result = json_decode($result, true);

        if ($result['statusCode'] == 200 || $result['statusCode'] == 201) {
            echo "OK";
        } else {
            echo "KO";
        }

        //print_r($result);
    }

    if (isset($post['action']) && $post['action'] == 'createAppointment') {


        global $token;

        $patientData = [
          'firstName' => 'Alez',
          'lastName' =>'Camilot',
          'contactMethod' => 'call Me',
          'languageType' => 'Spanish',
          "patientStatus" => "NEW",
          "dateOfBirth" => "",
          "preferredLocation" => [
              "id" => 7000000000115,
            "type" => "LocationV1",
            "url" => "https://prod.hs1api.com/ascend-gateway/api/v1/locations/7000000000115"
          ],
          "address1" => "CA 9845",
          "city" => "Sacramento",
          "state" => "CA",
          "postalCode" => "94203 ",
            'chartNumber' => 'FX2345'
        ];

        if (getPatientbyChartNumber($patientData['chartNumber'])) {
            $result = createAppointment($data);
        } else {
            $resultNewPatient = createPatient($patientData);

        }



        $result = json_decode($result);

        $appoinment = $result->data;



        syncApiAppointment($appoinment);



        // echo json_encode($appoinment);


    }

    if (isset($post['action']) && $post['action'] == 'deleteAppointment') {

        global $token;

        $clinica = $post['clinica'];
        $startDate = gmdate("Y-m-01\TH:i:s\Z");
        $result = getAppointments($locations, $startDate, $clinica);
        $result = json_decode($result);

        $appoinment = $result->data;



        $result = syncApiAppointment($appoinment);



        // echo json_encode($appoinment);


    }


    if (isset($post['action']) ) {

        switch ($post['action']) {

            case 'syncolorcategories':
                syncApiColorCategories();
            break;

            case 'synclocations':
                syncApiLocation();
            break;

            case 'syncoperatory':
                syncApiOperatory();
            break;

            case 'reasonsdb':
                reasonsfromdbtoascend();
            break;

            case 'newCallEntry':
                newCallEntry($post);
                break;

            case 'txcases':
            getTxCases();
            break;

            default: echo "Page Not Found"; break;
        }
    }

   


    // API Redirections
    if (isset($stream['type']) ) {

        switch ($stream['type']) {

            case 'PatientV1';
                managePatient($stream['id'], $stream['payload']);
                break;

            case 'AppointmentV1';
                manageAppointment($stream['id'], $stream['payload']);
                break;


        }
    }




