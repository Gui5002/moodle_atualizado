<?php
global $CFG, $DB, $USER;
?>
<script>
    function moeda(a, e, r, t) {
        let n = ""
        , h = j = 0
        , u = tamanho2 = 0
        , l = ajd2 = ""
        , o = window.Event ? t.which : t.keyCode;
        if (13 == o || 8 == o)
            return !0;
        if (n = String.fromCharCode(o),
        -1 == "0123456789".indexOf(n))
            return !1;
        for (u = a.value.length,
        h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
            ;
        for (l = ""; h < u; h++)
            -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
        if (l += n,
        0 == (u = l.length) && (a.value = ""),
        1 == u && (a.value = "0" + r + "0" + l),
        2 == u && (a.value = "0" + r + l),
        u > 2) {
            for (ajd2 = "",
            j = 0,
            h = u - 3; h >= 0; h--)
                3 == j && (ajd2 += e,
                j = 0),
                ajd2 += l.charAt(h),
                j++;
            for (a.value = "",
            tamanho2 = ajd2.length,
            h = tamanho2 - 1; h >= 0; h--)
                a.value += ajd2.charAt(h);
            a.value += r + l.substr(u - 2, u)
        }
        return !1
    }

    function goToByScroll(id) {
        id = id.replace("link", "");
        $('html,body').animate({
            scrollTop: $("#" + id).offset().top
        }, 'slow');
    }

    function saveTrainingSuggestion(){
        // let userid         = $('#userid').val();
        let pageid         = $('#pageid').val();
        let ts             = $('#ts').val();
        let eva_slc_cargo  = $('#eva_slc_cargo').val();
        let eva_slc_1      = $('#eva_slc_1').val();
        // let eva_slc_2      = $('#eva_slc_2').val();
        let eva_txtarea_1  = $('#eva_txtarea_1').val();
        // let eva_slc_comp  = $('#eva_slc_comp').val();
        // let eva_slc_realizacao  = $('#eva_slc_realizacao').val();
        let eva_txtarea_2  = $('#eva_txtarea_2').val();
        var error          = 0;
        var msg            = "";

        var slc_priority_area_legal = [];
        $.each($("input[name='eva_checkbox_1']:checked"), function(){
            slc_priority_area_legal.push($(this).val());
         });

        // var slc_technical_legal = [];
        // $.each($("input[name='eva_checkbox_2']:checked"), function(){
        //     slc_technical_legal.push($(this).val());
        // });

        var slc_modality = [];
        $.each($("input[name='eva_checkbox_3']:checked"), function(){
            slc_modality.push($(this).val());
        });

        // var slc_previsao = [];
        // $.each($("input[name='eva_checkbox_4']:checked"), function(){
        //     slc_previsao.push($(this).val());
        // });

        let eva_input_1    = $('#eva_input_1').val();
        // let eva_input_2    = $('#eva_input_2').val();
        // let eva_input_3    = $('#eva_input_3').val();
        let eva_input_4    = $('#eva_input_4').val();
        // let eva_input_5    = $('#eva_input_5').val();

        $('.form-control').removeClass('is-invalid');
        // $("input[name='eva_checkbox_1']").removeClass('is-invalid');

        if(eva_slc_cargo == ""){
            error += 1;
            $('#eva_slc_cargo').addClass('is-invalid').focus();
        }

        if(eva_slc_1 == ""){
            error += 1;
            $('#eva_slc_1').addClass('is-invalid').focus();
        }

        if(eva_txtarea_1 == ""){
            error += 1;
            $('#eva_txtarea_1').addClass('is-invalid').focus();
        }

        if(slc_priority_area_legal.length < 1){
            error += 1;
            $("input[name='eva_checkbox_1']").addClass('is-invalid').focus();
        }

        // if(eva_slc_comp == ""){
        //     error += 1;
        //     $('#eva_slc_comp').addClass('is-invalid').focus();
        // }

        // if(eva_slc_realizacao == ""){
        //     error += 1;
        //     $('#eva_slc_realizacao').addClass('is-invalid').focus();
        // }

        if(eva_txtarea_2 == ""){
            error += 1;
            $('#eva_txtarea_2').addClass('is-invalid').focus();
        }

        if(eva_input_1 == ""){
            error += 1;
            $('#eva_input_1').addClass('is-invalid').focus();
        }

        // if(eva_input_2 == ""){
        //     error += 1;
        //     $('#eva_input_2').addClass('is-invalid').focus();
        // }

        // if(eva_slc_2 == ""){
        //     error += 1;
        //     $('#eva_slc_2')
        //         .addClass('is-invalid')
        //         .focus();
        //
        // }

        // if(eva_input_3 == ""){
        //     $('#eva_input_3').addClass('is-invalid').focus();
        // }

        if(error > 0){
            console.log(error);
            return false;

        }else{
            $.ajax({
                url: '<?php echo $CFG->wwwroot?>/blocks/eva_training_suggestion/service.php',
                method: 'POST',
                dataType: 'json',
                data:{
                    'pageid': pageid,
                    'ts': ts,
                    'eva_slc_1': eva_slc_1,
                    'eva_slc_cargo': eva_slc_cargo,
                    // 'eva_slc_2': eva_slc_2,
                    'eva_txtarea_1': eva_txtarea_1,
                    'eva_txtarea_2': eva_txtarea_2,
                    'slc_priority_area_legal': slc_priority_area_legal,
                    // 'slc_technical_legal': slc_technical_legal,
                    // 'eva_slc_comp': eva_slc_comp,
                    'slc_modality': slc_modality,
                    // 'eva_slc_realizacao': eva_slc_realizacao,
                    'eva_input_1': eva_input_1,
                    // 'eva_input_2': eva_input_2,
                    // 'eva_input_3': eva_input_3,
                    'eva_input_4': eva_input_4
                    // 'eva_input_5': eva_input_5,
                    // 'slc_previsao': slc_previsao
                },
                success: function(response){
                    var erros  = response.erros;
                    var msg    = response.msg;
                    var email  = response.email;
                    var id_ts  = response.id_ts;
                    var organ  = response.organ;
                    var userid = response.userid;
                    var alerta = "";
                    var icone  = "";
                    var txt    = "";

                    if(erros > 0){
                        alerta = "danger";
                        icone  = "fa-times-circle"
                    }else{
                        alerta = "success";
                        icone  = "fa-check-circle";
                    }

                    txt += '<div class="row" id="divRemover" name="divRemover" style="width: 100%;margin-left: 0px;">';
                    txt += '<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">';
                    txt += '<div class="alert alert-'+alerta+'" id="'+alerta+'-alert">';
                    txt += '<button type="button" class="close" data-dismiss="alert">x</button>';
                    txt += '<div class="row">';
                    txt += '<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">';
                    txt += '<div style="text-align: center;">';
                    txt += '<i class="fa '+icone+' fa-3x"></i>';
                    txt += '</div>';
                    txt += '</div>';
                    txt += '</div>';
                    txt += '<div class="row">';
                    txt += '<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">';
                    txt += '<div style="text-align: center;">';
                    txt += '<span style="font-weight: 600; font-size: 1.2em;">';
                    txt += msg;
                    txt += '</span>';
                    txt += '</div>';
                    txt += '</div>';
                    txt += '</div>';
                    txt += '</div>';
                    txt += '</div>';
                    txt += '</div>';

                    $("#divRemover").remove();
                    $("#divMsg").html(txt);
                    goToByScroll("page");

                    if(email > 0 && email !== "undefined"){
                        enviarEmail(userid,pageid,ts,eva_slc_cargo,eva_slc_1,eva_txtarea_1,eva_txtarea_2,slc_modality,eva_input_1,eva_input_4);

                        setTimeout(function(){
                            limparCampos();
                        },1000);

                        // setTimeout(function(){
                        //     if(pageid > 0){
                        //         window.location = '<?php echo $CFG->wwwroot?>/mod/page/view.php?id=' + pageid;
                        //     }else{
                        //         window.location = '<?php echo $CFG->wwwroot?>';
                        //     }
                        // }, 7000);
                    }
                }
            });
        }
    }

    function enviarEmail(userid,pageid,ts,eva_slc_cargo,eva_slc_1,eva_txtarea_1,eva_txtarea_2,slc_modality,eva_input_1,eva_input_4){
        $.ajax({
            url: '<?php echo $CFG->wwwroot?>/blocks/eva_training_suggestion/send_mail.php',
            type: 'POST',
            dataType: 'json',
            data:{
                'userid': userid,
                'pageid': pageid,
                'ts': ts,
                'eva_slc_cargo': eva_slc_cargo,
                'eva_slc_1': eva_slc_1,
                // 'eva_slc_2': eva_slc_2,
                'eva_txtarea_1': eva_txtarea_1,
                'eva_txtarea_2': eva_txtarea_2,
                'slc_priority_area_legal': slc_priority_area_legal,
                // 'slc_technical_legal': slc_technical_legal,
                // 'eva_slc_comp': eva_slc_comp,
                'slc_modality': slc_modality,
                // 'eva_slc_realizacao': eva_slc_realizacao,
                'eva_input_1': eva_input_1,
                // 'eva_input_2': eva_input_2,
                // 'eva_input_3': eva_input_3,
                'eva_input_4': eva_input_4
                // 'eva_input_5': eva_input_5,
                // 'slc_previsao': slc_previsao
            },
            success: function(response){
                console.log(response);
            }
        });
    }

    function limparCampos(){
        $('#eva_slc_cargo').val('');
        $('#eva_slc_1').val('');
        // $('#eva_slc_2').val('');
        $('#eva_txtarea_1').val('');
        $('#eva_txtarea_2').val('');
        $.each($("input[name='eva_checkbox_1']:checked"), function(){
            $(this).prop('checked',false);
         });
        // $.each($("input[name='eva_checkbox_2']:checked"), function(){
        //     $(this).prop('checked',false);
        // });
        $.each($("input[name='eva_checkbox_3']:checked"), function(){
            $(this).prop('checked',false);
        });
        // $.each($("input[name='eva_checkbox_4']:checked"), function(){
        //     $(this).prop('checked',false);
        // });
        $('#eva_input_1').val('');
        // $('#eva_input_2').val('');
        // $('#eva_input_3').val('');
        $('#eva_input_4').val('');
        // $('#eva_input_5').val('');
    }
</script>