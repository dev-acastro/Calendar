<?php 
    include 'include/funciones.php';
?>

<!doctype html>
<html lang="en">

<head>
    <title>PPS | Appointments List</title>
    <?php include 'head.php'; ?>
    <link href="assets/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />

    <style>
        table.dataTable thead {
            background: linear-gradient(to right, #4A00E0, #8E2DE2);
            color: white;
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
                                    <i class="pe-7s-date icon-gradient bg-happy-itmeo">
                                    </i>
                                </div>
                                <div>Appointments
                                    <div class="page-title-subheading">This is a List of Registered Appointments
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav" id="myTab">
                        <li class="nav-item">
                            <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
                                <span>List of Appointments</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-card mb-3 card">
                                        <div class="card-header">List of Registered Appointments</div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="display align-middle mb-0 table table-borderless table-striped table-hover"
                                                    id="appointments">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Appt</th>
                                                            <th class="text-center">User</th>
                                                            <th class="text-center">Clinic</th>
                                                            <th class="text-center">Patient</th>
                                                            <th class="text-center">Contact</th>
                                                            <th class="text-center">Reason</th>
                                                            <th class="text-center">Date</th>
                                                            <th class="text-center">Time</th>
                                                            <th class="text-center">State</th>
                                                            <th class="text-center">Lab Case</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <?php include 'footer.php'; ?>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <!-- DATATABLE -->
    <script src="assets/dataTable/jquery.dataTables.min.js"></script>
    <script src="assets/dataTable/dataTables.bootstrap4.min.js"></script>

    <script src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <!-- <script src="assets/bootstrap.bundle.min.js"></script> -->

    <script>
        $(window).bind("load", function() {
            $('#manassas').DataTable({
                "order": [
                        [4, "desc"]
                    ],
            });

            $('#appointments').DataTable({
                    //"responsive":true,
                    "order": [
                        [0, "desc"]
                    ],
                    "serverSide": true,
                    "ajax":{
                        "method": "POST",
                        "url": "appointments/load-appointments.php"
                    },
                    "bProcessing": true,
                    "bPaginate":true,
                    "sPaginationType":"full_numbers", 
                    "iDisplayLength": 10,
                    "aoColumns":[
                        {"mData": "idcita"},
                        {"mData": "user"},
                        {"mData": "clinica"},
                        {"mData": "paciente"},
                        {"mData": "contacto"},
                        {"mData": "reason"},
                        {"mData": "fecha"},
                        {"mData": "hora"},
                        {"mData": "estado"},
                        {"mData": "lab"},
                        {"mData": "action"}
                    ],columnDefs: [
                        { className: 'text-left', targets: [3,5] },
                        { className: 'text-center', targets: [0,1,2,4,6,7,8,9,10] },
                    ],
                    //"iDisplayLength": -1,

                    
                });

            function ConvertTimeformat(format, str) {
                var hours = Number(str.match(/^(\d+)/)[1]);
                var minutes = Number(str.match(/:(\d+)/)[1]);
                var AMPM = str.match(/\s?([AaPp][Mm]?)$/)[1];
                var pm = ['P', 'p', 'PM', 'pM', 'pm', 'Pm'];
                var am = ['A', 'a', 'AM', 'aM', 'am', 'Am'];
                if (pm.indexOf(AMPM) >= 0 && hours < 12) hours = hours + 12;
                if (am.indexOf(AMPM) >= 0 && hours == 12) hours = hours - 12;
                var sHours = hours.toString();
                var sMinutes = minutes.toString();
                if (hours < 10) sHours = "0" + sHours;
                if (minutes < 10) sMinutes = "0" + sMinutes;
                if (format == '0000') {
                    return (sHours + sMinutes);
                } else if (format == '00:00') {
                    return (sHours + ":" + sMinutes);
                } else {
                    return false;
                }
            }

            $(document).on('click', '.edit', function (e) {
                var idapp = $(this).data('id');
                var chart = $(this).data('chart');
                var paciente = $(this).data('paciente');
                var reason = $(this).data('reason');
                var fecha = $(this).data('fecha');
                var clinic = $(this).data('clinic');
                var contacto = $(this).data('contacto');
                var actualdate = $(this).data('actualdate');
                var actualhour = $(this).data('actualhour');

                var clinictext = "";
                if (clinic == 1) {
                    clinictext = "Fairfax";
                } else if (clinic == 2) {
                    clinictext = "Manassas";
                } else if (clinic == 3) {
                    clinictext = "Woodbridge";
                }

                $('#app').val(idapp);
                $('#chart').text(chart);
                $('#patientName').text(paciente);
                $('#appReason').text(reason);
                $('#dateapp').val(fecha);
                $('#clinicapp').val(clinic);
                $('#actualdate').text(actualdate);
                $('#actualhour').text(actualhour);
                $('#clinictext').text(clinictext);
                $('#contacto').text(contacto);

                //CREAR ID DE PACIENTE
                //$("#select_clinic").change(function () {
                var availableDates = [];
                $('#dateapp').datepicker('destroy');
                $('#timeapp').empty();

                $.ajax({
                    type: "POST",
                    url: 'calltrackerentry/loadAvailableDate.php',
                    data: 'id_clinica=' + clinic,
                    dataType: 'JSON',
                    success: function (resp) {

                        for (var d in resp) {
                            availableDates.push(resp[d]);
                        }

                        $('#dateapp').datepicker({
                            format: 'yyyy/mm/dd',
                            todayHighlight: true,
                            daysOfWeekDisabled: [0],
                            daysOfWeekHighlighted: [1, 2, 3, 4, 5, 6],
                            beforeShowDay: function (dt) {
                                mdy = (('' + (dt.getMonth() + 1)).length < 2 ?
                                        '0' : '') + (dt.getMonth() + 1) + "-" +
                                    (('' + (dt.getDate())).length < 2 ? '0' :
                                        '') + dt.getDate() + "-" + dt
                                    .getFullYear();
                                if ($.inArray(mdy, availableDates) != -1) {
                                    return true;
                                } else {
                                    return false;
                                }
                            },
                            changeMonth: true,
                            changeYear: false,
                        });


                    }
                });

                //});

                //Define time to Appointment
                $('#dateapp').change(function (e) {
                    $('#timeapp').empty();
                    const data = {
                        id_clinica: $('#clinicapp').val(),
                        date: $('#dateapp').val()
                    };

                    $.ajax({
                        type: "POST",
                        url: 'calltrackerentry/loadAvailableHours.php',
                        data: data,
                        dataType: 'JSON',
                        success: function (resp) {
                            $(resp).each(function (v) { // valor
                                $('#timeapp').append('<option value="' + (
                                        resp[v].time) + '">' + (resp[v]
                                        .time) + ' -- <b>' + (resp[v]
                                        .available) +
                                    '</b> Available</option>');
                            });
                        }

                    });
                });
            });

            $('#reschedule').click(function () {

                var idapp = $('#app').val();

                const data = {
                    time: ConvertTimeformat("00:00", $('#timeapp').val()),
                    id: idapp,
                    date: $('#dateapp').val()
                }

                $.ajax({
                    type: "POST",
                    url: "appointments/update.php",
                    data: data,
                    success: function (resp) {
                        if (resp == "Success") {
                            swal.fire({
                                title: "Appointment",
                                text: "Has been reschedule successfully",
                                icon: "success"
                            }).then(function () {
                                location.reload();
                            });
                        } else if (resp == "Error") {
                            swal.fire({
                                title: "Appointment",
                                text: "Can't be reschedule. Please Try Again",
                                icon: "error"
                            });
                        } else {
                            swal.fire({
                                title: "Appointment",
                                text: resp,
                                icon: "error"
                            });
                        }
                    }
                });
            });

            //Ver account
            $(document).on("click", ".account", function () {
                pat_id = $.trim($(this).text());
                window.open('pat_account.php?id=' + pat_id, '_blank');
            });

        });
    </script>
</body>

</html>

<div class="modal fade update-app" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Appointment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <p class="card-title">Appointment Info</p>
                            <div class="widget-content p-0">
                                <div class="widget-content-outer">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading" id="actualdate"></div>
                                            <div class="widget-subheading" id="actualhour"></div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="text-danger" id="clinictext"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="widget-content p-0">
                                <div class="widget-content-outer">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading" id="patientName"></div>
                                            <div class="widget-subheading" id="chart"></div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="text-primary" id="appReason"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <p><i class="pe-7s-call text-danger text-bold"></i> <span id="contacto"></span></p>


                            <input type="hidden" id="app" />
                            <input type="hidden" id="clinicapp" />
                        </div>
                    </div>
                    <p class="card-title">Update Appointment Info</p>
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="dateapp">Date</label>
                            <input placeholder="Select a Date" id="dateapp" name="dateapp" class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="timeapp">Time</label>
                            <!-- <input type="text" id="timeapp" name="timeapp" class="form-control" /> -->
                            <select class="form-control" id="timeapp"></select>
                        </div>

                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="reschedule">SAVE CHANGES</button>
                <button type="button" class="btn btn-dark" data-dismiss="modal">CANCEL</button>

            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->

<div class="modal fade modalDetails" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>