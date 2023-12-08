/**
 * @author Celio Pereira Batalha Batalha
 * email: celio.batalha@gmail.com
 */

$('#id_tb_bolsa_modelo_id').addClass('bolsa_id');
$('.ftoggler').addClass('btoggler');
$('.fcontainer').addClass('bg_area');
$('.form-group').addClass('bitem');
$("#id_startdate_ava_day, #id_startdate_ava_month, #id_startdate_ava_year, #id_startdate_ava_hour, #id_startdate_ava_minute").addClass('data_select');
$("#id_qt_dias_ava").addClass('qt_dias');

$(function () {

    //======================================= EVENTO DO BOTÃO NOVO BAREMA MODELO =====================================
    // const wwwroot = $('.btnConfigEdit').attr('data-root');
    $("#id_btnCreateModelo").click(function () {
        var numero_barema = $('.btnConfigEdit').attr('data-buiedit');

        $.ajax({
            url: wwwroot + '/blocks/eva_barema_bolsa/condicional.php',
            data: 'acao=editformbolsa',
            dataType: 'json',
            success: function (resposta) {
                if (resposta == true) {
                    $(location).attr('href', wwwroot + '/blocks/eva_barema_bolsa/create.php?id=1&bui_editid=' + numero_barema);
                }else {
                    alert("Falha ao construir o formulario modelo...");
                }
            }
        });
    });


    //====================EVENTO DO RADIO BUTTOM PARA A SELEÇÃO DOS ANTEPROJETO=====================
    const wwwroot = $('.btnConfigEdit').attr('data-root');
    $("#id_eixo_j, #id_eixo_g").change(function (ev) {
        ev.preventDefault();
        var eixo = $(this).val();
        $.ajax({
            url: wwwroot +'/blocks/eva_barema_bolsa/condicional.php',
            data: 'acao=escolhaeixo&eixo=' + eixo,
            success: function (resp_eixo) {
                var dados = JSON.parse(resp_eixo);
                if (dados == 'success'){
                }
            }
        });
    });
    $("#id_eixo_j, #id_eixo_g").click(function (ev) {
            window.location.href = wwwroot + "/blocks/eva_barema_bolsa/create.php?id=1";
    });



    //====================EVENTO DO CAMPO O NUMREO DO ANTEPROJETO PARA POPULAR O SELECT DO AVALIADOR 1=====================

    $("#id_tb_anteprojeto_id").change(function (){
        var numero = $(this).val();
        $.ajax({
            url: wwwroot +'/blocks/eva_barema_bolsa/condicional.php',
            data: 'acao=selecionaravaliadores&id=' + numero,
            success: function ( resp ) {
                var dados = JSON.parse(resp);
                var options = '';
                if (dados.length > 0) {
                    options = '<option value="">--SELECIONE O AVALIADOR--</option>';
                    for (var i = 0; i < dados.length; i++) {
                        let dispensado = dados[i].afastado == 'disabled' ? ' -- ( Afastado )' : '';
                        options += '<option value="' + dados[i].id + '" '+dados[i].afastado+'>' + dados[i].nome + '' + dispensado + '</option>';
                    }
                }

                // $("#id_x1").addClass('d-none');
                // $("#id_avaliador_1").attr('disabled', false);

                $('#id_avaliador_1').html(options);
            }
        });
    });

//================ SELECT DO AVALIADOR UM ================================
    $("#id_avaliador_1").change(function (e){
        e.preventDefault();
        var valor1 = $(this).val();
        var numero = $("#id_tb_anteprojeto_id").val();

        // $(this).attr('disabled', true);
        // $("#id_x1").attr('data-ava1', + valor1);
        // $("#id_x1").removeClass('d-none');

        $.ajax({
            url: wwwroot +'/blocks/eva_barema_bolsa/condicional.php',
            data: 'acao=selecionaravaliadores&id=' + numero,
            success: function ( resp ) {
                var dados = JSON.parse(resp);
                var options = '';
                if (dados.length > 0) {
                    options = '<option value="">--SELECIONE O AVALIADOR--</option>';
                    for (var i = 0; i < dados.length; i++) {
                        if (dados[i].id != valor1){
                            let dispensado = dados[i].afastado == 'disabled' ? ' -- ( Afastado )' : '';
                            options += '<option value="' + dados[i].id + '" '+dados[i].afastado+'>' + dados[i].nome + '' + dispensado +'</option>';
                        }
                    }
                }

                // $("#id_x2").addClass('d-none');
                // $("#id_avaliador_2").attr('disabled', false);

                $('#id_avaliador_2').html(options);
            }
        });

    });

    $("#id_x1").click(function (e){
        e.preventDefault();
        var valor2 = $("#id_avaliador_2").val();
        var valor3 = $("#id_avaliador_3").val();

        // $("#id_x1").addClass('d-none');
        // $("#id_avaliador_1").attr('disabled', false);

        var numero = $("#id_tb_anteprojeto_id").val();
        $.ajax({
            url: wwwroot +'/blocks/eva_barema_bolsa/condicional.php',
            data: 'acao=selecionaravaliadores&id=' + numero,
            success: function ( resp ) {
                var dados = JSON.parse(resp);
                var options = '';
                if (dados.length > 0) {
                    options = '<option value="">--SELECIONE O AVALIADOR--</option>';
                    for (var i = 0; i < dados.length; i++) {
                        if (dados[i].id != valor2 && dados[i].id != valor3){
                            let dispensado = dados[i].afastado == 'disabled' ? ' -- ( Afastado )' : '';
                            options += '<option value="' + dados[i].id + '" '+dados[i].afastado+'>' + dados[i].nome + '' + dispensado +'</option>';
                        }
                    }
                }
                $('#id_avaliador_1').html(options);
            }
        });
    });

//================ SELECT DO AVALIADOR DOIS ================================

    $("#id_avaliador_2").change(function (e){
        e.preventDefault();
        var valor2 = $(this).val();
        var valor1 = $("#id_avaliador_1").val();
        var numero = $("#id_tb_anteprojeto_id").val();

        // $(this).attr('disabled', true);
        // $("#id_x2").attr('data-ava2', + valor2);
        // $("#id_x2").removeClass('d-none');

        $.ajax({
            url: wwwroot +'/blocks/eva_barema_bolsa/condicional.php',
            data: 'acao=selecionaravaliadores&id=' + numero,
            success: function ( resp ) {
                var dados = JSON.parse(resp);
                var options = '';
                if (dados.length > 0) {
                    options = '<option value="">--SELECIONE O AVALIADOR--</option>';
                    for (var i = 0; i < dados.length; i++) {
                        if (dados[i].id != valor2 && dados[i].id != valor1){
                            let dispensado = dados[i].afastado == 'disabled' ? ' -- ( Afastado )' : '';
                            options += '<option value="' + dados[i].id + '" '+dados[i].afastado+'>' + dados[i].nome + '' + dispensado +'</option>';
                        }
                    }
                }

                // $("#id_x3").addClass('d-none');
                // $("#id_avaliador_3").attr('disabled', false);

                $('#id_avaliador_3').html(options);
            }
        });

    });

    $("#id_x2").click(function (e){
        e.preventDefault();

        // $("#id_x2").addClass('d-none');
        // $("#id_avaliador_2").attr('disabled', false);

        var valor1 = $("#id_avaliador_1").val();
        var numero = $("#id_tb_anteprojeto_id").val();
        $.ajax({
            url: wwwroot +'/blocks/eva_barema_bolsa/condicional.php',
            data: 'acao=selecionaravaliadores&id=' + numero,
            success: function ( resp ) {
                var dados = JSON.parse(resp);
                var options = '';
                if (dados.length > 0) {
                    options = '<option value="">--SELECIONE O AVALIADOR--</option>';
                    for (var i = 0; i < dados.length; i++) {
                        if (dados[i].id != valor1){
                            let dispensado = (dados[i].afastado == 'disabeld') ? 'afastado' : '';
                            options += '<option value="' + dados[i].id + '" '+dados[i].afastado+'>' + dados[i].nome + '' + dispensado +'</option>';
                        }
                    }
                }
                $('#id_avaliador_2').html(options);
            }
        });
    });


//================ SELECT DO AVALIADOR TRES ================================

    $("#id_avaliador_3").change(function (e){
        e.preventDefault();

        // var valor3 = $(this).val();
        // $(this).attr('disabled', true);
        // $("#id_x3").attr('data-ava3', + valor3);
        // $("#id_x3").removeClass('d-none');

    });

    $("#id_x3").click(function (e){
        e.preventDefault();

        // $("#id_x3").addClass('d-none');
        // $("#id_avaliador_3").attr('disabled', false);

        var valor2 = $("#id_avaliador_2").val();
        var valor1 = $("#id_avaliador_1").val();
        var numero = $("#id_tb_anteprojeto_id").val();
        $.ajax({
            url: wwwroot +'/blocks/eva_barema_bolsa/condicional.php',
            data: 'acao=selecionaravaliadores&id=' + numero,
            success: function ( resp ) {
                var dados = JSON.parse(resp);
                var options = '';
                if (dados.length > 0) {
                    options = '<option value="">--SELECIONE O AVALIADOR--</option>';
                    for (var i = 0; i < dados.length; i++) {
                        if (dados[i].id != valor2 && dados[i].id != valor1){
                            let dispensado = dados[i].afastado == 'disabled' ? ' -- ( Afastado )' : '';
                            options += '<option value="' + dados[i].id + '" '+dados[i].afastado+'>' + dados[i].nome + '' + dispensado +'</option>';
                        }
                    }
                }
                $('#id_avaliador_3').html(options);
            }
        });
    });

});
