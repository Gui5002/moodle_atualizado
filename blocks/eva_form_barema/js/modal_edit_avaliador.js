$('.btoggler').addClass('d-none');

$(function (){

    const wwwroot = $('.btnEditModal').attr('data-root');
    let baremaid = $('.btnEditModal').attr('data-baremaid');


    $(".btnEditModal, .btnAdicionarModal, .btnDeleteModal").click(function (event){
        let id = $(this).attr('data-id');
        let iduser = $(this).attr('data-iduser');
        let header = $(this).attr('data-header');
        let btn = $(this).attr('data-btn');
        $("#avaliadorModalLabel").html(header);
        $(".btnEvento").html(btn);
        $(".btnEvento").attr('id', 'id_'+btn);

        $.ajax({
            url: wwwroot+'/blocks/eva_form_barema/completar.php',
            data: 'acao=buscausuarios',
            success: function ( resposta ) {
                var dados = JSON.parse(resposta);
                if (dados) {
                    var options = '<option value="">-- ESCOLHA O AVALIADOR --</option>';
                    $('#id_avaliador_userid').html(options);
                }
                for (var i = 0; i < dados.length; i++) {
                    options += '<option value="'+ dados[i].id +'">' + dados[i].nome + '</option>';
                }
                $('#id_avaliador_userid').html(options);
                $('#id_avaliador_userid option[value='+iduser+']').attr('selected', 'selected');
                $('#id_editavaliador').val(id);
            }
        });

    });

    $(".modalClose, .modalFechar").click(function (ev){
        $(location).attr('href', wwwroot+'/blocks/eva_form_barema/avaliadores.php?baremaid='+baremaid);
    });
});

//==================== ACAO DO ONMOUSECLICK ===============================
function add_alter_avaliador(btnEvent) {
    const wwwroot = $('.btnEditModal').attr('data-root');
    const id = $('.btnEditModal').attr('data-id');
    var baremaid = $('.btnAdicionarModal').attr('data-baremaid');
    var id_user = $('#id_avaliador_userid').val();
    //=============== FAZ A ALTERAÇÃO =====================================
    if (btnEvent.id === 'id_Alterar') {
        let id_avaliador = $('#id_editavaliador').val();
        $.ajax({
            url: wwwroot + '/blocks/eva_form_barema/completar.php?id_avaliador=' + id_avaliador + '&id_user=' + id_user,
            data: 'acao=editavaliadores',
            success: function (resp) {
                var dados = JSON.parse(resp);
                if (dados) {
                    $('#avaliadorModal').modal('hide');
                    $('.id_barema').html(dados.baremaid)
                    setTimeout(function () {
                        $('#msgSuccess').modal('show');
                    }, 100);
                }
            }
        });
    }
    //================== FAZ A ADIÇÃO DE AVALIADORES ==========================
    if (btnEvent.id === 'id_Adicionar') {
        let id_avaliador = $('.selectroot').val();
        $.ajax({
            url: wwwroot + '/blocks/eva_form_barema/completar.php?avaliadorid=' + id_avaliador + '&baremaid=' + baremaid,
            data: 'acao=adicionarvaliador',
            success: function (resp) {
                var dados = JSON.parse(resp);

                $('#avaliadorModal').modal('hide');
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

