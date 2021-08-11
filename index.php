<?php
include 'include/funciones.php';
?>
<!doctype html>
<html lang="en">

<head>
    <title>PPS | Home</title>
    <?php include 'head.php'; ?>

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
                                    <i class="pe-7s-home icon-gradient bg-mean-fruit">
                                    </i>
                                </div>
                                <div>Home
                                    <div class="page-title-subheading">Activities Summary</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="main-card mb-3 card">
                                <?php
                                $finalizadas = queryOne('SELECT COUNT(' . $tabla_citas . '.id_cita) as total_fin FROM ' . $tabla_citas . ' INNER JOIN ' . $tabla_citas_estado . ' ON ' . $tabla_citas . '.id_cita = ' . $tabla_citas_estado . '.id_cita WHERE estado_cita = "Finished" and cita_fecha = CURDATE() AND citas.id_paciente !="MS2";');
                                $canceladas = queryOne('SELECT COUNT(' . $tabla_citas . '.id_cita) as total_canc FROM ' . $tabla_citas . ' INNER JOIN ' . $tabla_citas_estado . ' ON ' . $tabla_citas . '.id_cita = ' . $tabla_citas_estado . '.id_cita WHERE estado_cita IN ("No Show Up","Canceled","Deleted") AND cita_fecha = CURDATE() AND citas.id_paciente !="MS2";');
                                $programadas = queryOne('SELECT COUNT(' . $tabla_citas . '.id_cita) as total_prog FROM ' . $tabla_citas . ' INNER JOIN ' . $tabla_citas_estado . ' ON ' . $tabla_citas . '.id_cita = ' . $tabla_citas_estado . '.id_cita WHERE estado_cita = "Scheduled" and cita_fecha = CURDATE() AND citas.id_paciente !="MS2";');
                                ?>
                                <div class="no-gutters row">
                                    <div class="col-md-4">
                                        <div class="widget-content">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-right ml-0 mr-3">
                                                    <div class="widget-numbers text-success"><?php echo $programadas['total_prog']; ?></div>
                                                </div>
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Appointments</div>
                                                    <div class="widget-subheading">Scheduled For Today Remaining</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="widget-content">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-right ml-0 mr-3">
                                                    <div class="widget-numbers text-warning"><?php echo $finalizadas['total_fin']; ?></div>
                                                </div>
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Appointments</div>
                                                    <div class="widget-subheading">Finished Today</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="widget-content">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-right ml-0 mr-3">
                                                    <div class="widget-numbers text-danger"><?php echo $canceladas['total_canc']; ?></div>
                                                </div>
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Appointments</div>
                                                    <div class="widget-subheading">Canceled or No Show Up Today</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider mt-0" style="margin-bottom: 30px;"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="main-card mb-3 card">
                                <div class="card-header">Today Appointments <?php echo date('m-d-Y'); ?></div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="display align-middle mb-0 table table-borderless table-striped table-hover" id="appointments">
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
                <?php include 'footer.php'; ?>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>

    <!-- DATATABLE -->
    <script src="assets/dataTable/jquery.dataTables.min.js"></script>
    <script src="assets/dataTable/dataTables.bootstrap4.min.js"></script>

    <script src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
          
            
            /* $('#appointments').DataTable({
                //"responsive":true,
                "order": [
                    [0, "desc"]
                ],
                "serverSide": true,
                "ajax": {
                    "method": "POST",
                    "url": "index/load-appointments.php"
                },
                "bProcessing": true,
                "bPaginate": true,
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10,
                "aoColumns": [{
                        "mData": "idcita"
                    },
                    {
                        "mData": "user"
                    },
                    {
                        "mData": "clinica"
                    },
                    {
                        "mData": "paciente"
                    },
                    {
                        "mData": "contacto"
                    },
                    {
                        "mData": "reason"
                    },
                    {
                        "mData": "fecha"
                    },
                    {
                        "mData": "hora"
                    },
                    {
                        "mData": "estado"
                    },
                    {
                        "mData": "lab"
                    }
                ],
                columnDefs: [{
                        className: 'text-left',
                        targets: [3, 5]
                    },
                    {
                        className: 'text-center',
                        targets: [0, 1, 2, 4, 6, 7, 8, 9]
                    },
                ],
            }); */

           
        });
    </script>
</body>

</html>