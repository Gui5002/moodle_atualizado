
$('#id_avaliadoruser option').attr('selected', 'selected');
$('#id_tb_barema_id').addClass('barema_id');
$('#id_resposta_padrao').addClass('resp_default');
$('.ftoggler').addClass('btoggler');
$('.fcontainer').addClass('bg_area');
$('.form-group').addClass('bitem');


$(function (){

    //======================================= EVENTO DO BOTÃO NOVO BAREMA MODELO =====================================
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
    const wwwroot = $('.btnConfigEdit').attr('data-root');

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
                    // console.log(dados[1].nome )
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





    //====================== CHAMA O MODAL E FAZ A DISTRIBUIÇÃO DOS ALUNOS PARA OS AVALIADORES===================
    let qt_total_aluno = '';
    $("#id_dist").click(function (){

        const wwwroot = $(".wwwroot").attr('data-root');
        var avaliadorid = $('#id_avaliador_tb_user_id').val();
        var quizid = $('#id_tb_atividade_id').val();
        var message = '';
        var error = vericicaCampos(avaliadorid, quizid);

        if (error > 0) {
            return false;
        }

        $.ajax({
            url: wwwroot +'/blocks/eva_form_barema/completar.php',
            data: 'acao=quantosalunos&quizid='+quizid+'&avaliadores='+avaliadorid,

            success: function (response) {
                var qtalunos = JSON.parse(response);

                qt_total_aluno = qtalunos[0].total;
                var disribuicao = '';

                message = '' +
                    '<div class="alert alert-warning" role="alert">\n' +
                    'Por favor selecione Avaliador!' +
                    '</div>'
                ;

                disribuicao += '' +
                    ' <div id="bitem_avaliados" class="form-group row bitem">\n' +
                    '     <div class="col-md-7 col-form-label d-flex pb-0 pr-md-0">\n' +
                    '         <label class="d-inline word-break ">Alunos Selecionados</label>\n' +
                    '         <div class="form-label-addon d-flex align-items-center align-self-start"></div>\n' +
                    '     </div>\n' +
                    '     <div class="col-md-5 form-inline align-items-start felement" data-fieldtype="select">\n' +
                    '       <div class="alert alert-warning text-center" role="alert" style="width: 100%">\n' +
                    '           <div class="qt-total fw600" style="color: #2a866f">'+qt_total_aluno+' &nbsp;&nbsp;&nbsp;&nbsp;  de &nbsp;&nbsp;&nbsp; '+qt_total_aluno+'</div>\n' +
                    '       </div>\n' +
                    '     </div>\n' +
                    ' </div>';

                for (var i=0;  i < qtalunos.length; i++){
                    disribuicao += '' +
                    ' <div id="bitem_avaliador_'+i+'" class="form-group row bitem">\n' +
                    '     <div class="col-md-7 col-form-label d-flex pb-0 pr-md-0">\n' +
                    '         <label class="d-inline word-break " for="avaliador_'+i+'">'+qtalunos[i].avaliador+'</label>\n' +
                    '         <div class="form-label-addon d-flex align-items-center align-self-start">\n' +
                    '             <div class="text-danger" title="Necessários">\n' +
                    '                 <i class="icon fa ccn-flaticon-warning text-danger fa-fw " title="Necessários"\n' +
                    '                    aria-label="Necessários"></i>\n' +
                    '             </div>\n' +
                    '         </div>\n' +
                    '     </div>\n' +
                    '     <div class="col-md-5 align-items-start felement" data-fieldtype="select">\n' +
                    '         <input type="text" class="form-control" name="qt_avaliando" data-userid_'+i+'="'+qtalunos[i].avaliador_id+'" id="qt_avaliando_'+i+'" value="'+qtalunos[i].qt_avaliando+'" onkeyup="mudarDistribuicao('+qt_total_aluno+', this);">\n' +
                    '         <input type="hidden" name="userid" id="qt_avaliando_'+i+'" value="'+qtalunos[i].avaliador_id+'">\n' +
                    '     </div>\n' +
                    ' </div>';
                }
                // onkeyup="mudarDistribuicao('+qt_total_aluno+');"
                $('#distribuicao_avaliadores').html(disribuicao);
                $("#id_submitbutton").attr("hidden", true);
                $(".btn-Adicionar").attr("hidden", false);

            }
        });

        $("#distribuicao_avaliacao_modal").modal('show');
    });

    $("#id_cancel").click(function (){
        window.location.href = wwwroot + "/blocks/eva_form_barema/create.php?id=1";
    });

    function vericicaCampos(avaliadores, quiz){
        let error =0;
        let message ='';

        if (avaliadores == ""){
            error += 1;
            message = '' +
                '<div class="alert alert-warning" role="alert">\n' +
                'Por favor selecione Avaliador!' +
                '</div>'
            ;
            $('.msg').html(message);
            setTimeout(function () {
                $('#msgSuccess').modal('show');
            }, 100);
        }else
        if (quiz == null){
            error += 1;
            message = '' +
                '<div class="alert alert-warning" role="alert">\n' +
                'Selecione a Atividade!' +
                '</div>'
            ;
            $('.msg').html(message);
            setTimeout(function () {
                $('#msgSuccess').modal('show');
            }, 100);
        }

        return error;
    }

    //======================= EVENTO DO BOTAO ADCIONAR DO MODAL DE DISTRIBUIÇÃO ==========================
    $(".btn-Adicionar").click(function (){

        const wwwroot = $(".wwwroot").attr('data-root');
        var avaliadorid = $('#id_avaliador_tb_user_id').val();
        var quizid = $('#id_tb_atividade_id').val();
        var message = '';

        let input_qtavaliados = [];
        let input_userid = [];
        $.each($("input[name='qt_avaliando']"), function () {
            input_qtavaliados.push($(this).val());
        });
        $.each($("input[name='userid']"), function () {
            input_userid.push($(this).val());
        });


        $.ajax({
            url: wwwroot + '/blocks/eva_form_barema/completar.php',
            data: 'acao=adicionardistribuicao&userid=' + input_userid + '&qtavaliados=' + input_qtavaliados,

            beforeSend: function () {
                status_loading(); //======== Chama a animação do spinner Aguardando ======
            },
            success: function (response) {
                var qtalunos = JSON.parse(response);

                console.log(qtalunos);

                var msgsucesso = '' +
                    '<div class="alert alert-success d-flex align-items-center text-center" role="alert">' +
                    '<div class="">Adicionado com Sucesso!</div>\n' +
                    '</div>'

                var msgfracasso = '' +
                    '<div class="alert alert-danger d-flex align-items-center text-center" role="alert">' +
                    '<div class="">Falha na Distribuição!</div>\n' +
                    '</div>'

                if(qtalunos == true){
                    $(".load").removeClass("d-none");
                    setTimeout(function (){
                        $("#id_submitbutton").attr("hidden", false);
                        $(".load").html(msgsucesso);
                    },1000);
                    setTimeout(function (){
                        $(".load").addClass("d-none");

                        $.ajax({
                            url: wwwroot + '/blocks/eva_form_barema/completar.php',
                            data: 'acao=buscardistribuicao',
                            success: function (res) {
                                let cont = JSON.parse(res);

                                console.log(cont);
                                var buscas="";

                                // setTimeout(function () {
                                // },1000)
                                    for (let i=1; i <= cont.length; i++){
                                        buscas += '<p>'+cont[i].ava + ' - Qtd de Alunos :' + cont[i].avaid +'</p>';
                                    }
                                    $("#fitem_id_dist .felement").css({display: 'block'});

                                    $("#fitem_id_dist .form-control-static").html(buscas);

                            }
                        });
                    }, 3000);
                }else{
                    $(".load").removeClass("d-none");
                    setTimeout(function (){
                        $("#id_submitbutton").attr("hidden", false);
                        $(".load").html(msgfracasso);
                    },1000);
                }
            }
        });

    });


    //===================FUNÇÃO CHAMADO PARA ANIMAÇÃO DO SPINER "AGUARDE...."========================
    function status_loading (){
        var spiner = load_animation_spiner()
        $(".load").html(spiner)
    }

    function load_animation_spiner(spiner){
        var spiner = '' +
            '<div class="alert alert-warning d-flex align-items-center" role="alert">\n' +
            '<div class="spinner-wrapper">\n' +
            '   <div class="spinner-border" role="status"></div>\n' +
            '</div>\n' +
            '<div class="ml-1">Aguarde...</div>\n' +
            '</div>'
        return spiner;
    }


    $("#id_avaliador_tb_user_id, #id_tb_categoria_id, #id_tb_subcategoria_id, #id_tb_curso_id, #id_tb_atividade_id").change(function (e) {
        e.preventDefault();
        $.ajax({
            url: wwwroot + '/blocks/eva_form_barema/completar.php',
            data: 'acao=deletetabeladistribuicao',

            success: function (response) {
                var qtalunos = JSON.parse(response);

                $("#id_submitbutton").attr("hidden", true);
                if(qtalunos == true){
                    // $("#id_submitbutton").attr("hidden", true);
                    window.location.href = wwwroot + "/blocks/eva_form_barema/create.php?id=1";
                }
            }
        });

    });
});

//===========================FUNCAO PARA ALTERACAO DA DISTRIBUIÇÃO =================================
function mudarDistribuicao(total_aluno, this_input) {
    var qtinput = [];
    $.each($("input[name='qt_avaliando']"), function () {
        qtinput.push($(this).val());
    });
    var qt_input=0;
    for (var i=0; i < qtinput.length; i++){
        var vazio =  (qtinput[i] == "")? 0 : qtinput[i];

        qt_input += parseInt(vazio);
    }


    var qt_total='';
    if ((qt_input < total_aluno) || (qt_input > total_aluno) ){
    qt_total = qt_input + '&nbsp;&nbsp;&nbsp;&nbsp;  de &nbsp;&nbsp;&nbsp; ' + total_aluno;

        $(".qt-total").html(qt_total);
        $(".qt-total").css({color: 'red'});
        $(".btn-Adicionar").attr("hidden", true);
    }else{
        qt_total = qt_input + '&nbsp;&nbsp;&nbsp;&nbsp;  de &nbsp;&nbsp;&nbsp; ' + total_aluno;

        $(".qt-total").css({color: '#2a866f'});
        $(".qt-total").html(qt_total);
        $(".btn-Adicionar").attr("hidden", false);
    }

    var elemento = document.getElementById("qt_avaliando_0").value;


}








