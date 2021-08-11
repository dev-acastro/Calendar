<?php 
    include 'include/funciones.php';
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
        .modal-backdrop.fade.in {
            z-index: -1;
        }
    </style>
</head>

<body>

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
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#fairfax">
                                <span>Fairfax</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade show active" id="fairfax" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div id="calendar-fairfax"></div>
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
    <script type="text/javascript" src="./assets/scripts/jquery-ui/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="./assets/scripts/jquery-ui/jquery-ui.css" />
    <!-- Sweet Alert -->
    <script src="./assets/sweetalert/sweetalert2@9.js"></script>

    <script src='./assets/calendar/moment.min.js'></script>
    <script src='./assets/calendar/fullcalendar.min.js'></script>
    <script src='./assets/calendar/fullcalendar-rightclick.js'></script>
    <script src='./assets/calendar/bootstrap.min.js'></script>
    <script src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src='./assets/calendar/main.js'></script>

</body>

</html>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        $(document).ready(function () {

            genCalendar("calendar-fairfax");

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
                    data: "fecha=" + getFecha(fecha, 1) + "&idClinica=" + idClinica,
                })
                .done(function (data) {
                    arreglo = data;
                })
                .fail(function (data) {
                    return {};
                });
            return arreglo

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
                eventRightclick: function () {
                    alert("hola")
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
                /* allDaySlot: false,
                minTime: '05:00:00',
                maxTime: '22:00:00',
                slotDuration: '00:30:00',
                slotLabelInterval : '00:30:00', */

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
                events: arrayEvent
                

            });
            console.log(arrayEvent);
            calendar.render();
        }

    });

</script>
<script type="text/javascript">
    $('#exampleModal').on('shown.bs.modal', function () {
        $('#exampleModal').trigger('focus')
    })
</script>