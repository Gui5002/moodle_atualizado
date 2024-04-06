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
        let btn = $(this).attr('data-btn');
        $("#avaliador_afast_ModalLabel").html(header);

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
                $(".btnEvento").html(btn);
                $(".btnEvento").attr('id', 'id_'+btn);
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
                    $('#avaliador_afast_Modal').modal('hide');
                    $('.msgsuccess').html(dados.msg);
                    setTimeout(function () {
                        $('#msgSuccess').modal('show');
                    }, 100);
                    // $(location).attr('href', wwwroot+'/blocks/eva_barema_bolsa/avaliadores.php');
            }
        });
}

//==================== ACAO DO ONMOUSECLICK ===============================
function add_alter_avaliador_afast(btnEvent) {
    const wwwroot = $(btnEvent).attr('data-root');
    
    
    //=============== FAZ A ALTERAÇÃO - MODAL EDIT EVALIADORES =====================================
    if (btnEvent.id === 'id_Alterar') {

        let id = $('.editinputavaliadores').attr('data-id');
        let coluna_user_id = $('.editinputavaliadores').attr('data-colunauserid');
        let user_id = $('.juridico_gestao').val();
        let radiobutton_eixo = $("input[name='distribuicao']:checked").val();
        //
        $.ajax({
            url: wwwroot + '/blocks/eva_barema_bolsa/condicional.php?id='+id+'&user_id=' + user_id +'&coluna_user_id='+ coluna_user_id +'&eixo=' + radiobutton_eixo,
            data: 'acao=editavaliadores',
            success: function (resp) {
                var dados = JSON.parse(resp);
                console.log(dados)
                if (dados) {
                    $('#avaliador_afast_Modal').modal('hide');
                    $('.msgsuccess').html(dados.msg)
                    setTimeout(function () {
                        $('#msgSuccess').modal('show');
                    }, 100);
                }
            }
        });
    }
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
                console.log(dados);

                $('#avaliador_afast_Modal').modal('hide');
                $('.msgsuccess').html('Avaliador adicionado com Sucesso.')
                setTimeout(function () {
                    $('#msgSuccess').modal('show');
                }, 100);

            }
        });
    }

    //================== FAZ A ADIÇÃO DE AVALIADORES ==========================

    if (btnEvent.id === 'id_Excluir') {
        alert(id);
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
