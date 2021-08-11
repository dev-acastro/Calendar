<?php 
    include 'include/funciones.php';
?>

<!doctype html>
<html lang="en">

<head>
    <title>PPS | Patient List</title>
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
        <?php  include 'navbar.php';?>

        <div class="app-main">
            <?php include 'sidebar.php'; ?>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-user icon-gradient bg-happy-itmeo">
                                    </i>
                                </div>
                                <div>Patients
                                    <div class="page-title-subheading">Registered Patients</div>
                                </div>
                            </div>
                        </div>
                    </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-card mb-3 card">
                                        <div class="card-header">Registered Patients</div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table
                                                    class="display align-middle mb-0 table table-borderless table-striped table-hover"
                                                    id="example">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Id</th>
                                                            <th class="text-center">Patient</th>
                                                            <th class="text-center">Clinic</th>
                                                            <th class="text-center">Contact</th>
                                                            <th class="text-center">DOB</th>
                                                            <th class="text-center">Language</th>
                                                            <th class="text-center">State</th>
                                                            <th class="text-center">Account</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
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
    
    <script>
        $(document).ready(function () {

            $('#example').DataTable({
                    //"responsive":true,
                    "order": [
                        [0, "desc"]
                    ],
                    "serverSide": true,
                    "ajax":{
                        "method": "POST",
                        "url": "patients/load-patients.php"
                    },
                    "bProcessing": true,
                    "bPaginate":true,
                    "sPaginationType":"full_numbers", 
                    "iDisplayLength": 10,
                    "aoColumns":[
                        {"mData": "id"},
                        {"mData": "patient"},
                        {"mData": "clinic"},
                        {"mData": "contact"},
                        {"mData": "dob"},
                        {"mData": "language"},
                        {"mData": "state"},
                        {"mData": "action"}
                    ],columnDefs: [
                        { className: 'text-left', targets: [1] },
                        { className: 'text-center', targets: [0,2,3,4,5,6,7] },
                    ],
                    //"iDisplayLength": -1,

                });


            //Ver account
            $(document).on("click", ".account", function () {
                opcion = 2; //editar
                fila = $(this).closest("tr");
                user_id = $.trim(fila.find('td:eq(0)').text()); //capturo el ID
                window.open('pat_account.php?id=' + user_id, '_blank');
            });

        });
    </script>
</body>

</html>