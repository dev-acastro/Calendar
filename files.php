<?php
include 'include/funciones.php';
?>

<!doctype html>
<html lang="en">

<head>
    <title>PPS | Files Management</title>
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
                        <form id="configform" action="calendar/apiData.php" method="POST">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <h5 class="card-title">PATIENT INFO</h5>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="position-relative row form-group"><label for="title"
                                                                                                 class="col-sm-3 col-form-label"><b>Title <span
                                                                class="text-danger">*</span></b></label>
                                                <div class="col-sm-9">
                                                    <input input class="form-control" type="text" id="title" name="title" required>
                                                </div>
                                            </div>
                                            <div class="position-relative row form-group"><label for="patientid"
                                                                                                 class="col-sm-3 col-form-label"><b>ID de Paciente <span
                                                                class="text-danger">*</span></b></label>
                                                <div class="col-sm-9">
                                                    <input class="form-control" type="text" id="patientid" name="paciente" required>
                                                </div>
                                            </div>

                                            <div class="position-relative row form-group"><label for="file"
                                                                                                 class="col-sm-3 col-form-label"><b>File</b></label>
                                                <div class="col-sm-9">
                                                    <input class="form-control" type="file" id="inputFile" name="file" onchange="convertToBase64()">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <br>


                                    <br>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-primary" id="addEntry">Save Changes</button>
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
<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/extra/bindings/inputmask.binding.js"></script>

<script type="text/javascript">
    function convertToBase64() {
        //Read File
        var selectedFile = document.getElementById("inputFile").files;
        //Check File is not Empty
        if (selectedFile.length > 0) {
            // Select the very first file from list
            var fileToLoad = selectedFile[0];
            // FileReader function for read the file.
            var fileReader = new FileReader();
            var base64;
            // Onload of file read the file content
            fileReader.onload = function(fileLoadedEvent) {
                base64 = fileLoadedEvent.target.result;
                // Print data in console
               // console.log(base64);
            };
            // Convert data to base64
           fileconverted = fileReader.readAsDataURL(fileToLoad);
           console.log(fileconverted);
        }
    }
</script>


</body>

</html>