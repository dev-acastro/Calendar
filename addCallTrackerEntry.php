<?php 
    include 'include/funciones.php';

    if (!empty($_GET)) {
        $inputs = $_GET;


        if (!empty($inputs['seat'])) {

            switch ($inputs['seat']) {
                case "FFX1":
                    $seat = "1";
                    $clinic = 'Fairfax';
                    break;
                case "FFX2":
                    $seat = "1";
                    $clinic = 'Fairfax';
                    break;
                case "FFX3":
                    $seat = "1";
                    $clinic = 'Fairfax';
                    break;
                case "FFX4":
                    $seat = "1";
                    $clinic = 'Fairfax';
                    break;
                case "OP-1":
                    $seat = "2";
                    $clinic = 'Manasass';
                    break;
                case "OP-2":
                    $seat = "2";
                    $clinic = 'Manasass';
                    break;
                case "OP-3":
                    $seat = "2";
                    $clinic = 'Manasass';
                    break;
                case "OP-4":
                    $seat = "2";
                    $clinic = 'Manasass';
                    break;
                case "WG1":
                    $seat = "3";
                    $clinic = 'Woodbridge';
                    break;
                case "WG2":
                    $seat = "3";
                    $clinic = 'Woodbridge';
                    break;
                case "WG3":
                    $seat = "3";
                    $clinic = 'Woodbridge';
                    break;
                case "WG4":
                    $seat = "3";
                    $clinic = 'Woodbridge';
                    break;
            }
        }

}
?>

<!doctype html>
<html lang="en">

<head>
    <title>PPS | Call Tracker Entry</title>
    <?php include 'head.php'; ?>

    <link href="assets/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
    <style>
        .search_list {
            list-style: none; /* Quitamos los marcadores */
            padding: 0; /* Quitamos el padding por defecto de la lista */
            margin-left: 10px; /* Separamos la lista de la izquierda */
        }

        .search_list > li::before { /* Añadimos contenido antes de cada elemento de la lista */
            content: "\2022"; /* Insertamos el marcador */
            padding-right: 8px; /* Establecemos el espacio entre las viñetas y el list item */
            color: red; /* Coloreamos la viñeta */
        }
    </style>
</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php 
            include 'navbar.php';
        ?>

        <div class="app-main">
            <?php
                include 'sidebar.php';

            ?>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-call icon-gradient bg-happy-itmeo">
                                    </i>
                                </div>
                                <div>New Call Tracker Entry
                                    <div class="page-title-subheading">
                                        (<span class="text-danger"><b>*</b></span>) Please Complete the Required Fields
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="main-card mb-3 card">
                                <div class="card-body">

                                    <h5 class="card-title">SEARCH PATIENT</h5>
                                    <div class="form-row">
                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <div>
                                                    <div class="custom-radio custom-control"><input type="radio" checked
                                                            id="search_chartid" name="search_pat[]"
                                                            value="search_chartid" class="custom-control-input"><label
                                                            class="custom-control-label" for="search_chartid">By Chart
                                                            ID</label></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <div>
                                                    <div class="custom-radio custom-control"><input type="radio"
                                                            id="search_name" name="search_pat[]" value="search_name"
                                                            class="custom-control-input"><label
                                                            class="custom-control-label" for="search_name">By Name or
                                                            Lastname</label></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <div>
                                                    <div class="custom-radio custom-control"><input type="radio"
                                                            id="search_phone" name="search_pat[]" value="search_phone"
                                                            class="custom-control-input"><label
                                                            class="custom-control-label" for="search_phone">By Phone
                                                            Number</label></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <div>
                                                    <div class="custom-radio custom-control"><input type="radio"
                                                            id="search_dob" name="search_pat[]" value="search_dob"
                                                            class="custom-control-input"><label
                                                            class="custom-control-label" for="search_dob">By Date of
                                                            Birth</label></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-9" style="display: block;" id="patientchartdiv">
                                            <div class="position-relative row form-group"><label for="patientName"
                                                    class="col-sm-2 col-form-label"><b>Search</b></label>
                                                <div class="col-sm-10">
                                                    <div class="row" id="div_chart" style="display: block;">
                                                        <div class="col-12 input-group">
                                                            
                                                            <input class="form-control" type="text" id="si_chart" placeholder="Input Patient ID">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-light" id="reset_chart">
                                                                    <span class="text-danger">
                                                                        <i class="fa fa-times"></i></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <!-- <br>
                                                        <p><span><i class="text-info fa fa-info-circle"></i></span> <b>For Search with Patient Chart ID:</b></p>
                                                            <ul class="search_list">
                                                                <li>Input <b>"F"</b> followed by the patient id number if the Patient is from <b>Fairfax</b></li>
                                                                <li>Input <b>"M"</b> followed by the patient id number if the Patient is from <b>Manassas</b></li>
                                                                <li>Input <b>"W"</b> followed by the patient id number if the Patient is from <b>Woodbridge</b></li>
                                                            </ul> -->
                                                    </div>
                                                    <div class="row" id="div_name" style="display: none;">
                                                        <div class="col-12 input-group">
                                                            <input class="form-control" type="text" id="si_name" placeholder="Input name or lastname">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-light" id="reset_name">
                                                                    <span class="text-danger">
                                                                        <i class="fa fa-times"></i></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" id="div_phone" style="display: none;">
                                                        <div class="col-12 input-group">
                                                            <input class="phone form-control" type="text" max="10" id="si_phone" placeholder="Input contact phone number">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-light" id="reset_phone">
                                                                    <span class="text-danger">
                                                                        <i class="fa fa-times"></i></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" id="div_date" style="display: none;">
                                                        <div class="col-6 input-group">
                                                            <input class="form-control"  type="text" id="si_dob" data-inputmask-alias="mm/dd/yyyy" data-inputmask="'yearrange': { 'minyear': '1917', 'maxyear': '2020' }" data-val="true" data-val-required="Required" placeholder="mm/dd/yyyy">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-light" id="reset_dob">
                                                                    <span class="text-danger">
                                                                        <i class="fa fa-times"></i></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row" id="alert_box">
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12" style="display: none;" id="resumeTableAppByPat">
                                            <div class="card-header">APPOINTMENTS SUMMARY</div>
                                                <div class="card-body">
                                                    <div class="table-responsive" id="app_toggle">
                                                        <table class="display align-middle mb-0 table table-borderless" id="">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">#</th>
                                                                    <th class="text-center">Date Schedule</th>
                                                                    <th class="text-center">Patient</th>
                                                                    <th class="text-center">Clinic</th>
                                                                    <th class="text-center">Reason</th>
                                                                    <th class="text-center">App Date</th>
                                                                    <th class="text-center">App Hour</th>
                                                                    <th class="text-center">Status</th>
                                                                    <th class="text-center">Confirmation</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="appointmentsByPat_data">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="alertWarning" class="alert alert-warning" role="alert" style="display: none;">
                                <label id="labelAlertWarning"></label>
                            </div>
                            <div id="alertDanger" class="alert alert-danger" role="alert" style="display: none;">
                              <label id="labelAlertDanger"></label>
                            </div>
                            <form id="configform">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">

                                        <h5 class="card-title">SELECT CLINIC</h5>
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <div class="position-relative row form-group"><label for=""
                                                        class="col-sm-4 col-form-label"><b>Clinic <span
                                                                class="text-danger">*</span></b></label>
                                                    <div class="col-sm-8">

                                                     <?php if (!empty($seat) && !empty($clinic)){?>

                                                        <select class="form-control" id="select_clinic" name="select_clinic" disabled>
                                                            <option value="<?php echo $seat;?>" selected><?php echo $clinic;?></option>
                                                        </select>
                                                        <?php  } elseif (empty($seat) && empty($clinic)) {   ?> 

                                                        <select class="form-control" id="select_clinic"
                                                            name="select_clinic">
                                                            <option selected disabled>--- Select a Clinic ---</option>
                                                            <?php
                                                                $result = $conexion->query('SELECT * FROM '. $tabla_clinicas .' ORDER BY clinica_nombre');
                                                                while($clinica=$result->fetch_assoc()):
                                                                    $clinic_codigo=$clinica['id_clinica'];
                                                                    $clinic_name=$clinica['clinica_nombre'];
                                                                    if($clinica['id_clinica']==$_POST['idClinica']){
                                                                        ?>
                                                                    <option value="<?php echo $clinic_codigo;?>" selected><?php echo $clinic_name;?></option>
                                                                        <?php
                                                                    }else{
                                                                        ?>
                                                                    <option value="<?php echo $clinic_codigo;?>"><?php echo $clinic_name;?></option>
                                                                        <!-- <?php
                                                                    }
                                                            ?> -->
                                                            
                                                            <?php endwhile; ?>
                                                        </select>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="position-relative row form-group"><label for=""
                                                        class="col-sm-6 col-form-label"><b>Patient Type:</b></label>
                                                    <div class="col-sm-6">
                                                        <!-- <b class="text-alternate" id="patient_type"></b> -->
                                                        <div class="position-relative form-group">
                                                            <div>
                                                                <div class="custom-radio custom-control"><input type="radio" checked
                                                                        id="new" name="pat_type[]" value="New"
                                                                        class="custom-control-input"><label
                                                                        class="custom-control-label" for="new">New</label>
                                                                </div>
                                                                <div class="custom-radio custom-control"><input type="radio"
                                                                        id="current" name="pat_type[]"
                                                                        value="Current" class="custom-control-input"><label
                                                                        class="custom-control-label"
                                                                        for="current">Current</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="col-md-4" style="display: block;" id="patientchartdiv">
                                                <div class="position-relative row form-group"><label for="chartid"
                                                        class="col-sm-5 col-form-label"><b>Patient Chart</b></label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control" type="text" id="chartid" required>
                                                        <input class="form-control" type="hidden" id="chartid_eagle">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h5 class="card-title">PATIENT INFO</h5>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative row form-group"><label for="name"
                                                        class="col-sm-3 col-form-label"><b>Name <span
                                                                class="text-danger">*</span></b></label>
                                                    <div class="col-sm-9">
                                                        <input input class="form-control" type="text" id="name" required>
                                                    </div>
                                                </div>
                                                <div class="position-relative row form-group"><label for="lastname"
                                                        class="col-sm-3 col-form-label"><b>Lastname <span
                                                                class="text-danger">*</span></b></label>
                                                    <div class="col-sm-9">
                                                        <input class="form-control" type="text" id="lastname" required>
                                                    </div>
                                                </div>
                                                <div class="position-relative row form-group"><label for="pat_genre"
                                                        class="col-sm-3 col-form-label"><b>Genre <span
                                                                class="text-danger">*</span></b></label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" id="pat_genre">
                                                            <option selected disabled value="nonSelected">--- Select an Option ---</option>
                                                            <option value="F">Female</option>
                                                            <option value="M">Male</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="position-relative row form-group"><label for="city"
                                                        class="col-sm-3 col-form-label"><b>City</b> <i>(optional)</i></label>
                                                    <div class="col-sm-9">
                                                        <input class="form-control" type="text" id="city">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative row form-group"><label for="contact"
                                                        class="col-sm-4 col-form-label"><b>Contact Phone <span
                                                                class="text-danger">*</span></b></label>
                                                    <div class="col-sm-8">
                                                        <input class="phone form-control" type="text" id="contact" required>
                                                    </div>
                                                </div>
                                                <div class="position-relative row form-group"><label for="dob"
                                                        class="col-sm-4 col-form-label"><b>Date of Birth <span
                                                                class="text-danger">*</span></b></label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" type="date" id="dob">
                                                    </div>
                                                </div>
                                                <div class="position-relative row form-group"><label for="pat_language"
                                                        class="col-sm-4 col-form-label"><b>Language <span
                                                                class="text-danger">*</span></b></label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" id="pat_language">
                                                            <option selected disabled value="nonSelected">--- Select an Option ---</option>
                                                            <option value="Spanish">Spanish</option>
                                                            <option value="English">English</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="position-relative row form-group"><label for="state"
                                                        class="col-sm-4 col-form-label"><b>State </b><i>(optional)</i></label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" type="text" id="state">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 class="card-title">SHEDULE APPOINTMENT <span class="text-danger">*</span></h5>
                                        <p><small id="operatory_text"> <?php echo isset($inputs['seat'])? $inputs['seat'] : ''?></small></p>
                                        <input name="operatory" id="operatory" type="hidden" value="<?php echo isset($inputs['seat'])? $inputs['seat'] : ''?>">
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <div class="position-relative form-group">
                                                    <div>
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" checked id="app_yes" name="do_app[]"
                                                                value="yes" class="custom-control-input">
                                                            <label class="custom-control-label" for="app_yes">Schedule
                                                                Appointment</label>
                                                                
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="position-relative form-group">
                                                    <div>
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" id="app_no" name="do_app[]" value="no"
                                                                class="custom-control-input">
                                                            <label class="custom-control-label" for="app_no">Only
                                                                Call</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="position-relative form-group">
                                                    <div>
                                                        <div class="custom-checkbox custom-control">
                                                            <input type="checkbox" id="chk_needform" name="chk_needform"
                                                                value="yes" class="custom-control-input" checked>
                                                            <label class="custom-control-label" for="chk_needform">NEED
                                                                FORMS</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row" id="app_div" style="display: block;">
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative row form-group"><label for=""
                                                            class="col-sm-3 col-form-label"><b>Date <span
                                                                    class="text-danger">*</span></b></label>
                                                        <div class="col-sm-9">
                                                            <!-- <input class="form-control" id="app_date" placeholder="Select a clinic to define a Date" value="<?php echo $_POST['finicio']?>"> -->
                                                            <input class="form-control" id="app_date" autocomplete="off" placeholder="Select a clinic to define a Date" value="<?php echo $inputs['start']?? '' ?>" <?php echo isset($inputs['start'])? 'disabled' : '' ?>>
                                                        </div>
                                                    </div>
                                                    <div class="position-relative row form-group"><label for="app_reason"
                                                            class="col-sm-3 col-form-label"><b>Reason <span
                                                                    class="text-danger">*</span></b></label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control ui-autocomplete-input" placeholden="Type a Reason" id="app_reason" name="app_reason" placeholder="Type a Reason">
                                                        </div>
                                                    </div>
                                                    <div class="position-relative row form-group"><label for="app_notes"
                                                            class="col-sm-3 col-form-label"><b>Notes</b></label>
                                                        <div class="col-sm-9">
                                                            <textarea id="app_notes" rows="2" placeholder="Notes" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">

                                                    <div class="position-relative row form-group"><label for="time"
                                                            class="col-sm-3 col-form-label"><b>Time <span
                                                                    class="text-danger">*</span></b></label>
                                                        <div class="col-sm-9">
                                                            <!-- <input class="form-control" id="app_time" placeholder="Select a Time"> -->
                                                            <select class="form-control" id="app_time" <?php echo isset($inputs['time'])? 'disabled': '' ?>>
                                                                <?php if (!empty($inputs['time'])) { ?>
                                                                    <option value="<?php echo $inputs['time'] ?>" selected><?php echo $inputs['time'] ?></option>

                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" value="" id="cita_hora" name="cita_hora">
                                                    <div class="position-relative row form-group"><label for=""
                                                            class="col-sm-3 col-form-label"><b>Duration <span
                                                                    class="text-danger">*</span></b></label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control" id="app_duration" disabled>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <h5 class="card-title">INSURANCE</h5>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative row form-group"><label
                                                            for="pat_have_insurance" class="col-sm-5 col-form-label"><b>Have
                                                                Insurance
                                                                ? <span class="text-danger">*</span></b></label>
                                                        <div class="col-sm-7">
                                                            <select class="form-control" id="pat_have_insurance" name="pat_have_insurance">
                                                                <option value="yes">Yes</option>
                                                                <option value="no" selected>No</option>
                                                            </select>
                                                            <p id="ins_msg" style="display: none;">Insurance Company: <label class="text-alternate" style="font-weight: bold;" id="ins_name"></label></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div id="insurancetype" style="display: none;">
                                                        <div class="position-relative row form-group">
                                                            <label for="pat_type_insurance" class="col-sm-5 col-form-label">
                                                                <b>Type Insurance </b>
                                                            </label>
                                                            <div class="col-sm-7">
                                                                <select class="form-control" id="pat_type_insurance" name="pat_type_insurance">
                                                                    <option value="Self">Self</option>
                                                                    <option value="Policy Holder">Policy Holder</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row" id="noapp_div" style="display: none;">
                                            <div class="col-md-6">
                                                <div class="position-relative row form-group">
                                                    <label for="noapp_notes"
                                                        class="col-sm-3 col-form-label"><b>Notes</b></label>
                                                    <div class="col-sm-9">
                                                        <textarea id="noapp_notes" name="noapp_notes" rows="2"
                                                            class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <br>

                                        <h5 class="card-title">Referral</h5>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative row form-group"><label for="insurance_type"
                                                        class="col-sm-3 col-form-label"><b>Channel <span
                                                                class="text-danger">*</span></b></label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" id="app_channel" name="app_channel">
                                                            <option selected disabled value="nonSelected">--- Select Channel ---</option>
                                                            <?php
                                                                    $result = $conexion->query('SELECT * FROM '. $tabla_call_channel .' ORDER BY channel');
                                                                    while($referal=$result->fetch_assoc()):
                                                                        $channel_codigo=$referal['id_channel'];
                                                                        $channel_name=$referal['channel'];
                                                                ?>
                                                            <option value="<?php echo $channel_codigo?>"><?php echo $channel_name;?></option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div id="app_referal_div" style="display: none;">
                                                    <div class="position-relative row form-group">
                                                        <label for="app_referal"
                                                            class="col-sm-3 col-form-label"><b>Referral <span
                                                                    class="text-danger">*</span></b></label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" id="app_referal" name="app_referal">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div id="currentpatient_div" style="display: none;">
                                                    <div class="position-relative row form-group"><label for="cur_pat_id"
                                                            class="col-sm-3 col-form-label"><b>Patient
                                                                Referral</b></label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control" type="text" placeholder="Input a Existing Patient Name" id="cur_pat_id" name="cur_pat_id">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <h5 class="card-title">Provider</h5>
                                        <p>(Where did you make this appointment?)</p>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative row form-group">
                                                    <label for="app_provider" class="col-sm-3 col-form-label">
                                                        <b>Provider <span class="text-danger">*</span></b>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" id="app_provider" name="app_provider">
                                                            <option selected disabled value="nonSelected">--- Select an Option ---</option>
                                                            <option value="Call Center">Call Center (RingByName)</option>
                                                            <option value="Google Business">Google Business</option>
                                                            <option value="FBDebbie" class="text-alternate" style="font-weight: bold;">Facebook Dr. Debbie</option>
                                                            <option value="FBTopDental" class="text-alternate" style="font-weight: bold;">Facebook Top Dental</option>
                                                            <option value="Staff">Staff (Clinic)</option>
                                                            <option value="IGDebbie" disabled>Instagram Dr. Debbie</option>
                                                            <option value="IGTopDental" disabled>Instagram Top Dental</option>
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="chatName" style="display: none;">
                                                <div class="position-relative row form-group">
                                                    <label for="chat_name" class="col-sm-3 col-form-label">
                                                        <b>Chat Name <span class="text-danger">*</span></b>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input class="form-control" type="text" id="chat_name" name="chat_name" placeholder="Input a Chat Name">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6" id="campaign" style="display: none;">
                                                <div class="position-relative row form-group">
                                                    <label for="app_provider" class="col-sm-3 col-form-label">
                                                        <b>Campaign</b>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" id="app_campaign" name="app_campaign">
                                                            <!-- <option selected disabled value="nonSelected">--- Select a Campaign ---</option> -->
                                                            <!-- <option value="none" selected>None</option>
                                                            <option value="bleaching">💯 Bleaching (Blanqueamiento)</option>
                                                            <option value="consulta_gratis">🔥 Consulta Gratis</option>
                                                            <option value="root_canal">💕 Root Canal</option>
                                                            <option value="sorteo">🎁 Sorteo</option> -->
                                                            <option value="none" selected>None</option>
                                                            <option value="implantes">👩‍🔬👨‍🔬 Implants</option>
                                                            <option value="root_canal">💕 Root Canal</option>
                                                            <option value="25%off">🔍 25% Off en Extraccion / Relleno</option>
                                                            <option value="boton_informativo">👀 Botón Informativo (Video)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <br>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                            <button type="button" class="btn btn-primary" id="addEntry">Save Changes</button>
                                            <button type="reset" class="btn btn-secondary" id="resetFields">Reset Fields</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php include 'footer.php'; ?>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jquery-ui/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="./assets/scripts/jquery-ui/jquery-ui.css" />

    <!-- DATATABLE -->
    <script src="assets/dataTable/jquery.dataTables.min.js"></script>
    <script src="assets/dataTable/dataTables.bootstrap4.min.js"></script>

    <!-- DATEPICKER -->
    <script src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <!--<script type='text/javascript' src="https://cdn.jsdelivr.net/gh/RobinHerbots/jquery.inputmask@3.x/extra/bindings/inputmask.binding.js"></script>-->
    <!--<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/extra/bindings/inputmask.binding.js"></script>-->


    <script src="calltrackerentry/entry.js"></script>

    <!-- input phone mask -->
    <script src="assets/mask/jquery.mask.js"></script>
    <script>
        $('.phone').mask('(000) 000 0000');
    </script>


    <script>
        $(document).ready(function () {

            /* $('#example').DataTable();
            $(":input[data-inputmask-mask]").inputmask();
            $(":input[data-inputmask-alias]").inputmask();

            $.ajax({
                type: 'POST',
                url: "index/sync_pat.php",
                data: 1,
                success: function(response) {
                    console.log(response);
                }
            }); */

            seatValue = $('#select_clinic').val()
            if (seatValue!=null) {$('#select_clinic').trigger('change')}
        });
    </script>
</body>

</html>