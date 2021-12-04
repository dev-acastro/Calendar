$(document).ready(function () {

    
    //=========================== SEARCH PATIENT =========================
    function getPatientStatus(chartid){
        $.ajax({
            
            type: "POST",
            dataType : 'json',
            url: "calltrackerentry/getPatientStatus.php",
            data:"idPaciente="+chartid,
            })
            .done(function(data){
                if(data.num==1){
                    $('#alertWarning').show();
                    $('#alertDanger').hide();
                    $("#labelAlertWarning").text("Warning: This patient has missed ("+data.num+") times his appointment");
                }else if(data.num>=2){
                    $('#alertWarning').hide();
                    $('#alertDanger').show();
                    $("#labelAlertDanger").text("Danger: This patient has missed (2) or more times his appointment. The patient must pay a fee (in advance) to confirm the appointment");
                }else{
                     $('#alertWarning').hide();
                    $('#alertDanger').hide();
                }
                console.log(data.num);
            })
            .fail(function(data){
                return {};
        });
    }
    //getPatientStatus("MS5986")

    $("#si_chart").autocomplete({
        source: 'calltrackerentry/searchpat_bychart.php',
        select: function (event, ui) {
            getPatientStatus(ui.item.id);
            $('#si_chart').val(ui.item.id + ': ' + ui.item.name + ' ' + ui.item.last + ' - ' + ui.item.phone + ' - ' + ui.item.dobtoshow);
            /* $('#si_chart').val(ui.item.id); */
            $('#chartid').val(ui.item.id);
            $('#name').val(ui.item.name);
            $('#lastname').val(ui.item.last);
            $('#contact').val(ui.item.phone);
            $('#dob').val(ui.item.dob);

            if(ui.item.lang == "Spanish"){
                $("#pat_language option[value='Spanish']").attr("selected",true);
            }else if(ui.item.lang == "English"){
                $("#pat_language option[value='Spanish']").attr("selected",true);
            }else{
                $("#pat_language option[value='nonSelected']").attr("selected",true);
            }

            if(ui.item.genre == "F"){
                $("#pat_genre option[value='F']").attr("selected",true);
            }else if(ui.item.genre == "M"){
                $("#pat_genre option[value='M']").attr("selected",true);
            }else{
                $("#pat_genre option[value='nonSelected']").attr("selected",true);
            }

            if(ui.item.emp == 0){
                $("#pat_have_insurance option[value='no']").attr("selected",true);
                $("#pat_have_insurance option[value='yes']").attr("selected",false);
                $('#ins_msg').css('display', 'none');
                $('#ins_name').text(ui.item.ins);
            }else if(ui.item.emp != 0){
                $("#pat_have_insurance option[value='yes']").attr("selected",true);
                $("#pat_have_insurance option[value='no']").attr("selected",false);
                $('#ins_msg').css('display', 'block');
                $('#ins_name').text(ui.item.ins);
            }

            $('#new').prop('checked', false);
            $('#chk_needform').prop('checked', false);
            $('#current').prop('checked', true);

            $('#app_channel').val(1);
            $('#app_referal_div').css('display', 'block');
            var val = 1;

            $.ajax({
                type: "POST",
                url: 'calltrackerentry/loadReferal.php',
                data: 'id_channel=' + val,
                success: function (resp) {
                    $('#app_referal').html(resp);
                    $('#app_referal').val(45);
                }
            });

            $.ajax({
                type: "POST",
                url: 'calltrackerentry/getPatNoShow.php',
                data: 'chartid=' + ui.item.id,
                success: function (resp) {
                    console.log(resp);
                    $("#alert_box").html(resp);
                }
            });

            $.ajax({
                type: "POST",
                url: 'calltrackerentry/getAppointmentsByPatient.php',
                data: 'patid=' + ui.item.id,
                success: function (resp) {
                    $('#resumeTableAppByPat').css('display', 'block');
                    $("#appointmentsByPat_data").html(resp);
                }
            });

            return false;
        }
    });

    //Autocomplete Current Patient 
    $('[name="search_pat[]"]').change(function (){
        var search_type = $('[name="search_pat[]"]:checked').val();
        if(search_type == "search_chartid"){
            $("#si_chart").autocomplete({
                source: 'calltrackerentry/searchpat_bychart.php',
                select: function (event, ui) {
                    getPatientStatus(ui.item.id);
                    $('#si_chart').val(ui.item.id + ': ' + ui.item.name + ' ' + ui.item.last + ' - ' + ui.item.phone + ' - ' + ui.item.dobtoshow);
                    /* $('#si_chart').val(ui.item.id); */
                    $('#chartid').val(ui.item.id);
                    $('#name').val(ui.item.name);
                    $('#lastname').val(ui.item.last);
                    $('#contact').val(ui.item.phone);
                    $('#dob').val(ui.item.dob);

                    if(ui.item.lang == "Spanish"){
                        $("#pat_language option[value='Spanish']").attr("selected",true);
                    }else if(ui.item.lang == "English"){
                        $("#pat_language option[value='Spanish']").attr("selected",true);
                    }else{
                        $("#pat_language option[value='nonSelected']").attr("selected",true);
                    }
        
                    if(ui.item.genre == "F"){
                        $("#pat_genre option[value='F']").attr("selected",true);
                    }else if(ui.item.genre == "M"){
                        $("#pat_genre option[value='M']").attr("selected",true);
                    }else{
                        $("#pat_genre option[value='nonSelected']").attr("selected",true);
                    }
        
                    if(ui.item.emp == 0){
                        $("#pat_have_insurance option[value='no']").attr("selected",true);
                        $("#pat_have_insurance option[value='yes']").attr("selected",false);
                        $('#ins_msg').css('display', 'none');
                        $('#ins_name').text(ui.item.ins);
                    }else if(ui.item.emp != 0){
                        $("#pat_have_insurance option[value='yes']").attr("selected",true);
                        $("#pat_have_insurance option[value='no']").attr("selected",false);
                        $('#ins_msg').css('display', 'block');
                        $('#ins_name').text(ui.item.ins);
                    }

                    $('#new').prop('checked', false);
                    $('#current').prop('checked', true);
                    $('#chk_needform').prop('checked', false);

                    $.ajax({
                        type: "POST",
                        url: 'calltrackerentry/getPatNoShow.php',
                        data: 'chartid=' + ui.item.id,
                        success: function (resp) {
                            $("#alert_box").html(resp);
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: 'calltrackerentry/getAppointmentsByPatient.php',
                        data: 'patid=' + ui.item.id,
                        success: function (resp) {
                            $('#resumeTableAppByPat').css('display', 'block');
                            $("#appointmentsByPat_data").html(resp);
                        }
                    });
                    return false;
                }
            });
        }
        else if(search_type == "search_name"){
            $("#si_name").autocomplete({
                source: 'calltrackerentry/searchpat_byname.php',
                select: function (event, ui) {
                    $('#si_name').val(ui.item.id + ': ' + ui.item.name + ' ' + ui.item.last + ' - ' + ui.item.phone + ' - ' + ui.item.dobtoshow);
                    $('#chartid').val(ui.item.id);
                    $('#name').val(ui.item.name);
                    $('#lastname').val(ui.item.last);
                    $('#contact').val(ui.item.phone);
                    $('#dob').val(ui.item.dob);

                    if(ui.item.lang == "Spanish"){
                        $("#pat_language option[value='Spanish']").attr("selected",true);
                    }else if(ui.item.lang == "English"){
                        $("#pat_language option[value='Spanish']").attr("selected",true);
                    }else{
                        $("#pat_language option[value='nonSelected']").attr("selected",true);
                    }
        
                    if(ui.item.genre == "F"){
                        $("#pat_genre option[value='F']").attr("selected",true);
                    }else if(ui.item.genre == "M"){
                        $("#pat_genre option[value='M']").attr("selected",true);
                    }else{
                        $("#pat_genre option[value='nonSelected']").attr("selected",true);
                    }
        
                    if(ui.item.emp == 0){
                        $("#pat_have_insurance option[value='no']").attr("selected",true);
                        $("#pat_have_insurance option[value='yes']").attr("selected",false);
                        $('#ins_msg').css('display', 'none');
                        $('#ins_name').text(ui.item.ins);
                    }else if(ui.item.emp != 0){
                        $("#pat_have_insurance option[value='yes']").attr("selected",true);
                        $("#pat_have_insurance option[value='no']").attr("selected",false);
                        $('#ins_msg').css('display', 'block');
                        $('#ins_name').text(ui.item.ins);
                    }

                    $('#new').prop('checked', false);
                    $('#current').prop('checked', true);
                    $('#chk_needform').prop('checked', false);
                    
                    $('#app_channel').val(1);
                    $('#app_referal_div').css('display', 'block');
                    var val = 1;

                    $.ajax({
                        type: "POST",
                        url: 'calltrackerentry/loadReferal.php',
                        data: 'id_channel=' + val,
                        success: function (resp) {
                            $('#app_referal').html(resp);
                            $('#app_referal').val(45);
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: 'calltrackerentry/getPatNoShow.php',
                        data: 'chartid=' + ui.item.id,
                        success: function (resp) {
                            console.log(resp);
                            $("#alert_box").html(resp);
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: 'calltrackerentry/getAppointmentsByPatient.php',
                        data: 'patid=' + ui.item.id,
                        success: function (resp) {
                            $('#resumeTableAppByPat').css('display', 'block');
                            $("#appointmentsByPat_data").html(resp);
                        }
                    });
                    return false;
                }
            });
        }
        else if(search_type == "search_phone"){
            $("#si_phone").autocomplete({
                source: 'calltrackerentry/searchpat_byphone.php',
                select: function (event, ui) {
                    $('#si_phone').val(ui.item.id + ': ' + ui.item.name + ' ' + ui.item.last + ' - ' + ui.item.phone + ' - ' + ui.item.dobtoshow);
                    $('#chartid').val(ui.item.id);
                    $('#name').val(ui.item.name);
                    $('#lastname').val(ui.item.last);
                    $('#contact').val(ui.item.phone);
                    $('#dob').val(ui.item.dob);

                    if(ui.item.lang == "Spanish"){
                        $("#pat_language option[value='Spanish']").attr("selected",true);
                    }else if(ui.item.lang == "English"){
                        $("#pat_language option[value='Spanish']").attr("selected",true);
                    }else{
                        $("#pat_language option[value='nonSelected']").attr("selected",true);
                    }
        
                    if(ui.item.genre == "F"){
                        $("#pat_genre option[value='F']").attr("selected",true);
                    }else if(ui.item.genre == "M"){
                        $("#pat_genre option[value='M']").attr("selected",true);
                    }else{
                        $("#pat_genre option[value='nonSelected']").attr("selected",true);
                    }
        
                    if(ui.item.emp == 0){
                        $("#pat_have_insurance option[value='no']").attr("selected",true);
                        $("#pat_have_insurance option[value='yes']").attr("selected",false);
                        $('#ins_msg').css('display', 'none');
                        $('#ins_name').text(ui.item.ins);
                    }else if(ui.item.emp != 0){
                        $("#pat_have_insurance option[value='yes']").attr("selected",true);
                        $("#pat_have_insurance option[value='no']").attr("selected",false);
                        $('#ins_msg').css('display', 'block');
                        $('#ins_name').text(ui.item.ins);
                    }

                    $('#new').prop('checked', false);
                    $('#current').prop('checked', true);
                    $('#chk_needform').prop('checked', false);

                    $('#app_channel').val(1);
                    $('#app_referal_div').css('display', 'block');
                    var val = 1;

                    $.ajax({
                        type: "POST",
                        url: 'calltrackerentry/loadReferal.php',
                        data: 'id_channel=' + val,
                        success: function (resp) {
                            $('#app_referal').html(resp);
                            $('#app_referal').val(45);
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: 'calltrackerentry/getPatNoShow.php',
                        data: 'chartid=' + ui.item.id,
                        success: function (resp) {
                            console.log(resp);
                            $("#alert_box").html(resp);
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: 'calltrackerentry/getAppointmentsByPatient.php',
                        data: 'patid=' + ui.item.id,
                        success: function (resp) {
                            $('#resumeTableAppByPat').css('display', 'block');
                            $("#appointmentsByPat_data").html(resp);
                        }
                    });
                    return false;
                }
            });
        }
        else if(search_type == "search_dob"){
            $("#si_dob").autocomplete({
                source: 'calltrackerentry/searchpat_dob.php',
                select: function (event, ui) {
                    $('#si_dob_value').val(ui.item.id + ': ' + ui.item.name + ' ' + ui.item.last + ' - ' + ui.item.phone + ' - ' + ui.item.dobtoshow);
                    $('#chartid').val(ui.item.id);
                    $('#name').val(ui.item.name);
                    $('#lastname').val(ui.item.last);
                    $('#contact').val(ui.item.phone);
                    $('#dob').val(ui.item.dob);

                    if(ui.item.lang == "Spanish"){
                        $("#pat_language option[value='Spanish']").attr("selected",true);
                    }else if(ui.item.lang == "English"){
                        $("#pat_language option[value='Spanish']").attr("selected",true);
                    }else{
                        $("#pat_language option[value='nonSelected']").attr("selected",true);
                    }
        
                    if(ui.item.genre == "F"){
                        $("#pat_genre option[value='F']").attr("selected",true);
                    }else if(ui.item.genre == "M"){
                        $("#pat_genre option[value='M']").attr("selected",true);
                    }else{
                        $("#pat_genre option[value='nonSelected']").attr("selected",true);
                    }
        
                    if(ui.item.emp == 0){
                        $("#pat_have_insurance option[value='no']").attr("selected",true);
                        $("#pat_have_insurance option[value='yes']").attr("selected",false);
                        $('#ins_msg').css('display', 'none');
                        $('#ins_name').text(ui.item.ins);
                    }else if(ui.item.emp != 0){
                        $("#pat_have_insurance option[value='yes']").attr("selected",true);
                        $("#pat_have_insurance option[value='no']").attr("selected",false);
                        $('#ins_msg').css('display', 'block');
                        $('#ins_name').text(ui.item.ins);
                    }

                    $('#new').prop('checked', false);
                    $('#current').prop('checked', true);
                    $('#chk_needform').prop('checked', false);

                    $('#app_channel').val(1);
                    $('#app_referal_div').css('display', 'block');
                    var val = 1;
        
                    $.ajax({
                        type: "POST",
                        url: 'calltrackerentry/loadReferal.php',
                        data: 'id_channel=' + val,
                        success: function (resp) {
                            $('#app_referal').html(resp);
                            $('#app_referal').val(45);
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: 'calltrackerentry/getPatNoShow.php',
                        data: 'chartid=' + ui.item.id,
                        success: function (resp) {
                            console.log(resp);
                            $("#alert_box").html(resp);
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: 'calltrackerentry/getAppointmentsByPatient.php',
                        data: 'patid=' + ui.item.id,
                        success: function (resp) {
                            $('#resumeTableAppByPat').css('display', 'block');
                            $("#appointmentsByPat_data").html(resp);
                        }
                    });
                    return false;
                }
            });
        }
    });
    
    

    /* $('#current_time_app').timepicker({
        timeFormat: 'hh:mm p',
        interval: 30,
        minTime: new Date(0, 0, 0, 9, 0, 0),
        maxTime: '7:00pm',
        defaultTime: '09:00',
        startTime: '09:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    }); */

    /* function ConvertTimeformat(format, str) {
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
    } */

    //================================== REASON AND DURATION APP ============================

    $("#app_reason").autocomplete({

        source: 'calltrackerentry/loadReasons.php',
        select: function (event, ui) {
            $('#app_reason').val(ui.item.value);
            $('#app_duration').val(ui.item.duration);
            return false;
        }
    });

    //================================== DATE AND TIME APP ============================
    $('[name="pat_type[]"]').change(function (){
        var patient_type = $('[name="pat_type[]"]:checked').val();
        var hizo_cita = $('[name="do_app[]"]:checked').val();
        if(patient_type == "New"){
            if(hizo_cita == "yes"){
                $('#chk_needform').prop('checked', true);
            }
            else if(hizo_cita == "no"){
                $('#chk_needform').prop('checked', false);
            }
        }
        else if(patient_type == "Current"){
            $('#chk_needform').prop('checked', false);
            $('#app_referal_div').css('display', 'block');

            $('#app_channel').val(1);
            var val = 1;

            $.ajax({
                type: "POST",
                url: 'calltrackerentry/loadReferal.php',
                data: 'id_channel=' + val,
                success: function (resp) {
                    $('#app_referal').html(resp);
                    $('#app_referal').val(45);
                }
            });
        }
    });

    //==================== LLENANDO ARREGLO DE HORAS DISPONIBLES ====================

    //CREAR ID DE PACIENTE
    $("#select_clinic").change(function () {
        var availableDates = [];
           // $('#app_date').datepicker('destroy');
            //$('#app_time').empty();
            /* $('#app_time').timepicker('destroy'); */

        var clinicName = $('#select_clinic option:selected').html();
        var clinicId = $('#select_clinic').val();
        var patient_type = $('[name="pat_type[]"]:checked').val();

        if (patient_type == "New") {

            $.ajax({
                type: "POST",
                url: 'calltrackerentry/loadPatientId.php',
                data: 'id_clinica=' + clinicId,
                success: function (resp) {
                   console.log(resp);
                    var letraI = clinicName.charAt(0);
                    var letraF = clinicName.charAt(clinicName.length - 1);
                    var id_paciente = letraI.toUpperCase() + letraF.toUpperCase();
                    var id_paciente_eagle = letraI.toUpperCase();

                    $("#chartid").val($.trim(id_paciente + resp));
                    $("#chartid_eagle").val($.trim(id_paciente_eagle + resp));
                    console.log(id_paciente + resp);
                }
            });

            $.ajax({
                type: "POST",
                url: 'calltrackerentry/loadAvailableDate.php',
                data: 'id_clinica=' + clinicId,
                dataType: 'JSON',
                success: function (resp) {
    
                    for (var d in resp) {
                        availableDates.push(resp[d]);
                    }
    
                    $('#app_date').datepicker({
                        format: 'mm/dd/yyyy',
                        todayHighlight: true,
                        daysOfWeekDisabled: [0],
                        daysOfWeekHighlighted: [1, 2, 3, 4, 5, 6],
                        beforeShowDay: function (dt) {
                            mdy = (('' + (dt.getMonth() + 1)).length < 2 ? '0' : '') + (dt.getMonth() + 1) + "-" + (('' + (dt.getDate())).length < 2 ? '0' : '') + dt.getDate() + "-" + dt.getFullYear();
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
    
            /* $('#app_date').change(function (e) {
                $('#app_time').empty();
                const data = {
                    id_clinica: $('#select_clinic').val(),
                    date: $('#app_date').val()
                };
    
                $.ajax({
                    type: "POST",
                    url: 'calltrackerentry/loadAvailableHours.php',
                    data: data,
                    dataType: 'JSON',
                    success: function (resp) {
                        $(resp).each(function(v){
                            $('#app_time').append('<option value="' + (resp[v].time) + '">' + (resp[v].time) + '</option>');
                        });
                    }
    
                });
            }); */
        }

        else if (patient_type == "Current") {
            $.ajax({
                type: "POST",
                url: 'calltrackerentry/loadAvailableDate.php',
                data: 'id_clinica=' + clinicId,
                dataType: 'JSON',
                success: function (resp) {
    
                    for (var d in resp) {
                        availableDates.push(resp[d]);
                    }
    
                    $('#app_date').datepicker({
                        format: 'mm/dd/yyyy',
                        todayHighlight: true,
                        daysOfWeekDisabled: [0],
                        daysOfWeekHighlighted: [1, 2, 3, 4, 5, 6],
                        beforeShowDay: function (dt) {
                            mdy = (('' + (dt.getMonth() + 1)).length < 2 ? '0' : '') + (dt.getMonth() + 1) + "-" + (('' + (dt.getDate())).length < 2 ? '0' : '') + dt.getDate() + "-" + dt.getFullYear();
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
    
            /* $('#app_date').change(function (e) {
                $('#app_time').empty();
                const data = {
                    id_clinica: $('#select_clinic').val(),
                    date: $('#app_date').val()
                };
    
                $.ajax({
                    type: "POST",
                    url: 'calltrackerentry/loadAvailableHours.php',
                    data: data,
                    dataType: 'JSON',
                    success: function (resp) {
                        $(resp).each(function(v){ // indice, valor
                            $('#app_time').append('<option value="' + (resp[v].time) + '">' + (resp[v].time) + '</option>');
                        });
                    }
    
                });
            }); */
        }
    });

    //Define time to Appointment
    $('#app_date').change(function (e) {
        $('#app_time').empty();
        const data = {
            id_clinica: $('#select_clinic').val(),
            date: $('#app_date').val()
        };

        $.ajax({
            type: "POST",
            url: 'calltrackerentry/loadAvailableHours.php',
            data: data,
            dataType: 'JSON',
            success: function (resp) {
                $(resp).each(function(v){ // valor
                    $('#app_time').append('<option value="' + (resp[v].time) + '">' + (resp[v].time) + '</option>');
                });
            }

        });
    });


    //========================================= MOSTRAR O NO DIVS =========================================

    $('[name="do_app[]"]').click(function () {
        var actual_value_chartid = $('#chartid').val();
        var hizo_cita = $('[name="do_app[]"]:checked').val();
        if (hizo_cita == "yes") {
            $('#app_div').css('display', 'block');
            $('#noapp_div').css('display', 'none');

            $('#chartid').val(actual_value_chartid);
        } else if (hizo_cita == "no") {
            $('#app_div').css('display', 'none');
            $('#noapp_div').css('display', 'block');
            $('#chk_needform').prop('checked', false);

            $('#chartid').val('');
        }
    });

    $('#reset_chart').click(function(){
        $('#si_chart').val('');
        $('#alert_box').html('');
        $('#configform')[0].reset();
        $('#app_referal_div').css('display', 'none');
        $('#ins_msg').css('display', 'none');
        $('#resumeTableAppByPat').css('display', 'none');
    });

    $('#reset_name').click(function(){
        $('#si_name').val('');
        $('#alert_box').html('');
        $('#configform')[0].reset();
        $('#app_referal_div').css('display', 'none');
        $('#ins_msg').css('display', 'none');
        $('#resumeTableAppByPat').css('display', 'none');
    });
    
    $('#reset_phone').click(function(){
        $('#si_phone').val('');
        $('#alert_box').html('');
        $('#configform')[0].reset();
        $('#app_referal_div').css('display', 'none');
        $('#ins_msg').css('display', 'none');
        $('#resumeTableAppByPat').css('display', 'none');
    });
    $('#reset_dob').click(function(){
        $('#si_dob').val('');
        $('#alert_box').html('');
        $('#configform')[0].reset();
        $('#app_referal_div').css('display', 'none');
        $('#ins_msg').css('display', 'none');
        $('#resumeTableAppByPat').css('display', 'none');
    });

    $('[name="search_pat[]"]').click(function () {
        $('#alert_box').html('');
        var search_by = $('[name="search_pat[]"]:checked').val();
        if (search_by == "search_chartid") {
            $('#div_chart').css('display', 'block');
            $('#div_name').css('display', 'none');
            $('#div_phone').css('display', 'none');
            $('#div_date').css('display', 'none');
        } else if (search_by == "search_name") {
            $('#div_chart').css('display', 'none');
            $('#div_name').css('display', 'block');
            $('#div_phone').css('display', 'none');
            $('#div_date').css('display', 'none');
        } else if (search_by == "search_phone") {
            $('#div_chart').css('display', 'none');
            $('#div_name').css('display', 'none');
            $('#div_phone').css('display', 'block');
            $('#div_date').css('display', 'none');
        } else if (search_by == "search_dob") {
            $('#div_chart').css('display', 'none');
            $('#div_name').css('display', 'none');
            $('#div_phone').css('display', 'none');
            $('#div_date').css('display', 'block');

            /*swal.fire({
                title: "Under Construction",
                html: "The DOB search type is under construction. <br><b class='text-danger'>Please Try with other options</b>",
                icon: "info"
            })*/
        }
    });

    $("#pat_have_insurance").change(function () {
        var val = $('#pat_have_insurance').val();

        if (val == 'yes') {
            $('#insurancetype').css('display', 'block');
        } else if (val == 'no') {
            $('#insurancetype').css('display', 'none');
        }
    });

    /* $("#pat_type_insurance").change(function () {
        var val = $('#pat_type_insurance').val();

        if (val == 'Policy Holder') {
            $('#pat_policyholder').css('display', 'block');

            var clinicName = $('#select_clinic option:selected').html();
            var clinicId = $('#select_clinic').val();
            $.ajax({
                type: "POST",
                url: 'calltrackerentry/loadPatientId2.php',
                data: 'id_clinica=' + clinicId,
                success: function (resp) {

                    var letraI = clinicName.charAt(0);
                    var letraF = clinicName.charAt(clinicName.length - 1);
                    var id_paciente = letraI.toUpperCase() + letraF.toUpperCase();
                    var id_paciente_eagle = letraI.toUpperCase();

                    $("#id_paciente_ph").val(id_paciente + resp);
                    $("#id_paciente_eagle_ph").val(id_paciente_eagle + resp);

                }
            });
        } else if (val == 'Self') {
            $('#pat_policyholder').css('display', 'none');
        }

    }); */

    $("#app_channel").change(function () {
        var val = $('#app_channel :selected').val();
        var tipo = $('[name="pat_type[]"]:checked').val();
        $('#app_referal_div').css('display', 'block');

        $.ajax({
            type: "POST",
            url: 'calltrackerentry/loadReferal.php',
            data: 'id_channel=' + val,
            success: function (resp) {
                $('#app_referal').html(resp);

                var ref = $('#app_referal :selected').html();
                if (ref == "Existing Patient") {
                    $('#currentpatient_div').css('display', 'block');
                } else {
                    $('#currentpatient_div').css('display', 'none');
                }
            }
        });

    });

    $('#app_referal').change(function () {
        var ref = $('#app_referal :selected').html();
        if (ref == "Existing Patient") {
            $('#currentpatient_div').css('display', 'block');
        } else {
            $('#currentpatient_div').css('display', 'none');
        }
    });


    //Validar que se seleccione la clinica para mostrar fechas disponibles
    $('#app_date').click(function (){
        var clinic =  $('#select_clinic option:selected').text();
        if(clinic == "--- Select a Clinic ---"){
            swal.fire({
                title: "Select a Clinic",
                html: "You must select a clinic to define a date for the appointment. <br><b class='text-danger'>Please Select a clinic and Try again</b>",
                icon: "warning"
            })
        }
    });

    //Si el provider es para chat, aparece input
    $('#app_provider').click(function (){
        var provider = $('#app_provider').val();

        if(provider == "FBDebbie" || provider == "FBTopDental" || provider == "IGDebbie" || provider == "IGTopDental"){
            $('#chatName').css('display', 'block');
            $('#campaign').css('display', 'block');
        }else if(provider == "Call Center"){
            $('#chatName').css('display', 'none');
            $('#campaign').css('display', 'block');
        }else{
            $('#chatName').css('display', 'none');
            $('#campaign').css('display', 'none');
        }

        /* if(provider == "Call Center"){
            $('#campaign').css('display', 'block');
        }else{
            $('#campaign').css('display', 'none');
        } */
    });


    //==================================== ADD ENTRY ===============================================

    //guardar nueva entrada de call tracker
    $("#addEntry").click(function (e) {
        e.preventDefault();
        
        var tipo = $('[name="pat_type[]"]:checked').val();
        var insurance = $('#pat_have_insurance').val();
        var tipo_insurance = $('#pat_type_insurance').val();
        var color = '';

        //var time24 = ConvertTimeformat("00:00", $('#app_time').val());

        //Define Color si es Consulta Virtual
        if ($('#app_reason').val() == "Virtual Consultation") {
            color = "#ab82ba";
        } else {
            color = "#16aaff";
        }

        var hc = $('[name="do_app[]"]:checked').val();

        const postData = {
            id_paciente: $('#chartid').val(),
            id_paciente_eagle: $('#chartid_eagle').val(),
            id_clinica: $('#select_clinic').val(),
            paciente_nombres: $('#name').val(),
            paciente_apellidos: $('#lastname').val(),
            paciente_fechanac: $('#dob').val(),
            paciente_contacto: $('#contact').val(),
            paciente_genero: $('#pat_genre').val(),
            paciente_idioma: $('#pat_language').val(),
            paciente_tiene_seguro: insurance,
            paciente_ciudad: $('#city').val(),
            paciente_estado: $('#state').val(),

            id_channel: $('#app_channel').val(),
            id_referal: $('#app_referal').val(),
            //id_campaign: $('#campaignR').val(),
            tipo_paciente: tipo,

            id_reason: $('#app_reason').val(),
            cita_fecha: $('#app_date').val(),
            cita_hora: $('#app_time').val(),
            cita_duracion: $('#app_duration').val(),
            cita_notas: $('#app_notes').val(),
            cita_provider: $('#app_provider').val(),
            cita_chat: $('#chat_name').val(),
            cita_campaign: $('#app_campaign').val(),

            tipo_seguro: $('#pat_type_insurance').val(),
            current_patient_referal_id: $('#cur_pat_id').val(),

            id_paciente_ph: $('#ph_chart').val(),
            id_paciente_eagle_ph: $('#ph_chart_eagle').val(),
            paciente_nombres_ph: $('#ph_name').val(),
            paciente_apellidos_ph: $('#ph_lastname').val(),
            paciente_relacion_ph: $('#ph_relationship').val(),
            paciente_contacto_ph: $('#ph_phone').val(),
            paciente_birth_ph: $('#ph_dob').val(),
            operatory: $('#operatory').val(),
            api_id: '',
            color: color,
            call_hizo_cita: hc,
            

            needform: $('#chk_needform').val()
        }

        const dataWA = {
            id_paciente: $('#chartid').val(),
            id_clinica: $('#select_clinic').val(),
            paciente_nombres: $('#name').val(),
            paciente_apellidos: $('#lastname').val(),
            paciente_fechanac: $('#dob').val(),
            paciente_contacto: $('#contact').val(),

            id_channel: $('#app_channel').val(),
            id_referal: $('#app_referal').val(),
            tipo_paciente: tipo,
            call_notes: $('#noapp_notes').val(),

            current_patient_referal_id: $('#cur_pat_id').val()
        }

        if (tipo == 'New' && hc == "yes") {
            if ($('#name').val() === '') {
                swal.fire({
                    title: "Error",
                    text: "Please, input a Patient Name",
                    icon: "error"
                })
            } else if ($('#lastname').val() === '') {
                swal.fire({
                    title: "Error",
                    text: "Please, input a Patient LastName",
                    icon: "error"
                })
            } else if ($('#dob').val() === '') {
                swal.fire({
                    title: "Error",
                    text: "Please, input a date of birth",
                    icon: "error"
                })
            } else if ($('#contact').val() === '') {
                swal.fire({
                    title: "Error",
                    text: "Please, input a contact phone number",
                    icon: "error"
                })
            } else if ($('#chartid').val() === '') {
                swal.fire({
                    title: "Error",
                    text: "Please, select a clinic to generate the Patient Chart",
                    icon: "error"
                })
            } else if ($('#pat_genre').val() === null) {
                swal.fire({
                    title: "Error",
                    text: "Please, select a Genre",
                    icon: "error"
                })
            } else if ($('#pat_language').val() === null) {
                swal.fire({
                    title: "Error",
                    text: "Please, select a Language",
                    icon: "error"
                })
            } else if ($('#chk_needform').prop('checked') == false) {
                swal.fire({
                    title: "Error",
                    text: "Please, check NEED FORMS box",
                    icon: "error"
                })
            } else if ($('#app_reason').val() === '') {
                swal.fire({
                    title: "Error",
                    text: "Please, input a Appointment's Reason",
                    icon: "error"
                })
            } else if ($('#app_channel').val() == null) {
                swal.fire({
                    title: "Error",
                    text: "Please, select a channel and a referral",
                    icon: "error"
                })
            } else if ($('#app_provider').val() == null) {
                swal.fire({
                    title: "Error",
                    text: "Please, select an appointment provider",
                    icon: "error"
                })
            } else {
                postData.api_id = sendtoApiData(postData, true);
                $.ajax({
                    type: "POST",
                    url: 'calltrackerentry/addCallTrackerEntry.php',
                    data: postData,
                    dataType: 'JSON',
                    success: function (resp) {
                        console.info("ATENCION!!!!! SI ESTAMOS ACA")
                        console.log(resp)
                        if (resp[0]["response"] == "Success") {
                            swal.fire({
                                title: "New Call Tracker Entry",
                                html: "Has been entered successfully. <br>Patient with Chart ID <b class='text-alternate'>"+resp[0]["id_generado"]+"</b> has been created",
                                icon: "success"
                            }).then(function () {
                               // location.reload();
                               windows.location = "scheduler.php";
                            });
                        } else if (resp[0]["response"] == "Error") {
                            swal.fire({
                                title: "New Call Tracker Entry",
                                text: "Can't be entered. Please Try Again",
                                icon: "error"
                            });
                        } else {
                            swal.fire({
                                title: "New Call Tracker Entry",
                                text: resp,
                                icon: "error"
                            });
                        }
                    }
                }).done(function (data){
                    console.info("ATENCION!!!!! SI ESTAMOS ACA")
                    console.warning("DONE FUNCTION")
                    console.log(data)
                });
            }

        } else if (tipo == 'New' && hc == "no") {

            if ($('#name').val() === '' || $('#lastname').val() === '') {
                swal.fire({
                    title: "Error",
                    text: "Please, input a Patient Name and LastName",
                    icon: "error"
                })
            } else if ($('#contact').val() === '') {
                swal.fire({
                    title: "Error",
                    text: "Please, input a contact phone number",
                    icon: "error"
                })
            } else if ($('#select_clinic').val() == null) {
                swal.fire({
                    title: "Error",
                    text: "Please, select a clinic",
                    icon: "error"
                })
            } else if ($('#noapp_notes').val() == "") {
                swal.fire({
                    title: "Error",
                    text: "Please, input a notes or comments",
                    icon: "error"
                })
            } else if ($('#app_channel').val() == null) {
                swal.fire({
                    title: "Error",
                    text: "Please, select a channel and a referral",
                    icon: "error"
                })
            } else {
                postData.api_id = sendtoApiData(postData);
                $.ajax({
                    type: "POST",
                    url: 'calltrackerentry/addCallTrackerEntryWithoutApp.php',
                    data: dataWA,
                    success: function (resp) {
                        if (resp == "Success") {
                            swal.fire({
                                title: "New Call",
                                text: "Has been entered successfully",
                                icon: "success"
                            }).then(function () {
                                location.reload();
                            });
                        } else if (resp == "Error") {
                            swal.fire({
                                title: "New Call",
                                text: "Can't be entered. Please Try Again",
                                icon: "error"
                            });
                        } else {
                            swal.fire({
                                title: "New Call",
                                text: resp,
                                icon: "error"
                            });
                        }
                    }
                });
            }
        } else if (tipo == 'Current' && hc == "yes") {

            if ($('#chartid').val() === '') {
                swal.fire({
                    title: "Error",
                    text: "Please, search a patient to below",
                    icon: "error"
                })
            } else if ($('#contact').val() === '') {
                swal.fire({
                    title: "Error",
                    text: "Please, input a contact phone number",
                    icon: "error"
                })
            } else if ($('#select_clinic').val() == null) {
                swal.fire({
                    title: "Error",
                    text: "Please, select a clinic",
                    icon: "error"
                })
            } else if ($('#app_channel').val() == null) {
                swal.fire({
                    title: "Error",
                    text: "Please, select a channel and a referral",
                    icon: "error"
                })
            } /* else if($('#app_provider').val() == "FBDebbie" || $('#app_provider').val() == "FBTopDental" || $('#app_provider').val() == "IGDebbie" || $('#app_provider').val() == "IGTopDental"){
                if($('#chat_name').val() == ""){
                    swal.fire({
                        title: "Chat Name Missing",
                        text: "Please, input a Chat Name",
                        icon: "warning"
                    });
                }
            } */ else {
                postData.api_id = sendtoApiData(postData, true)
                $.ajax({
                    type: "POST",
                    url: 'calltrackerentry/addCallTrackerEntryCurrentPatient.php',
                    data: postData,
                    dataType: 'JSON',
                    success: function (resp) {
                        if (resp[0]["response"] == "Success") {
                            swal.fire({
                                title: "New Appointment",
                                html: "Has been entered successfully. <br>You have registered an appointment for the patient with Chart ID <b class='text-alternate'>"+resp[0]["id_generado"]+"</b>",
                                icon: "success"
                            }).then(function () {
                                location.reload();
                            });
                        } else if (resp[0]["response"] == "Error") {
                            swal.fire({
                                title: "New Appointment",
                                text: "Can't be entered. Please Try Again",
                                icon: "error"
                            });
                        } else {
                            swal.fire({
                                title: "New Appointment",
                                text: resp,
                                icon: "error"
                            });
                        }
                    }
                });
            }
        } else if (tipo == 'Current' && hc == "no") {
            if ($('#chartid').val() === '') {
                swal.fire({
                    title: "Error",
                    text: "Please, search a patient to below",
                    icon: "error"
                })
            } if ($('#name').val() === '' || $('#lastname').val() === '') {
                swal.fire({
                    title: "Error",
                    text: "Please, input a Patient Name and LastName",
                    icon: "error"
                })
            } else if ($('#contact').val() === '') {
                swal.fire({
                    title: "Error",
                    text: "Please, input a contact phone number",
                    icon: "error"
                })
            } else if ($('#select_clinic').val() == null) {
                swal.fire({
                    title: "Error",
                    text: "Please, select a clinic",
                    icon: "error"
                })
            } else if ($('#noapp_notes').val() == "") {
                swal.fire({
                    title: "Error",
                    text: "Please, input a notes or comments",
                    icon: "error"
                })
            } else if ($('#app_channel').val() == null) {
                swal.fire({
                    title: "Error",
                    text: "Please, select a channel and a referral",
                    icon: "error"
                })
            } else {
                postData.api_id = sendtoApiData(postData)
                $.ajax({
                    type: "POST",
                    url: 'calltrackerentry/addCallTrackerEntryWithoutApp.php',
                    data: dataWA,
                    success: function (resp) {
                        if (resp == "Success") {
                            swal.fire({
                                title: "New Call",
                                text: "Has been entered successfully",
                                icon: "success"
                            }).then(function () {
                                location.reload();
                            });
                        } else if (resp == "Error") {
                            swal.fire({
                                title: "New Call",
                                text: "Can't be entered. Please Try Again",
                                icon: "error"
                            });
                        } else {
                            swal.fire({
                                title: "New Call",
                                text: resp,
                                icon: "error"
                            });
                        }
                    }
                });
            }
        }


    });

    
});

function sendtoApiData(postData, appo = null) {
    postData.action = 'newCallEntry';
    postData.withAppo = appo
    var appointmentID = ''
     $.ajax({
        async: false,
        type: "POST",
        url: 'calendar/apiData.php',
        data: postData,
        dataType: 'JSON',
        success: function (resp) {
            appointmentID = resp
        }
    }); 
    return appointmentID
}