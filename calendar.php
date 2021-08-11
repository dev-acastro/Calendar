<?php 
    include 'include/funciones.php';
?>

<!doctype html>
<html lang="en">

<head>
    <title>PPS | Calendar</title>
    <?php include 'head.php'; ?>
    <link href='./assets/calendar/fullcalendar.min.css' rel='stylesheet' />
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
                                <div>Appoinments Calendar
                                    <div class="page-title-subheading">You can see below, all registered appointments
                                    </div>
                                </div>
                            </div>
                            <div class="page-title-actions">
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
                        <li class="nav-item">
                            <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#manassas">
                                <span>Manassas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#woodbridge">
                                <span>Woodbridge</span>
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
                        <div class="tab-pane tabs-animation fade" id="manassas" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div id="calendar-manassas"></div>
                                    <!-- <div id="calendar-bg-events"></div> -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tabs-animation fade" id="woodbridge" role="tabpanel">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <!-- <div id="calendar-bg-events"></div> -->
                                    <div id="calendar-woodbridge"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <?php include 'footer.php'; ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
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

    <script src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.nav-link').change(function () {
                $('#calendar-fairfax').fullCalendar();
                $('#calendar-manassas').fullCalendar();
                $('#calendar-woodbridge').fullCalendar();
            });



            $.ajax({
                url: "calendar/seebhfx4c.php",
                type: "POST",
                data: {},
                dataType: 'JSON',
                success: function (data) {
                    $('#calendar-fairfax').fullCalendar({
                        themeSystem: 'bootstrap4',
                        customButtons: {
                            refreshButton: {
                                text: 'Refresh Calendar',
                                click: function () {
                                    $("#calendar-fairfax").fullCalendar('render');
                                }
                            }
                        },
                        header: {
                            left: "prev,next today refreshButton",
                            center: "title",
                            right: "agendaDay,month,agendaWeek,listMonth"
                        },
                        eventRender: function (event, element) {
                            if (event.icon == "asterisk") {
                                element.find(".fc-title").prepend(
                                    "<i class='text-danger fa fa-" + event.icon +
                                    "'></i> ");
                            } else if (event.icon == "check") {
                                element.find(".fc-title").prepend(
                                    "<i class='text-alternate fa fa-" + event.icon +
                                    "'></i> ");
                            } else if (event.icon == "paper-plane") {
                                element.find(".fc-title").prepend(
                                    "<i class='text-white fa fa-" + event.icon +
                                    "'></i> ");
                            } else if (event.icon == "ban") {
                                element.find(".fc-title").prepend(
                                    "<i class='text-danger fa fa-" + event.icon +
                                    "'></i> ");
                            } else if (event.icon == "trash") {
                                element.find(".fc-title").prepend(
                                    "<i class='text-white fa fa-" + event.icon +
                                    "'></i> ");
                            }

                            if (event.needform == "yes") {
                                element.find(".fc-title").append("<span class='spinner-grow spinner-grow-sm mr-2 text-danger'></span>");
                            } else if (event.needform == "no" || event.needform == null) {
                                element.find(".fc-title").append("<span></span>");
                            }
                        },
                        // businessHours: data, 
                        selectConstraint: data,
                        plugins: ['interaction', 'dayGrid', 'timeGrid'],
                        selectable: true,
                        editable: true,
                        selectOverlap: function (event) {
                            return event.rendering === 'background';
                        },
                        events: 'calendar/loadEventFairfax.php',

                        eventClick: function (calEvent, jsEvent, view, resourceObj) {
                            $.ajax({
                                url: "calendar/loadEventInfo.php",
                                type: "POST",
                                data: {
                                    id: calEvent.id
                                },
                                dataType: 'JSON',
                                success: function (data) {
                                    var texto =
                                        "<div class=''><b>Date: </b>" +
                                            moment(calEvent.start).format("MMMM Do YYYY, h:mm a") + " (" + data[0]["duration"] + ")" +
                                            "<br><b>Reason         : </b>" + data[0]["reason"] +
                                            "<br><b>Patient        : </b>" + data[0]["patient"] +
                                            "<br><b>Patient Type   : </b>" + data[0]["type"] +
                                            "<br><b>Birthdate      : </b>" + data[0]["birthday"] +
                                            "<br><b>Have insurance : </b>" + data[0]["insurance"] +
                                            "<br><b>Notes          : </b>" + data[0]["notes"] +
                                            "<br><b>Referral       : </b>" + data[0]["referral"] +
                                            "<br><b>Contact        : </b>" + data[0]["contact"] +
                                            "<br><b>Added by       : </b>" + data[0]["user"] + " on " + data[0]["dateAdd"] +
                                            "<br><b>Provider       : </b>" + data[0]["provider"] +
                                            "<br><b>Campaign       : </b>" + data[0]["campaign"] +
                                        '</div>'; //Event Start Date

                                    swal.fire({
                                        title: data[0]["eagle"], //Event Title
                                        html: texto,
                                        input: 'select',
                                        inputOptions: {
                                            scheduled: 'Schedule Appointment',
                                            canceled: 'Cancel Appointment',
                                            deleted: 'Delete Appointment',
                                        },
                                        inputPlaceholder: data[0]["icono"],
                                        showCancelButton: true,
                                        confirmButtonText: 'Update',
                                        cancelButtonText: 'Close',
                                        cancelButtonColor: '#3f6ad8',
                                        confirmButtonColor: '#794c8a',
                                        icon: "info"
                                    }).then((result) => {
                                        if (result.value) {
                                            const dataToSend = {
                                                id: calEvent.id,
                                                icon: result.value
                                            };
                                            $.ajax({
                                                url: "calendar/updateApptState.php",
                                                type: "POST",
                                                data: dataToSend,
                                                success: function (response) {
                                                    if (response == "Success") {
                                                        swal.fire({
                                                                title: "Done!",
                                                                text: "The Appointment State was updated!",
                                                                type: "success"
                                                            })
                                                            .then(
                                                                function () {
                                                                    location.reload();
                                                                    //$('#calendar-fairfax').fullCalendar("refetchEvents");
                                                                }
                                                            );
                                                    } else if (response == "Error") {
                                                        swal.fire("Error updating!","Please try again","error");
                                                    }
                                                },
                                                error: function (xhr,ajaxOptions,thrownError) {
                                                    swal.fire("Error updating!","Please try again","error");
                                                }
                                            });
                                        }
                                    }, function (dismiss) {
                                        if (dismiss === 'cancel') {

                                        }
                                    });
                                }
                            });
                        },
                        eventRightclick: function (event, jsEvent, view) {
                            eventsdate = moment(event.start).format('MMMM Do YYYY');
                            date = moment(event.start).format();
                            hour = moment(event.start).format('hh:mm a');

                            swal.fire({
                                title: event.title,
                                html: "Change the clinic this Appointment?",
                                showCancelButton: true,
                                input: 'select',
                                inputOptions: {
                                    1: 'Fairfax',
                                    2: 'Manassas',
                                    3: 'Woodbridge'
                                },
                                inputPlaceholder: 'Select a clinic',
                                confirmButtonColor: '#794c8a',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Change clinic',
                                closeOnConfirm: false,
                                icon: "warning"
                            }).then(result => {
                                if (result.value) {
                                    const dataToSend = {
                                        id: event.id,
                                        clinic: result.value
                                    };
                                    $.ajax({
                                        url: "calendar/updateClinic.php",
                                        type: "POST",
                                        data: dataToSend,
                                        success: function (response) {
                                            if (response == "Success") {
                                                swal.fire({
                                                    title: "Done!",
                                                    text: "Clinic has been Updated",
                                                    type: "success"
                                                }).then(function () {
                                                    location.reload();
                                                });
                                            } else if (response == "Error") {
                                                swal.fire("Error updating!","Please try again", "error");
                                            }

                                        },
                                        error: function (xhr, ajaxOptions, thrownError) {
                                            swal.fire("Error updating!", "Please try again","error");
                                        }
                                    });
                                } else {
                                    console.log('cancel action');
                                    event.revertFunc();
                                }
                            });
                            return false;
                        },
                        eventDrop: function (event, dayDelta, minuteDelta, allDay,
                            revertFunc) {

                            eventsdate = moment(event.start).format('MMMM Do YYYY');
                            date = moment(event.start).format();
                            hour = moment(event.start).format('hh:mm a');

                            swal.fire({
                                title: event.title, //Event Title event.start.toISOString()
                                html: "Do you  want to reschedule this Appointment to <br><b>" + eventsdate + "</b>?" +
                                    '<input id="swal-input2" class="swal2-input" value="' + hour + '">',
                                showCancelButton: true,
                                confirmButtonColor: '#794c8a',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Reschedule',
                                closeOnConfirm: false,
                                icon: "warning"
                            }).then(result => {
                                if (result.value) {
                                    const dataToSend = {
                                        id: event.id,
                                        date: date,
                                        start: ConvertTimeformat("00:00", $('#swal-input2').val())
                                    };
                                    $.ajax({
                                        url: "calendar/update-app.php",
                                        type: "POST",
                                        data: dataToSend,
                                        success: function (response) {
                                            if (response == "Success") {
                                                swal.fire({
                                                    title: "Done!",
                                                    text: "Appointment has been Rescheduled",
                                                    type: "success"
                                                }).then(function () {
                                                    location.reload();
                                                });
                                            } else if (response == "Error") {
                                                swal.fire("Error updating!",
                                                    "Please try again", "error");
                                            }

                                        },
                                        error: function (xhr, ajaxOptions, thrownError) {
                                            swal.fire("Error updating!", "Please try again",
                                                "error");
                                        }
                                    });
                                } else {
                                    console.log('cancel action');
                                    event.revertFunc();
                                    location.reload();
                                }
                            });

                        }

                    });
                }
            });

            $('#calendar-manassas').fullCalendar({
                themeSystem: 'bootstrap4',
                customButtons: {
                    refreshButton: {
                        text: 'Refresh Calendar',
                        click: function () {
                            $("#calendar-manassas").fullCalendar('render');
                        }
                    }
                },
                header: {
                    left: "prev,next today refreshButton",
                    center: "title",
                    right: "agendaDay,month,agendaWeek,listMonth"
                },
                eventRender: function (event, element) {
                    if (event.icon == "asterisk") {
                        element.find(".fc-title").prepend("<i class='text-danger fa fa-" + event.icon + "'></i> ");
                    } else if (event.icon == "check") {
                        element.find(".fc-title").prepend("<i class='text-alternate fa fa-" + event.icon + "'></i> ");
                    } else if (event.icon == "paper-plane") {
                        element.find(".fc-title").prepend("<i class='text-white fa fa-" + event.icon + "'></i> ");
                    } else if (event.icon == "ban") {
                        element.find(".fc-title").prepend("<i class='text-danger fa fa-" + event.icon + "'></i> ");
                    } else if (event.icon == "trash") {
                        element.find(".fc-title").prepend("<i class='text-white fa fa-" + event.icon + "'></i> ");
                    }

                    if (event.needform == "yes") {
                        element.find(".fc-title").append("<span class='spinner-grow spinner-grow-sm mr-2 text-danger'></span>");
                    } else if (event.needform == "no" || event.needform == null) {
                        element.find(".fc-title").append("<span></span>");
                    }
                },
                plugins: ['interaction', 'dayGrid', 'timeGrid'],
                selectable: true,
                editable: true,
                events: 'calendar/loadEventManassas.php',
                eventClick: function (calEvent, jsEvent, view, resourceObj) {
                    $.ajax({
                        url: "calendar/loadEventInfo.php",
                        type: "POST",
                        data: {
                            id: calEvent.id
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            var texto =
                                "<div class=''><b>Date: </b>" +
                                    moment(calEvent.start).format("MMMM Do YYYY, h:mm a") + " (" + data[0]["duration"] + ")" +
                                    "<br><b>Reason         : </b>" + data[0]["reason"] +
                                    "<br><b>Patient        : </b>" + data[0]["patient"] +
                                    "<br><b>Patient Type   : </b>" + data[0]["type"] +
                                    "<br><b>Birthdate      : </b>" + data[0]["birthday"] +
                                    "<br><b>Have insurance : </b>" + data[0]["insurance"] +
                                    "<br><b>Notes          : </b>" + data[0]["notes"] +
                                    "<br><b>Referral       : </b>" + data[0]["referral"] +
                                    "<br><b>Contact        : </b>" + data[0]["contact"] +
                                    "<br><b>Added by       : </b>" + data[0]["user"] + " on " + data[0]["dateAdd"] +
                                    "<br><b>Provider       : </b>" + data[0]["provider"] +
                                    "<br><b>Campaign       : </b>" + data[0]["campaign"] +
                                '</div>'; //Event Start Date

                            swal.fire({
                                title: data[0]["eagle"], //Event Title
                                html: texto,
                                input: 'select',
                                inputOptions: {
                                    /* confirmed: 'Confirmed Appointment',
                                    notconfirmed: 'Not Confirmed Appointment',
                                    sendmessage: 'Message Sent',
                                    cancelapp: 'Cancel Appointment', */
                                    scheduled: 'Schedule Appointment',
                                    canceled: 'Cancel Appointment',
                                    deleted: 'Delete Appointment',
                                },
                                inputPlaceholder: data[0]["icono"],
                                showCancelButton: true,
                                confirmButtonText: 'Update',
                                cancelButtonText: 'Close',
                                cancelButtonColor: '#3f6ad8',
                                confirmButtonColor: '#794c8a',
                                icon: "info"
                            }).then((result) => {
                                if (result.value) {
                                    const dataToSend = {
                                        id: calEvent.id,
                                        icon: result.value
                                    };
                                    $.ajax({
                                        url: "calendar/updateApptState.php",
                                        type: "POST",
                                        data: dataToSend,
                                        success: function (response) {
                                            if (response == "Success") {
                                                swal.fire({
                                                        title: "Done!",
                                                        text: "The Appointment State was updated!",
                                                        type: "success"
                                                    })
                                                    .then(
                                                        function () {
                                                            location.reload();
                                                        }
                                                    );
                                            } else if (response == "Error") {
                                                swal.fire("Error updating!","Please try again","error");
                                            }
                                        },
                                        error: function (xhr,ajaxOptions,thrownError) {
                                            swal.fire("Error updating!","Please try again","error");
                                        }
                                    });
                                }
                            }, function (dismiss) {
                                if (dismiss === 'cancel') {

                                }
                            });
                        }
                    });
                },
                eventRightclick: function (event, jsEvent, view) {
                    eventsdate = moment(event.start).format('MMMM Do YYYY');
                    date = moment(event.start).format();
                    hour = moment(event.start).format('hh:mm a');

                    swal.fire({
                        title: event.title,
                        html: "Change the clinic this Appointment?",
                        showCancelButton: true,
                        input: 'select',
                        inputOptions: {
                            1: 'Fairfax',
                            2: 'Manassas',
                            3: 'Woodbridge'
                        },
                        inputPlaceholder: 'Select a clinic',
                        confirmButtonColor: '#794c8a',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Change clinic',
                        closeOnConfirm: false,
                        icon: "warning"
                    }).then(result => {
                        if (result.value) {
                            const dataToSend = {
                                id: event.id,
                                clinic: result.value
                            };
                            $.ajax({
                                url: "calendar/updateClinic.php",
                                type: "POST",
                                data: dataToSend,
                                success: function (response) {
                                    if (response == "Success") {
                                        swal.fire({
                                            title: "Done!",
                                            text: "Clinic has been Updated",
                                            type: "success"
                                        }).then(function () {
                                            location.reload();
                                        });
                                    } else if (response == "Error") {
                                        swal.fire("Error updating!", "Please try again", "error");
                                    }

                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    swal.fire("Error updating!", "Please try again", "error");
                                }
                            });
                        } else {
                            console.log('cancel action');
                            event.revertFunc();
                        }
                    });
                    return false;
                },
                eventDrop: function (event, dayDelta, minuteDelta, allDay,
                    revertFunc) {

                    eventsdate = moment(event.start).format('MMMM Do YYYY');
                    date = moment(event.start).format();
                    hour = moment(event.start).format('hh:mm a');

                    swal.fire({
                        title: event.title, //Event Title event.start.toISOString()
                        html: "Do you  want to reschedule this Appointment to <br><b>" + eventsdate + "</b>?" +
                            '<input id="swal-input2" class="swal2-input" value="' + hour + '">',
                        showCancelButton: true,
                        confirmButtonColor: '#794c8a',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Reschedule',
                        closeOnConfirm: false,
                        icon: "warning"
                    }).then(result => {
                        if (result.value) {
                            const dataToSend = {
                                id: event.id,
                                date: date,
                                start: ConvertTimeformat("00:00", $('#swal-input2').val())
                            };
                            $.ajax({
                                url: "calendar/update-app.php",
                                type: "POST",
                                data: dataToSend,
                                success: function (response) {
                                    if (response == "Success") {
                                        swal.fire({
                                            title: "Done!",
                                            text: "Appointment has been Rescheduled",
                                            type: "success"
                                        }).then(function () {
                                            location.reload();
                                        });
                                    } else if (response == "Error") {
                                        swal.fire("Error updating!",
                                            "Please try again", "error");
                                    }

                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    swal.fire("Error updating!", "Please try again",
                                        "error");
                                }
                            });
                        } else {
                            console.log('cancel action');
                            event.revertFunc();
                            location.reload();
                        }
                    });

                }
            });

            $('#calendar-woodbridge').fullCalendar({
                themeSystem: 'bootstrap4',
                customButtons: {
                    refreshButton: {
                        text: 'Refresh Calendar',
                        click: function () {
                            $("#calendar-woodbridge").fullCalendar('render');
                        }
                    }
                },
                header: {
                    left: "prev,next today refreshButton",
                    center: "title",
                    right: "agendaDay,month,agendaWeek,listMonth"
                },
                eventRender: function (event, element) {
                    if (event.icon == "asterisk") {
                        element.find(".fc-title").prepend(
                            "<i class='text-danger fa fa-" + event.icon +
                            "'></i> ");
                    } else if (event.icon == "check") {
                        element.find(".fc-title").prepend(
                            "<i class='text-alternate fa fa-" + event.icon +
                            "'></i> ");
                    } else if (event.icon == "paper-plane") {
                        element.find(".fc-title").prepend(
                            "<i class='text-white fa fa-" + event.icon +
                            "'></i> ");
                    } else if (event.icon == "ban") {
                        element.find(".fc-title").prepend(
                            "<i class='text-danger fa fa-" + event.icon +
                            "'></i> ");
                    } else if (event.icon == "trash") {
                        element.find(".fc-title").prepend("<i class='text-white fa fa-" + event.icon + "'></i> ");
                    }

                    if (event.needform == "yes") {
                        element.find(".fc-title").append("<span class='spinner-grow spinner-grow-sm mr-2 text-danger'></span>");
                    } else if (event.needform == "no" || event.needform == null) {
                        element.find(".fc-title").append("<span></span>");
                    }
                },
                // businessHours: data,
                // eventConstraint: data, 
                plugins: ['interaction'],
                selectable: true,
                editable: true,
                events: 'calendar/loadEventWoodbridge.php',
                eventClick: function (calEvent, jsEvent, view, resourceObj) {
                    $.ajax({
                        url: "calendar/loadEventInfo.php",
                        type: "POST",
                        data: {
                            id: calEvent.id
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            var texto =
                                "<div class=''><b>Date: </b>" + moment(calEvent.start).format("MMMM Do YYYY, h:mm a") +
                                " (" + data[0]["duration"] + ")" +
                                "<br><b>Reason         : </b>" + data[0]["reason"] +
                                "<br><b>Patient        : </b>" + data[0]["patient"] +
                                "<br><b>Patient Type   : </b>" + data[0]["type"] +
                                "<br><b>Birthdate      : </b>" + data[0]["birthday"] +
                                "<br><b>Have insurance : </b>" + data[0]["insurance"] +
                                "<br><b>Notes          : </b>" + data[0]["notes"] +
                                "<br><b>Referral       : </b>" + data[0]["referral"] +
                                "<br><b>Contact        : </b>" + data[0]["contact"] +
                                "<br><b>Added by       : </b>" + data[0]["user"] + " on " + data[0]["dateAdd"] +
                                "<br><b>Provider       : </b>" + data[0]["provider"] +
                                "<br><b>Campaign       : </b>" + data[0]["campaign"] +
                                '</div>'; //Event Start Date

                            swal.fire({
                                title: data[0]["eagle"], //Event Title calEvent.title
                                html: texto,
                                input: 'select',
                                inputOptions: {
                                    scheduled: 'Schedule Appointment',
                                    canceled: 'Cancel Appointment',
                                    deleted: 'Delete Appointment',
                                },
                                inputPlaceholder: data[0]["icono"],
                                showCancelButton: true,
                                confirmButtonText: 'Update',
                                cancelButtonText: 'Close',
                                cancelButtonColor: '#3f6ad8',
                                confirmButtonColor: '#794c8a',
                                icon: "info"
                            }).then((result) => {
                                if (result.value) {
                                    const dataToSend = {
                                        id: calEvent.id,
                                        icon: result.value
                                    };
                                    $.ajax({
                                        url: "calendar/updateApptState.php",
                                        type: "POST",
                                        data: dataToSend,
                                        success: function (
                                            response
                                        ) {
                                            if (response ==
                                                "Success"
                                            ) {
                                                swal.fire({
                                                        title: "Done!",
                                                        text: "The Appointment State was updated!",
                                                        type: "success"
                                                    })
                                                    .then(
                                                        function () {
                                                            location.reload();
                                                        }
                                                    );
                                            } else if (
                                                response ==
                                                "Error"
                                            ) {
                                                swal.fire(
                                                    "Error updating!",
                                                    "Please try again",
                                                    "error"
                                                );
                                            }

                                        },
                                        error: function (
                                            xhr,
                                            ajaxOptions,
                                            thrownError
                                        ) {
                                            swal.fire(
                                                "Error updating!",
                                                "Please try again",
                                                "error"
                                            );
                                        }
                                    });
                                }
                            }, function (dismiss) {
                                if (dismiss === 'cancel') {

                                }
                            });
                        }
                    });
                },
                eventRightclick: function (event, jsEvent, view) {
                    eventsdate = moment(event.start).format('MMMM Do YYYY');
                    date = moment(event.start).format();
                    hour = moment(event.start).format('hh:mm a');

                    swal.fire({
                        title: event.title,
                        html: "Change the clinic this Appointment?",
                        showCancelButton: true,
                        input: 'select',
                        inputOptions: {
                            1: 'Fairfax',
                            2: 'Manassas',
                            3: 'Woodbridge'
                        },
                        inputPlaceholder: 'Select a clinic',
                        confirmButtonColor: '#794c8a',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Change clinic',
                        closeOnConfirm: false,
                        icon: "warning"
                    }).then(result => {
                        if (result.value) {
                            const dataToSend = {
                                id: event.id,
                                clinic: result.value
                            };
                            $.ajax({
                                url: "calendar/updateClinic.php",
                                type: "POST",
                                data: dataToSend,
                                success: function (response) {
                                    if (response == "Success") {
                                        swal.fire({
                                            title: "Done!",
                                            text: "Clinic has been Updated",
                                            type: "success"
                                        }).then(function () {
                                            location.reload();
                                        });
                                    } else if (response == "Error") {
                                        swal.fire("Error updating!", "Please try again", "error");
                                    }

                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    swal.fire("Error updating!", "Please try again", "error");
                                }
                            });
                        } else {
                            console.log('cancel action');
                            event.revertFunc();
                        }
                    });
                    return false;
                },
                eventDrop: function (event, dayDelta, minuteDelta, allDay,
                    revertFunc) {

                    eventsdate = moment(event.start).format('MMMM Do YYYY');
                    date = moment(event.start).format();
                    hour = moment(event.start).format('hh:mm a');

                    swal.fire({
                        title: event.title, //Event Title event.start.toISOString()
                        html: "Do you  want to reschedule this Appointment to <br><b>" + eventsdate + "</b>?" +
                            '<input id="swal-input2" class="swal2-input" value="' + hour + '">',
                        showCancelButton: true,
                        confirmButtonColor: '#794c8a',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Reschedule',
                        closeOnConfirm: false,
                        icon: "warning"
                    }).then(result => {
                        if (result.value) {
                            const dataToSend = {
                                id: event.id,
                                date: date,
                                start: ConvertTimeformat("00:00", $('#swal-input2').val())
                            };
                            $.ajax({
                                url: "calendar/update-app.php",
                                type: "POST",
                                data: dataToSend,
                                success: function (response) {
                                    if (response == "Success") {
                                        swal.fire({
                                            title: "Done!",
                                            text: "Appointment has been Rescheduled",
                                            type: "success"
                                        }).then(function () {
                                            location.reload();
                                        });
                                    } else if (response == "Error") {
                                        swal.fire("Error updating!",
                                            "Please try again", "error");
                                    }

                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    swal.fire("Error updating!", "Please try again",
                                        "error");
                                }
                            });
                        } else {
                            console.log('cancel action');
                            event.revertFunc();
                            location.reload();
                        }
                    });

                }

            });

        });
    </script>


</body>

</html>

<!-- States Info Modal -->

<div class="modal fade states" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    <i class="text-primary fa fa-info-circle"></i> Colors and Icons Information
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <br>
                    <h5 class="card-title">APPOINTMENTS COLOR STATES</h5>
                    <div class="row">
                        <table class="mb-0 table table-sm table-borderless table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">State</th>
                                    <th class="text-center">Color</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">Schedule Appointment</td>
                                    <td class="text-center">
                                        <div class="mb-2 mr-2 badge badge-pill badge-info">color</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">Synchronized with Eagle Soft</td>
                                    <td class="text-center">
                                        <div class="mb-2 mr-2 badge badge-pill badge-success">color</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">Reschedule Appointment</td>
                                    <td class="text-center">
                                        <div class="mb-2 mr-2 badge badge-pill badge-warning">color</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">Virtual Consultation</td>
                                    <td class="text-center">
                                        <div class="mb-2 mr-2 badge badge-pill badge-alternate">color</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">No Show Up</td>
                                    <td class="text-center">
                                        <div class="mb-2 mr-2 badge badge-pill badge-dark">color</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">Canceled Appointment</td>
                                    <td class="text-center">
                                        <div class="mb-2 mr-2 badge badge-pill badge-danger">color</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">Finished</td>
                                    <td class="text-center">
                                        <div class="mb-2 mr-2 badge badge-pill badge-light">color</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <br>
                    <h5 class="card-title">APPOINTMENTS ICONS STATES</h5>
                    <div class="row">
                        <table class="mb-0 table table-sm table-borderless table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">CONFIRMED</th>
                                    <th class="text-center">NOT CONFIRMED</th>
                                    <th class="text-center">SEND MESSAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><i class="text-alternate text-bold fa fa-check"></i></td>
                                    <td class="text-center"><i class="text-danger fa fa-asterisk"></i></td>
                                    <td class="text-center"><i class="text-dark fa fa-paper-plane"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="row">
                        <table class="mb-0 table table-sm table-borderless table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">CANCEL APPOINTMENT</th>
                                    <th class="text-center">NEED FORMS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><i class="text-danger fa fa-ban"></i></td>
                                    <td class="text-center"><span class='spinner-grow spinner-grow-sm mr-2 text-danger'></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>