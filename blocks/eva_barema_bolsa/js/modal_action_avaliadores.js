/**
 * @author Celio Pereira Batalha
 * email: celio.batalha@gmail.com
 */

$('.btoggler').addClass('d-none');

$(function (){


    let baremaid = $('.btnEditModal').attr('data-baremaid');

    $(".btnEditModal, .btnAdicionarModal, .btnDeleteModal").click(function (event){
        const wwwroot = $(this).attr('data-root');
        let id = $(this).attr('data-id');
        let iduser = $(this).attr('data-iduser');
        let coluna_user_id = $(this).attr('data-colunauserid');
        let header = $(this).attr('data-header');
        let eixo = $(this).attr('data-eixo');
        // let btn = $(this).attr('data-btn');
        $("#avaliador_bolsa_ModalLabel").html(header);

        $.ajax({
            url: wwwroot+'/blocks/eva_barema_bolsa/condicional.php',
            data: 'acao=buscausuarios',
            success: function ( resposta ) {
                var dados = JSON.parse(resposta);

                if (dados) {
                    var options = '<option value="">-- ESCOLHA O AVALIADOR --</option>';
                    $('#id_titular_user_id').html(options);
                    $('#id_suplente_user_id').html(options);
                }
                for (var i = 0; i < dados.length; i++) {
                    options += '<option value="'+ dados[i].id +'">' + dados[i].nome + '</option>';
                }
                $('.selecttitular').html(options);
                $('.selectsuplente').html(options);

            //=====dados que sao passado para a tela modal de edicao do
                // $(".btnEvento").html(btn);
                // $(".btnEvento").attr('id', 'id_'+btn);
                $('.editinputavaliadores').attr('data-id', id);
                $('.editinputavaliadores').attr('data-colunauserid', coluna_user_id);
                $('.juridico_gestao').html(options);
                $('.juridico_gestao option[value='+iduser+']').attr('selected', 'selected');
                // $('.selectsuplente option[value='+iduser+']').attr('selected', 'selected');
                // $('#id_edit_avaliador_afast').val(id);
                $("."+eixo+"_ch").attr('checked', true);
            }
        });

    });



    $(".modalClose, .modalFechar").click(function (ev){
        $(location).attr('href','/blocks/eva_barema_bolsa/avaliadores.php');
    });
});

function status_avaliadores(valor){
        var wwwroot = $(valor).attr('data-root');
        var status = $(valor).attr('data-status');
        var id = $(valor).attr('data-iduser');
        // var coluna_status = $(valor).attr('data-colunastatus');
        // var coluna_user_id = $(valor).attr('data-colunauserid');
        $.ajax({
            // url: wwwroot+'/blocks/eva_barema_bolsa/condicional.php?status='+status+'&id='+id+'&coluna_status='+coluna_status+'&user_id='+coluna_user_id,
            url: wwwroot+'/blocks/eva_barema_bolsa/condicional.php?status='+status+'&id='+id,
            data: 'acao=mudarstatus',
            success: function ( resposta ) {
                let dados = JSON.parse(resposta);
                    $('#avaliador_bolsa_Modal').modal('hide');
                    var alert = '<div class="alert alert-'+dados.alert+'" role="alert"><strong>'+dados.msg+'!</strong></div>'
                    $('.msg').html(alert)
                    setTimeout(function () {
                        $('#msg_id').modal('show');
                    }, 100);
                    // $(location).attr('href', wwwroot+'/blocks/eva_barema_bolsa/avaliadores.php');
            }
        });
}
//==================== ACAO DO ONMOUSECLICK ===============================
function add_alter_avaliador_bolsa(btnEvent) {
    const wwwroot = $(btnEvent).attr('data-root');
    
    //================== FAZ A ADIÇÃO DE AVALIADORES ==========================
    if (btnEvent.id === 'id_Adicionar') {
        let user_id = $('.selecttitular').val();
        // let suplente_user_id = $('.selectsuplente').val();
        let radiobutton_eixo = $("input[name='distribuicao']:checked").val();

        var error = verificacamposvazio(user_id, radiobutton_eixo);

        if (error > 0) {
            return false;
        }
        $.ajax({
            url: wwwroot + '/blocks/eva_barema_bolsa/condicional.php?user_id=' + user_id  + '&eixo=' + radiobutton_eixo,
            data: 'acao=adicionarvaliador',
            success: function (resp) {
                var dados = JSON.parse(resp);
                console.log(dados['msg']);
                if(dados['msg'] != false){
                    $('#avaliador_bolsa_Modal').modal('hide');
                    var alert = '<div class="alert alert-success" role="alert"><strong>Avaliador adicionado com sucesso!</strong></div>'
                    $('.msg').html(alert)
                    setTimeout(function () {
                        $('#msg_id').modal('show');
                    }, 100);
                }else{
                    $('#avaliador_bolsa_Modal').modal('hide');
                    var alert = '<div class="alert alert-warning" role="alert"><strong>Avaliador já existe!</strong></div>'
                    $('.msg').html(alert)
                    setTimeout(function () {
                        $('#msg_id').modal('show');
                    }, 100);
                }


            }
        });
    }

    //================== FAZ A ADIÇÃO DE AVALIADORES ==========================

    if (btnEvent.id === 'id_Excluir') {

    }
}

function verificacamposvazio(user_id, radiobutton_eixo) {
    let error = 0;

    if (user_id == "") {
        error += 1;
        $(".selecttitular").addClass('is-invalid').focus()
    }
    // if (suplente_user_id == "") {
    //     error += 1;
    //     $(".selectsuplente").addClass('is-invalid').focus()
    // }
    if (radiobutton_eixo == undefined) {
        error += 1;
        $(".distribuicao").addClass('is-invalid').focus()
    }

    return error;
}


const bt = document.querySelector(".bt-lapis");
const modal = document.querySelector("dialog");
const btclose = document.querySelector("dialog button")

$(".bt-lapis").click(function (){
    let wwwroot = $('.bt-lapis').attr('data-root');
    let input_id = $(this).attr("data-id");
    $(".input_id").val(input_id);
    $.ajax({
        url: wwwroot + '/blocks/eva_barema_bolsa/condicional.php?id='+input_id,
        data: 'acao=pegareixo',
        success: function (respeixo) {
            var dados = JSON.parse(respeixo);
            console.log(dados['eixo'])
            if(dados['eixo'] == 'j'){
                $("#j_id").attr("checked", true);
            }
            if(dados['eixo'] == 'g'){
                $("#g_id").attr("checked", true);
            }
        }
    });
    let el = this;
    let coordenadas = el.getBoundingClientRect();
    let pos_x = Math.floor(coordenadas.left) + parseInt(36);
    let pos_y = Math.floor(coordenadas.top) - parseInt(10);
    console.log(pos_x)

    $(".dlg").css({
        'margin': 0,
        'postion': 'absolute',
        'left': pos_x,
        'top': pos_y,
    });
    
    modal.showModal();
});

$("#id_Alterar").click(function () {
    let id = $(".input_id").val();
    let wwwroot = $('.bt-lapis').attr('data-root');
    let radiobutton_eixo = $("input[name='eixo']:checked").val();
    $.ajax({
        url: wwwroot + '/blocks/eva_barema_bolsa/condicional.php?id='+id +'&eixo=' + radiobutton_eixo,
        data: 'acao=editavaliadores',
        success: function (resp) {
            var dados = JSON.parse(resp);
            console.log(dados)
            if (dados) {
                $('#avaliador_bolsa_Modal').modal('hide');
                var alert = '<div class="alert alert-success" role="alert"><strong>Alterado com Sucesso!</strong></div>'
                $('.msg').html(alert)
                setTimeout(function () {
                    $('#msg_id').modal('show');
                }, 100);
                modal.close();
            }
        }
    });
});

$(".bt-close").click(function (){
    modal.close();
    $("#j_id").attr("checked", false);
    $("#g_id").attr("checked", false);
});

    // modal.close();
        
//=============== FAZ A ALTERAÇÃO - MODAL EDIT EVALIADORES =====================================



