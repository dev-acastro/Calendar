<?php 
session_start();
include("database.php");

$tabla_appointments='appointments_eagle';
$tabla_patients='patients';

$tabla_tp = 'tp';
$tabla_tp_detalle = 'tp_detalle';
$tabla_tp_diagnostico = 'tp_diagnostico';
$tabla_tp_sign = 'tp_sign';

$tabla_no_paciente = 'no_paciente';
$tabla_appt_types='appt_types';
$tabla_costos='costos';
$tabla_costos_materiales='costos_materiales';
$tabla_costos_tipo='tipo_costo';
$tabla_porcentajes='porcentajes';
$tabla_porcentajes_tipos='tipo_porcentaje';
$tabla_tratamientos='tratamientos';
$tabla_tratamientos_tipo='tratamientos_tipo';
$tabla_procedimientos='procedimientos';
$tabla_calendario='calendario';
$tabla_calendario_horario='calendario_business_hours';
$tabla_calendario_horas='calendario_horas';
$tabla_clinicas='clinicas';
$tabla_calls='calls';
$tabla_call_channel='call_channel';
$tabla_call_referal='call_referal';
$tabla_call_campaign='call_campaign';
$tabla_call_tracker='call_tracker';
$tabla_citas='citas';
$tabla_citas_reason='citas_reason';
$tabla_citas_estado='citas_estado';
$tabla_pacientes='paciente';
$tabla_pacientes_contacto_emergencia='paciente_contacto_emergencia';
$tabla_pacientes_familia='paciente_familia';
$tabla_pacientes_policy_holder='paciente_policy_holder';
$tabla_pacientes_financiamiento='paciente_financiamiento';
$tabla_job_number='job_number';
$tabla_job_number_estado='job_number_estado';
$tabla_job_number_file='job_number_file';
$tabla_job_costos='job_costos';
$tabla_job_costos_real='job_costos_real';
$tabla_job_pagos='job_pagos';
$tabla_job_pagos_interna='job_pagos_interna';
$tabla_job_pagos_real='job_pagos_real';
$tabla_job_pagos_real_estado='job_pagos_real_estado';
$tabla_job_pagos_estados='job_pagos_estado';
$tabla_job_pp='job_pp';
$tabla_job_pp_real='job_pp_real';
$tabla_job_no_pp='job_no_pp';
$tabla_job_pricing='job_pricing';
$tabla_job_pricing_real='job_pricing_real';
$tabla_job_procedimientos='job_procedimientos';
$tabla_job_sesiones='job_sesiones';
$tabla_job_sesiones_real='job_sesiones_real';
$tabla_job_sesiones_real_estado='job_sesiones_real_estado';
$tabla_job_sesiones_estado='job_sesiones_estado';
$tabla_job_diagnostico='job_diagnostico';
$tabla_estados='estados';
$tabla_estados_tipo='tipo_estado';
$tabla_usuarios='usuario_login';
$tabla_usuarios_perfil='usuario_perfil';
$tabla_usuarios_cargo='usuario_cargo';

/* test job */
$tabla_test_job_number='test_job_number';
$tabla_test_job_costos='test_job_costos';
$tabla_test_job_pagos='test_job_pagos';
$tabla_test_job_pp='test_job_pp';
$tabla_test_job_pricing='test_job_pricing';
$tabla_test_job_procedimientos='test_job_procedimientos';
$tabla_test_job_sesiones='test_job_sesiones';
$tabla_test_job_tp = 'test_job_tp';
$tabla_test_job_tp_detalle = 'test_job_tp_detalle';

$tabla_sms_status = 'sms_status';
$sign_path = "assets/files/sign/";

$galeria_usuarios = 'assets/images/avatars/';

/*Variables Creadas por Ricardo*/
$pdf_telefono = '(703) 393 9393';
$pdf_SocialMedia = 'https://www.facebook.com/TopDentalmf/';

#<input type="time" class="form-control" name="duration[]" id="duration" min="00:15" max="03:00" step="900">

function alert(){
    if(isset($_SESSION['alert'])){
        echo '
        <div id="toast-container" class="toast-bottom-right" role="alert">
            <div class="toast toast-'.$_SESSION['alert'][0].' aria-live="polite">
            
            <button type="button" class="toast-close-button" role="button" data-dismiss="toast"> <i class="fa fa-times"></i></button>
            <div class="toast-title">Alert</div>
            <div class="toast-message">'.$_SESSION['alert'][1].'</div>
            </div>
        </div>';
        unset($_SESSION['alert']);
    }

}

function cantidad($campo,$valor=null,$tabla=null){
    global $conexion;
    switch($valor){
        case null:
            $query=$conexion->query('select * from '.$tabla);
            break;
        default:
            $query=$conexion->query('select * from '.$tabla.' where '.$campo.'="'.$valor.'"');
    }
    return @$query->num_rows;
}

//Num row con mas de 1 condicion
function cantidad_2($tabla,$condicion){
    global $conexion;

    foreach($condicion as $con=>$c){
        $values_c[]=$con.'="'.$c.'"';
    }

    $query = $conexion->query('SELECT * FROM '.$tabla.' WHERE '.array_to_string($values_c,' AND ').';');
    return @$query->num_rows;
}

function escapar($elemento){
    global $conexion;
    return mysqli_real_escape_string($conexion,$elemento);
}

function obtener_datos($campo,$valor=null,$tabla=null){
    global $conexion;
    (empty($valor))?$consulta='select * from '.$tabla:$consulta='select * from '.$tabla.' where '.$campo.'="'.$valor.'"';
    $query=$conexion->query($consulta);
    return $query->fetch_assoc();
}

function queryOne($query)
{
   global $conexion;
    $result = $conexion->query($query);
    if (!$result) {
        return false;
    }
    if (mysqli_num_rows($result) == 0) {
        return false;
    }
    $row = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $row;
}

function primer_registro(){
    global $conexion;
    global $tabla_admin;
    if(insertar($tabla_admin,array('name'=>escapar($_POST['usuario']),'email'=>escapar($_POST['email']),'access'=>password_hash($_POST['acceso'],PASSWORD_DEFAULT)))){
        $_SESSION['alert']=array('success','Registro exitoso');
    }else{
        $_SESSION['alert']=array('danger','El usuario o el correo ya han sido utilizados');
    }
}

function insertar($tabla,$valores){
    global $conexion;

    /* if($tabla == 'paciente'){
        echo 'insert into '.$tabla.' ('.array_to_string($valores,',',true).') values ('.array_to_string($valores,',',false,true).')';
    } */

    $result = $conexion->query('insert into '.$tabla.' ('.array_to_string($valores,',',true).') values ('.array_to_string($valores,',',false,true).')');
    if($result){
        return lastId($conexion);
    }else{
        return false;
    }
}

//update solo con una condicion
function actualizar($tabla,$valores,$condicion){
    global $conexion;
    foreach($valores as $indice=>$valor){
        $values[]=$indice.'="'.$valor.'"';
    }

    return $conexion->query('update '.$tabla.' set '.array_to_string($values,',').' where '.array_to_string($condicion,'',true).'='.array_to_string($condicion,'',false,true));
}

//Update con mas de 1 condicion
function actualizar_2($tabla,$valores,$condicion){
    global $conexion;
    foreach($valores as $indice=>$valor){
        $values[]=$indice.'="'.$valor.'"';
    }

    foreach($condicion as $con=>$c){
        $values_c[]=$con.'="'.$c.'"';
    }

    return $conexion->query('update '.$tabla.' set '.array_to_string($values,',').' where '.array_to_string($values_c,' AND ').';');
}

function lastId($conexion)
{
    return mysqli_insert_id($conexion);
}

function borrar($tabla,$condicion){
    global $conexion;
    return $conexion->query('delete from '.$tabla.' where '.array_to_string($condicion,'',true).'='.array_to_string($condicion,'',false,true));
}

function array_to_string($array,$separator,$index=false,$mysql=false){
    $string='';
    foreach($array as $indice=>$element){
        if($mysql){
            if(!is_numeric($element)){
                $element='"'.$element.'"';
            }
        }
        if($index){
            $string.=$indice.$separator;
        }else{
            $string.=$element.$separator;
        }
    }
    return trim($string,$separator);
}

function random($longitudPass=11){
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890+-*/!@#$%^&*(_=-)";
    $longitudCadena=strlen($cadena);
    $pass = "";
    for($i=1 ; $i<=$longitudPass ; $i++){
        $pos=rand(0,$longitudCadena-1);
        $pass .= substr($cadena,$pos,1);
    }
    return $pass;
}



function valor_alert($separator='<br>'){
    if(isset($_SESSION['alert'][1])){
        return $_SESSION['alert'][1].$separator;
    }
}

function slug($str){
    # special accents
    $a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','Ð','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','?','?','J','j','K','k','L','l','L','l','L','l','?','?','L','l','N','n','N','n','N','n','?','O','o','O','o','O','o','Œ','œ','R','r','R','r','R','r','S','s','S','s','S','s','Š','š','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Ÿ','Z','z','Z','z','Ž','ž','?','ƒ','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','?','?','?','?','?','?');
    $b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
    return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/','/[ -]+/','/^-|-$/'),array('','-',''),str_replace($a,$b,$str)));
}
if(!function_exists('password_hash')){
    function password_hash($password,$null=null){
        $salt = '$2a$07$usesomadasdsadsadsadasdasdasdsadesillystringfors';
        return crypt($password,$salt);
    }
}
if(!function_exists('password_verify')){
    function password_verify($password,$in_db){
        return crypt($password,$in_db)==$in_db;
    }
}

function timeElapsedSinceNow( $datetime, $full = false )
{
    $now = new DateTime;
    $then = new DateTime( $datetime );
    $diff = (array) $now->diff( $then );
    $diff['w']  = floor( $diff['d'] / 7 );
    $diff['d'] -= $diff['w'] * 7;
    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach( $string as $k => & $v )
    {
        if ( $diff[$k] )
        {
            $v = $diff[$k] . ' ' . $v .( $diff[$k] > 1 ? 's' : '' );
        }
        else
        {
            unset( $string[$k] );
        }
    }
    if ( ! $full ) $string = array_slice( $string, 0, 1 );
    return $string ? implode( ', ', $string ) . ' ago' : 'just now';
}

function makeThumbnail($sourcefile,$max_width, $max_height, $endfile, $type){
    // Takes the sourcefile (path/to/image.jpg) and makes a thumbnail from it
    // and places it at endfile (path/to/thumb.jpg).
    // Load image and get image size.
    switch($type){
        case'image/png':
            $img = imagecreatefrompng($sourcefile);
            break;
        case'image/jpeg':
            $img = imagecreatefromjpeg($sourcefile);
            break;
        case'image/gif':
            $img = imagecreatefromgif($sourcefile);
            break;
        default :
            return 'Un supported format';
    }
    $width = imagesx( $img );
    $height = imagesy( $img );
    if ($width > $height) {
        if($width < $max_width)
            $newwidth = $width;
        else
            $newwidth = $max_width;
        $divisor = $width / $newwidth;
        $newheight = floor( $height / $divisor);
    }
    else {
        if($height < $max_height)
            $newheight = $height;
        else
            $newheight =  $max_height;
        $divisor = $height / $newheight;
        $newwidth = floor( $width / $divisor );
    }
    //Create a new temporary image.
    $tmpimg = imagecreatetruecolor( $newwidth, $newheight );
    imagealphablending($tmpimg, false);
    imagesavealpha($tmpimg, true);
    //Copy and resize old image into new image.
    imagecopyresampled( $tmpimg, $img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $tmpimg;
}

#REFERRAL-------------------------------------------




/**
* Segunda funcion para obtener datos
*
* @param string $campo, es el campo de la base de datos donde se va a hacer where
* @param string $valor, es el valor al que se icuala el campo (campo = valor)
* @param string $tabla, es el nombre de la base de datos
*
* @return array() con datos mixtos
*
*/
function obtener_datos_2($campo,$valor=null,$tabla=null,$orderCampo = null,$tipoOrder = null){
    global $conexion;
    (empty($valor))?$consulta='select * from '.$tabla:$consulta='SELECT * from '.$tabla.' WHERE '.$campo.'="'.$valor.'"';
    if(!empty($orderCampo))
        $consulta=$consulta." ORDER BY ".$orderCampo." ".$tipoOrder;
    $query = $conexion->query($consulta);
    $row1 = array();
    $array1 = array();
    $array2 = array();
    while($row = $query->fetch_assoc()) {
       foreach ($row as $key => $value) {
           $array2[$key] = $value;
       }
       array_push($array1, $array2);
    }
    return $array1;
}
/**
* Funcion para formatear el numero de telefono a solo numero
*
* @return string, numero telefonico solo numero
*
*/
function formatTelefono($telefono){
    $telefono = str_replace("(","",$telefono);
    $telefono = str_replace(")","",$telefono);
    $telefono = str_replace(" ","",$telefono);
    $telefono = str_replace("-","",$telefono);
    return $telefono;
}
function getDaySpanish($fecha,$idioma="Spanish"){
    if($idioma=="English"){
        $dias = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
    }else{
        $dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
    }
    
    return $dias[date('N', strtotime($fecha))];
}
/**
* Funcion para obtener los pacientes para enviarles el mensaje de confirmacion
*
* @return array() con datos mixtos
*
*/
/* function getSmsConfirmClients(){
    global $conexion;
    $query = $conexion->query('SELECT NOW()');
    $fecha = $query->fetch_assoc()["NOW()"];
    $fecha=date("Y-m-d",strtotime("+1 day",strtotime($fecha)));
    do{
        $fecha=date("Y-m-d",strtotime("+1 day",strtotime($fecha)));
        $consulta='SELECT ci.id_cita AS id,ci.id_paciente AS idPaciente, CONCAT(p.paciente_nombres," ",p.paciente_apellidos) AS Nombre, ci.id_reason AS RazonCita, ca.evento_fecha AS Fecha, ca.evento_inicio AS Hora, sms.status AS Estado,p.paciente_contacto AS Telefono, cl.clinica_nombre AS Clinica, p.paciente_idioma AS Idioma FROM citas AS ci LEFT JOIN calendario as ca ON ca.id_cita=ci.id_cita LEFT JOIN citas_estado AS cie ON cie.id_cita=ci.id_cita LEFT JOIN sms_status AS sms ON sms.id_cita=ci.id_cita LEFT JOIN paciente AS p ON p.id_paciente=ci.id_paciente LEFT JOIN clinicas AS cl ON cl.id_clinica=ci.id_clinica WHERE DATE_FORMAT(ca.evento_fecha, "%Y-%m-%d") = "'.$fecha.'" AND cie.estado_cita != "Canceled"';
        $query = $conexion->query($consulta);
        
    }while ($query->num_rows<= 0);
    $row1 = array();
    $array1 = array();
    $array2 = array();
    while($row = $query->fetch_assoc()) {
       foreach ($row as $key => $value) {
           $array2[$key] = $value;
       }
       array_push($array1, $array2);
    }
    return $array1;
} */

function getSmsConfirmClients(){
    global $conexion;
    $query = $conexion->query('SELECT NOW()');
    $fecha = $query->fetch_assoc()["NOW()"];
    $fecha=date("Y-m-d",strtotime("+1 day",strtotime($fecha)));
    //$fecha=date("Y-m-d",strtotime($fecha));
    do{
        $fecha=date("Y-m-d",strtotime("+1 day",strtotime($fecha)));
        $consulta='SELECT ci.id_cita AS id,ci.id_paciente AS idPaciente, CONCAT(p.paciente_nombres," ",p.paciente_apellidos) AS Nombre, ci.id_reason AS RazonCita, ca.evento_fecha AS Fecha, ca.evento_inicio AS Hora, sms.status AS Estado,p.paciente_contacto AS Telefono, cl.clinica_nombre AS Clinica, p.paciente_idioma AS Idioma FROM citas AS ci LEFT JOIN calendario as ca ON ca.id_cita=ci.id_cita LEFT JOIN citas_estado AS cie ON cie.id_cita=ci.id_cita LEFT JOIN sms_status AS sms ON sms.id_cita=ci.id_cita LEFT JOIN paciente AS p ON p.id_paciente=ci.id_paciente LEFT JOIN clinicas AS cl ON cl.id_clinica=ci.id_clinica WHERE DATE_FORMAT(ca.evento_fecha, "%Y-%m-%d") = "'.$fecha.'" AND cie.estado_cita != "Canceled" AND cie.estado_cita != "No Show Up"';
        $query = $conexion->query($consulta);
        
    }while ($query->num_rows<= 0);
    $row1 = array();
    $array1 = array();
    $array2 = array();
    while($row = $query->fetch_assoc()) {
       foreach ($row as $key => $value) {
           $array2[$key] = $value;
       }
       array_push($array1, $array2);
    }
    return $array1;
}

function getSentSms(){
    global $conexion;
    $query = $conexion->query('SELECT NOW()');
   /*  $fecha = $query->fetch_assoc()["NOW()"];
    $fecha=date("Y-m-d",strtotime("+2 day",strtotime($fecha)));
    //$fecha=date("Y-m-d",strtotime($fecha));
    do{
        $fecha=date("Y-m-d",strtotime("+1 day",strtotime($fecha))); */
        $consulta='SELECT 
        ci.id_cita AS id,
        ci.id_paciente AS idPaciente, 
        CONCAT(UCASE(LEFT(paciente_nombres, 1)), SUBSTRING(paciente_nombres, 2)," ",UCASE(LEFT(paciente_apellidos, 1)), SUBSTRING(paciente_apellidos, 2)) AS Nombre,
        ci.id_reason AS RazonCita, 
        ca.evento_fecha AS Fecha, 
        ca.evento_inicio AS Hora, 
        ca.id_app_type AS Apptype,
        sms.status AS Estado,
        p.paciente_contacto AS Telefono, 
        cl.clinica_nombre AS Clinica, 
        p.paciente_idioma AS Idioma,
        cie.estado_cita AS EstadoCita,
        cie.estado_color AS EstadoCitaColor,
        appt_types.type_description as RazonCita2,
        appt_types.type_color_hex as RazonCitaColor2,
        citas_reason.reason_color as RazonCitaColor 
        FROM citas AS ci 
        LEFT JOIN calendario as ca ON ca.id_cita=ci.id_cita 
        LEFT JOIN appt_types ON ca.id_app_type = appt_types.type_id
        LEFT JOIN citas_estado AS cie ON cie.id_cita=ci.id_cita 
        LEFT JOIN sms_status AS sms ON sms.id_cita=ci.id_cita 
        LEFT JOIN paciente AS p ON p.id_paciente=ci.id_paciente 
        LEFT JOIN clinicas AS cl ON cl.id_clinica=ci.id_clinica 
        LEFT JOIN citas_reason ON ci.id_reason = citas_reason.reason_nombre 
        WHERE DATE_FORMAT(ca.evento_fecha, "%Y-%m-%d") BETWEEN CURDATE() and CURDATE() + INTERVAL 7 DAY AND cie.estado_cita != "Canceled" AND cie.estado_cita != "No Show Up" AND cie.estado_cita != "Deleted" AND p.id_paciente NOT IN ("MS2","MSNP777","MS6") 
        ORDER BY date(ca.evento_fecha) ASC';
        $query = $conexion->query($consulta);
        
    /* }while ($query->num_rows<= 0); */
    $array1 = array();
    $array2 = array();
    while($row = $query->fetch_assoc()) {
       foreach ($row as $key => $value) {
           $array2[$key] = $value;
       }
       array_push($array1, $array2);
    }
    return $array1;
}

/**
* Funcion para obtener los pacientes para el calendario scheduler
*
*@param string $fecha, fecha de la cual se quieren obtener los datos
*@param int $idClinkca, id de la clinica
* 
*@return array() con datos mixtos
*
*/
function getSchedulerClients($fecha,$idClinica){
    global $conexion;
    $idRand ="";
    if($idClinica=="1"){
        $idRand="FFX";
    }
    if($idClinica=="2"){
        $idRand="OP ";
    }
    if($idClinica=="3"){
        $idRand="WBG";
    }
    $consulta='SELECT IFNULL(ca.chair,CONCAT("'.$idRand.'",FLOOR(RAND()*(4-1+1)+1))) AS resourceId, CONCAT(ca.id_paciente," ",p.paciente_nombres," ",p.paciente_apellidos) AS title, CONCAT(DATE_FORMAT(ca.evento_fecha, "%Y-%m-%d"),"T",ca.evento_inicio,"+00:00") AS Inicio, CONCAT(DATE_FORMAT(ca.evento_fecha, "%Y-%m-%d"),"T",ca.evento_fin,"+00:00") AS Fin FROM calendario AS ca INNER JOIN paciente AS p ON p.id_paciente=ca.id_paciente WHERE DATE_FORMAT(ca.evento_fecha, "%m-%d-%Y")="'.$fecha.'" AND ca.id_clinica="'.$idClinica.'"';
    //echo $consulta; 
    $query = $conexion->query($consulta);
    $row1 = array();
    $array1 = array();
    $array2 = array();
    while($row = $query->fetch_assoc()) {
       foreach ($row as $key => $value) {
            if($key=="Inicio"){
                $array2["start"] = $value;
            }elseif($key=="Fin"){
                $array2["end"] = $value;
            }else{
                $array2[$key] = $value;
            }
           
       }
       array_push($array1, $array2);
    }
    return $array1;
}

#Eliminar Tildes
function quitar_tildes($cadena) {
    $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
    $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
    $texto = str_replace($no_permitidas, $permitidas ,$cadena);
    return $texto;
}


