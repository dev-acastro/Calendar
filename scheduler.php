<?php

    include 'include/funciones.php';

    $clinica = isset($_POST['clinica']) ?? "";

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset='utf-8' />
    <title>PPS | Scheduler</title>
    <?php include 'head.php'; ?>
    <link href='./assets/calendar/main.css' rel='stylesheet' />
    <link href='./assets/calendar/fullcalendar.print.min.css' rel='stylesheet' media='print' />
    <link href="assets/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />

    <style>
       /* .modal-backdrop.fade.in {
            z-index: -1;
        }*/
    </style>
</head>

<body>
    <div class="container">
        <div class="modal fade" id="modalAppointment" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <p>Some text in the modal.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="container">
        <div class="modal fade" id="modalNewAppointment" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <p>Some text in the modal.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php 
            include_once "navbar.php";
        ?>
        <div class="ui-theme-settings">
            <button type="button" id="TooltipDemo" class="btn-open-options btn btn-warning">
                <i class="fa fa-cog fa-w-16 fa-spin fa-2x"></i>
            </button>
        </div>

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
                                    <i class="pe-7s-date icon-gradient bg-warm-flame">
                                    </i>
                                </div>
                                <div>Sheduler Appointments <b>(Under Construction)</b>
                                    <div class="page-title-subheading">You can see below, all Sheduled Appointments
                                    </div>
                                </div>
                            </div>
                            <div class="page-title-actions">
                                <!-- <button type="button" id="availableHours" data-toggle="modal" data-target=".available"
                                        class="btn-shadow mr-3 btn btn-primary"> Available Hours
                                    </button> -->
                                <button type="button" id="infoStates" data-toggle="modal" data-target=".states"
                                    class="btn-shadow mr-3 btn btn-dark"> States Info
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex my-3 justify-content-between col-md-4">
                        <div >
                            <form action="scheduler.php" method="post">
                                <input type="hidden" name="clinica" value="Fairfax">
                                <button type="submit" class="btn <?php echo (isset($_POST['clinica']))? ($_POST['clinica']  == 'Fairfax')? 'btn-success' : 'btn-primary' : 'btn-primary';?>" value="Fairfax">Fairfax</button>
                            </form>
                        </div>

                        <div >
                            <form action="scheduler.php" method="post">
                                <input type="hidden" name="clinica" value="Manassas">
                                <button type="submit" class="btn  <?php echo (isset($_POST['clinica']))? ($_POST['clinica']  == 'Manassas')? 'btn-success' : 'btn-primary' : 'btn-primary';?>" value="Manassas">Manassas</button>
                            </form>
                        </div>
                        <div >
                            <form action="scheduler.php" method="post">
                                <input type="hidden" name="clinica" value="Woodbridge">
                                <button type="submit" class="btn  <?php echo (isset($_POST['clinica']))? ($_POST['clinica']  == 'Woodbridge')? 'btn-success' : 'btn-primary' : 'btn-primary';?>" value="Woodbridge">Woodbridge</button>
                            </form>
                        </div>
                    </div>

                    <div class="alert alert-success" role="alert"  id="notSyncedAppointments" style="display: none">
                        Appointments are up to date
                    </div>
                    <div class="alert alert-danger" role="alert" id="syncedAppointments" style="display: none">
                        New Appointments have been updated, please <a href="#" id="Reload" class="btn btn-primary" onClick="window.location.reload();">Reload</a>
                    </div>


                    <?php

                    if (!empty($_POST['clinica'])) {

                    ?>
                    <div class="container sm-col-12"  id="newAppointmentForm" hidden>
                        <form>
                            <div class="mb-3">
                                <label for="paciente" class="form-label">Paciente</label>
                                <input type="paciente" class="form-control" id="paciante" aria-describedby="´pacienteHelp">
                                <div id="pacienteHelp" class="form-text">Ingresa nombre o numero de paciente.</div>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1">
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade show active" id="<?php echo $_POST['clinica']?? "";?>" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div id="calendar-<?php echo isset($_POST['clinica'])? strtolower($_POST['clinica']) : "";?>" clinica="<?php echo isset($_POST['clinica'])? strtolower($_POST['clinica']) : "";?>"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <?php  }?>
                <?php include 'footer.php'; ?>
            </div>
        </div>
    </div>




    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jquery-ui/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="./assets/scripts/jquery-ui/jquery-ui.css" />
    <!-- Sweet Alert -->
    <script src="./assets/sweetalert/sweetalert2@9.js"></script>

    <script src='./assets/calendar/moment.min.js'></script>
    <!-- <script src='./assets/calendar/jquery.min.js'></script> -->
    <script src='./assets/calendar/fullcalendar.min.js'></script>
    <script src='./assets/calendar/fullcalendar-rightclick.js'></script>
    <script src='./assets/calendar/bootstrap.min.js'></script>
    <!-- <script src='./assets/calendar/fullcalendar-rightclick.js'></script> -->
    <script src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src='./assets/calendar/main.js'></script>

    <!-- Modal -->
    <!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-2">Cita:</div>
                            <div class="col-10">
                                <input type="text" name="cita" id="cita">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">FEcha inicio:</div>
                            <div class="col-10">
                                <input type="text" name="finicio" id="finicio">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">Hora inicio:</div>
                            <div class="col-10">
                                <input type="text" name="hinicio" id="hinicio">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">Hora Final:</div>
                            <div class="col-10">
                                <input type="text" name="hfin" id="hfin">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div> -->
</body>

</html>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        $('#notSyncedAppointments').hide()
        $('#SyncedAppointments').hide()
        $(document).ready(function () {
            var clinica = $("[id^=calendar]").attr("clinica");
            genCalendar("calendar-" + clinica);
            syncData(clinica);
        });


        function bandera(dato) {
            return dato;
        }

        function genEvent(fecha, idClinica) {
            var arreglo;
            $.ajax({
                    async: false,
                    type: "POST",
                    dataType: 'JSON',
                    url: "calendar/scheduler.php",
                    data:  "fecha=" + getFecha(fecha, 1) + "&idClinica=" + idClinica,
                })
                .done(function (data) {
                    arreglo = data;
                })
                .fail(function (data) {
                    return {};
                });
            return arreglo
        }

        function syncData(clinica){
            var arreglo;
            $.ajax({
                async: true,
                type: "POST",
                dataType: 'JSON',
                url: "calendar/apiData.php",
                data: "action=getAppointments&clinica=" + clinica,
                })
                .done(function (data) {
                    data = JSON.parse(JSON.stringify(data));
                    if (data.synced == true) {
                        $("#syncedAppointments").show('slow')
                    }
                    if (data.synced == false) {
                        $("#notSyncedAppointments").show('slow')
                        setTimeout(function(){ $("#notSyncedAppointments").hide("slow"); }, 5000);
                    }
                    console.log(data.synced);
                })
                .fail(function (data) {
                    return {};
                });
            return arreglo
        }


        function updateAppointment(info) {
            dataPut = {
                "id": info.event.id,
                "start": info.event.startStr,
                "end": info.event.endStr,
                "action": "updateAppointment",
            }
            query = param(dataPut)

            $.ajax({
                async: false,
                type: "POST",
                url: "calendar/apiData.php?",
                data: query
            }).done(function (data) {
                    arreglo = data;
                })
                .fail(function (data) {
                    return {};
                });


        }

        function param(object)
        {
            var parameters = [];
            for (var property in object) {
                if (object.hasOwnProperty(property)) {
                    parameters.push(encodeURI(property + '=' + object[property]));
                }
            }

            return parameters.join('&');
        }

        function createAppointment(info) {

            dataPut = {
                "id": info.event.id,
                "start": info.event.startStr,
                "end": info.event.endStr,
                "action": "createAppointment",
            }

            $.ajax({
                async: false,
                type: "POST",
                url: "calendar/apiData.php",
                data: JSON.stringify(dataPut)
            }).done(function (data) {
                arreglo = data;
            })
                .fail(function (data) {
                    return {};
                });


        }

            function getFecha(fecha, tipo = null) {
                const d = new Date(fecha);
                const y = new Intl.DateTimeFormat('en', {
                    year: 'numeric'
                }).format(d);
                const m = new Intl.DateTimeFormat('en', {
                    month: '2-digit'
                }).format(d);
                const da = new Intl.DateTimeFormat('en', {
                    day: '2-digit'
                }).format(d);
                if (tipo) {
                    return (da + "-" + m + "-" + y);
                } else {
                    return (da + "/" + m + "/" + y);
                }

            }

            function getHora(fecha) {
                const d = new Date(fecha);
                const h = new Intl.DateTimeFormat('en-US', {
                    hour: 'numeric',
                    minute: 'numeric'
                }).format(d);
                //const m = new Intl.DateTimeFormat('en-US', { minute: 'numeric' }).format(d);
                return (h);
            }



        function genCalendar(clinica) {
            var columnas;
            var idClinica;
            var arrayEvent;

            if (clinica == "calendar-fairfax") {
                columnas = [{
                    id: 'FFX1',
                    title: 'FFX1'
                }, {
                    id: 'FFX2',
                    title: 'FFX2'
                }, {
                    id: 'FFX3',
                    title: 'FFX3'
                }, {
                    id: 'FFX4',
                    title: 'FFX4'
                }];
                idClinica = 1;
                arrayEvent = genEvent(Date(), idClinica);
            }
            if (clinica == "calendar-manassas") {
                columnas = [{
                    id: 'OP 1',
                    title: 'OP 1'
                }, {
                    id: 'OP 2',
                    title: 'OP 2'
                }, {
                    id: 'OP 3',
                    title: 'OP 3'
                }, {
                    id: 'OP 4',
                    title: 'OP 4'
                }/* , {
                    id: 'OP 5',
                    title: 'OP 5'
                } */];
                idClinica = 2;
                arrayEvent = genEvent(Date(), idClinica);
            }
            if (clinica == "calendar-woodbridge") {
                columnas = [{
                    id: 'WBG1',
                    title: 'WBG1'
                }, {
                    id: 'WBG2',
                    title: 'WBG2'
                }, {
                    id: 'WBG3',
                    title: 'WBG3'
                }, {
                    id: 'WBG4',
                    title: 'WBG4'
                }];
                idClinica = 3;
                arrayEvent = genEvent(Date(), idClinica);
            }
            var calendarEl = document.getElementById(clinica);
            var citas;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                themeSystem: 'bootstrap',
                customButtons: {
                    next: {
                        text: 'next',
                        click: function () {
                            calendar.next();
                            arrayEvent = genEvent(calendar.getDate(), idClinica);
                            var eventSources = calendar.getEvents();
                            $.each(eventSources, function (index, value) {
                                eventSources[index].remove();
                            });

                            $.each(arrayEvent, function (index, value) {
                                calendar.addEvent(value);
                            });
                            calendar.refetchEvents();
                        }
                    },
                    prev: {
                        text: 'prev',
                        click: function () {
                            calendar.prev();
                            arrayEvent = genEvent(calendar.getDate(), idClinica);
                            var eventSources = calendar.getEvents();
                            $.each(eventSources, function (index, value) {
                                eventSources[index].remove();
                            });

                            $.each(arrayEvent, function (index, value) {
                                calendar.addEvent(value);
                            });
                            calendar.refetchEvents();
                            //console.log(calendar['currentData']['eventSources'][]);
                            //calendar.render();
                        }
                    },
                    today: {
                        text: 'today',
                        click: function () {
                            calendar.today();
                            arrayEvent = genEvent(calendar.getDate(), idClinica);
                            var eventSources = calendar.getEvents();
                            $.each(eventSources, function (index, value) {
                                eventSources[index].remove();
                            });

                            $.each(arrayEvent, function (index, value) {
                                calendar.addEvent(value);
                            });
                            calendar.refetchEvents();
                        }
                    }
                },

                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,resourceTimeGridDay'
                },

                navLinks: true, // can click day/week names to navigate views
                selectable: true,
                selectMirror: true,
                slotEventOverlap: false,
                allDaySlot: false,
                slotMinTime: '07:00:00',
                slotMaxTime: '18:00:00',
                slotDuration: '00:30:00',
                select: function (info) {

                    seat = info.resource.id
                    start = info.start.toISOString().substring(0, 10)
                    time = info.start.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })

                    window.location.replace('addCallTrackerEntry.php?start='+start+'&time='+time+'&seat='+seat)

                },
                //slotLabelInterval : '00:30:00',

                /* select: function (arg) {

                    $("#finicio").val(getFecha(arg.start));
                    $("#hinicio").val(getHora(arg.start));
                    $("#hfin").val(getHora(arg.end));
                    $("#exampleModal").modal("show");

                    var form = $('<form action="addCallTrackerEntry.php" method="post">' +
                        '<input type="text" name="finicio" value="' + getFecha(arg.start) +
                        '" />' +
                        '<input type="text" name="hinicio" value="' + getHora(arg.start) +
                        '" />' +
                        '<input type="text" name="hfin" value="' + getHora(arg.end) + '" />' +
                        '<input type="text" name="silla" value="' + arg.resource.id + '" />' +
                        '<input type="text" name="idClinica" value="' + idClinica + '" />' +
                        '</form>');
                    $('body').append(form);
                    form.submit();

                    //var title = prompt('Event Title:');

                    if (title) {
                        calendar.addEvent({
                            resourceId: arg.resource.id,
                            title: title,
                            start: arg.start,
                            end: arg.end,
                            allDay: arg.allDay
                        })
                    }
                    calendar.unselect()
                }, */
                timeZone: 'local',
                editable: true,
                initialView: 'resourceTimeGridDay',
                resources: (columnas),
                eventRender: function(info) {
                    var tooltip = new Tooltip(info.el, {
                    title: info.event.extendedProps.description,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                    });
                },
                events: arrayEvent,
               /* eventResize : function(data){
                    updateAppointment(data)
                },
                eventDrop : function(data){
                   updateAppointment(data)
                },*/
                eventChange: function(data){

                    updateAppointment(data)
                },
                eventClick: function(eventClickInfo ){
                   //$('#modalAppointment').modal();
                    console.log(eventClickInfo );
                },

            });
            calendar.render();
        }

    });

</script>

<script type="text/javascript">
    $('#exampleModal').on('shown.bs.modal', function () {
        $('#exampleModal').trigger('focus')
    })
</script>