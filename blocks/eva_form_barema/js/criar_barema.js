
$('#id_avaliadoruser option').attr('selected', 'selected');
$('#id_tb_barema_id').addClass('barema_id');
$('#id_resposta_padrao').addClass('resp_default');
$('.ftoggler').addClass('btoggler');
$('.fcontainer').addClass('bg_area');
$('.form-group').addClass('bitem');


$(function (){

    //======================================= EVENTO DO BOTÃO NOVO BAREMA MODELO =====================================
    const wwwroot = $('.btnConfigEdit').attr('data-root');
    $("#id_btnConfigEdit").click( function () {
        var numero_barema = $('.btnConfigEdit').attr('data-buiedit');

        $.ajax({
            url: wwwroot +'/blocks/eva_form_barema/completar.php',
            data: 'acao=editconfigdatabarema',
            dataType: 'json',
            success: function (resposta) {
                $(location).attr('href', wwwroot +'/blocks/eva_form_barema/barema.php?id=1&bui_editid='+ numero_barema);
            }
        });
    });

    //===========================================EVENTO DO SELECT - CATEGORIA - ========================================
    $("#id_tb_categoria_id").change(function (){
        $('#id_tb_subcategoria_id').html('<option value=""></option>');
        $('#id_tb_curso_id').html('<option value=""></option>');
        $('#id_tb_atividade_id').html('<option value=""></option>');
        var idcategoria = $(this).val();
        if ($(this).val()){
            $.ajax({
                url: wwwroot+'/blocks/eva_form_barema/completar.php',
                data: 'acao=buscarsubcategoria&id_subcategoria='+idcategoria,
                success: function ( resposta ) {
                    var dados = JSON.parse(resposta);
                    console.log(dados[1].nome )
                    var options = '';
                    $('#id_tb_subcategoria_id').html(options);
                    if (dados) {
                        options = '<option value="">SELECIONE UMA SUBCATEGORIA</option>';
                        $('#id_tb_subcategoria_id').html(options);
                    }
                    //======AQUI PUXA OS DADOS E MONTA O OPTGROUP NA SUBCATEGORIA======================
                    for (var i = 0; i < dados.length; i++) {
                        if (dados[i].nome){
                            options += '<option value="'+ dados[i].id +'">' + dados[i].nome + '</option>';
                        }
                        if (dados[i].optlabel) {
                            options += '<optgroup label="'+ dados[i].optlabel.nome +'">';
                                for (var x = 0; x < dados.options.length; x++) {
                                    options += '<option value="'+ dados.options[x].id +'">' + dados.options[x].nome + '</option>'
                                }
                            options += '</optgroup>';
                        }
                    }
                    $('#id_tb_subcategoria_id').html(options);
                }
            });
            $.ajax({
                url: wwwroot+'/blocks/eva_form_barema/completar.php',
                data: 'acao=buscarcursos&id_curso='+idcategoria,
                success: function ( resp ) {
                    var dados = JSON.parse(resp);
                    var options = '';
                    $('#id_tb_curso_id').html(options);
                    if (dados) {
                        options = '<option value="">SELECIONE UM CURSO</option>';
                        $('#id_tb_curso_id').html(options);
                    }
                    for (var i = 0; i < dados.length; i++) {
                        options += '<option value="'+ dados[i].id +'">' + dados[i].nome + '</option>';
                    }
                    $('#id_tb_curso_id').html(options);
                }
            });
        }
    });



    //===========================================EVENTO DO SELECT - SUBCATEGORIA - ========================================

    $("#id_tb_subcategoria_id").change(function (){
        var idsubcategoria = $(this).val();
        $.ajax({
            url: wwwroot +'/blocks/eva_form_barema/completar.php',
            data: 'acao=buscarcursos&id_curso='+idsubcategoria,
            success: function ( resp ) {
                var dados = JSON.parse(resp);
                var options = '';
                $('#id_tb_curso_id').html(options);
                if (dados) {
                    options = '<option value="">SELECIONE UM CURSO</option>';
                    $('#id_tb_curso_id').html(options);
                }
                for (var i = 0; i < dados.length; i++) {
                    options += '<option value="'+ dados[i].id +'">' + dados[i].nome + '</option>';
                }
                $('#id_tb_curso_id').html(options);
            }
        });
    });

    //===========================================EVENTO DO SELECT - CURSO - ========================================

    $("#id_tb_curso_id").change(function (){
        var idcurso = $(this).val();
        var idestudante = $('#id_estudante').val();
        $.ajax({
            url: wwwroot +'/blocks/eva_form_barema/completar.php',
            data: 'acao=buscaatividades&id_curso='+idcurso+'&id_estudante='+idestudante,
            success: function ( resp ) {
                var dados = JSON.parse(resp);
                var options = '';
                $('#id_tb_atividade_id').html(options);
                if (dados) {
                    options = '<option value="">SELECIONE UMA ATIVIDADE</option>';
                    $('#id_tb_atividade_id').html(options);
                }
                for (var i = 0; i < dados.length; i++) {
                    options += '<option value="'+ dados[i].id +'">' + dados[i].nome + '</option>';
                }
                $('#id_tb_atividade_id').html(options);
            }
        });
    });

});







