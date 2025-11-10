
$(".envioEmailPendente").click(function (event){
    const wwwroot = $(this).attr('data-root');
    let id = $(this).attr('data-id');
    let tempo = $(this).attr('data-tempo');

    $.ajax({
        url: wwwroot+'/blocks/eva_barema_afastamento/condicional.php?id='+id,
        data: 'acao=emailpendente',
        success: function ( resposta ) {
            var dados = JSON.parse(resposta);
            if (tempo > 1) {
                var texto = 'Faltam <b>' + tempo + ' dias</b>';
            }else if (tempo == 1){
                var texto = 'Falta <b>' + tempo + ' dia</b>';
            }else{
                var texto = '<b>Ultimo dia</b>'
            }

            $('#msg_para').html("<b>Para:</b> "+dados.email);
            $('.msg').html(texto+" para fazer a avaliação pendente relativa ao Afastamentos. <br> Clique no link abaixo para visualizá-la.");
            $(".msg_anteprojeto").html("<b>Anteprojeto:</b> " + dados.anteprojeto);
            $(".msg_avaliador").html("<b>Avaliador:</b> "+ dados.avaliador);
            $(".msg_link").html("<b>Link:</b> " + dados.url_link);

            $("#idatribuicao").val(dados.idatribuicao);
            $("#idtempo").val(tempo);
        }
    });

});

let globalidatribuicao ;
$(".exchangeAvaliador").click(function (event){
    event.preventDefault();
    const wwwroot = $(this).attr('data-root');
    globalidatribuicao = $(this).attr('data-id');
    let idatribuicao = $(this).attr('data-id');


    $.ajax({
        url: wwwroot+'/blocks/eva_barema_afastamento/condicional.php?id='+idatribuicao,
        data: 'acao=preparandoSubstituicao',
        success: function ( resposta ) {
            var dados = JSON.parse(resposta);

            console.log(dados)
            var eixo = (dados.destribuicao == 'j') ? 'Jurídico' : 'Gestão'

            $('#js_anteprojeto').html(dados.anteprojeto + ' - Distribuição eixo :<b> '+eixo+' </b>');
            $("#js_avaliador").html(dados.avaliador);
            $("#id_qt_dia").val(dados.qt_dia);
            $("#id_atribuicao").html(idatribuicao);

        }
    });
});

$("#user_id").change(function (event){
    event.preventDefault();
    var user_id = $(this).val();

    $.ajax({
        url:'/blocks/eva_barema_afastamento/condicional.php?id='+user_id+'&idatribuicao='+globalidatribuicao,
        data: 'acao=verificastatus',

        beforeSend: function () {
            status_loading();
        },
        success: function ( resposta ) {
            var dados = JSON.parse(resposta);
            var restrict = '' +

            console.log(dados);

            $(".user_restrict").addClass("d-none");
            if (dados.status){
                setTimeout(function (){
                    $(".load_1").html(' <b class="text-success"> Aceito</b>');
                    $(".selectsubstituto").attr("disabled", false);
                    $(".btnSubstituir").attr("disabled", false);
                }, 300)
            }else{
                setTimeout(function (){
                    $(".load_1").html(' <b class="text-danger"> Negado</b>');
                    $(".selectsubstituto").attr("disabled", false);
                    $(".btnSubstituir").attr("disabled", true);
                    restrict = '' +
                        '<div class="alert alert-info font-weight-bold" role="alert">' +
                        '  Esse Avaliadores não tem eixo Jurídico!' +
                        '</div>';
                    $(".user_restrict").removeClass("d-none");
                    $(".user_restrict").html(restrict);
                }, 300)
            }
            if (dados.status == 'restric'){
                setTimeout(function (){
                    $(".load_1").html(' <b class="text-danger"> Negado</b>');
                    $(".selectsubstituto").attr("disabled", false);
                    $(".btnSubstituir").attr("disabled", true);
                    restrict = '' +
                        '<div class="alert alert-warning font-weight-bold" role="alert">' +
                        '  Esse Avaliador já possui esse Anteprojeto!' +
                        '</div>';
                    $(".user_restrict").removeClass("d-none");
                    $(".user_restrict").html(restrict);

                }, 300)
            }
        }
    });
});

$(".modalFechar, .modalClose").click(function (){
    var userid = $(this).attr('data-userid');
    window.location.href ="/blocks/eva_barema_afastamento/avaliacao.php?admin="+userid;
});

function status_loading (){
    $(".selectsubstituto").attr("disabled", true);
    var spiner = load_animation_spiner()
    $(".load_1").html(spiner)
}

function load_finalizando (){
    $(".modalFechar").attr("disabled", true);
    $(".btnSubstituir").attr("disabled", true);
    var spiner = load_animation_spiner()
    $(".load_2").html(spiner)
}

function load_animation_spiner(spiner){
    var spiner = '' +
        '<div class="spinner-wrapper">\n' +
        '   <div class="spinner-border" role="status">\n' +
        '      <span class="hidden">Loading...</span>\n' +
        '   </div>\n' +
        '</div>'
    return spiner;
}

$("#id_substituicao").click(function (event){
    event.preventDefault();
    var idatribuicao = $("#id_atribuicao").html();
    var qt_dia = $("#id_qt_dia").val();
    var idsubstituto = $(".substituto_user_id").val();


    $.ajax({
        url:'/blocks/eva_barema_afastamento/condicional.php?id_atribuicao='+idatribuicao+'&id_substituto='+idsubstituto+'&qt_dia='+qt_dia,
        data: 'acao=substituicao',

        beforeSend: function () {
            $(".load_2").removeClass("d-none");
            load_finalizando();
        },
        success: function ( resposta ) {
            var dados = JSON.parse(resposta);

            console.log(dados);

            if (dados){
                $(".substituicao").removeClass("d-none");
                setTimeout(function () {
                    $(".substituicao").html('<span class="text-success font-weight-bold">Avaliador Substituido.</span>');
                }, 2000);

                $.ajax({
                    url:'/blocks/eva_barema_afastamento/condicional.php?id_tb_atribuicao='+idatribuicao,
                    data: 'acao=emailparasubstituido',

                    beforeSend: function () {
                        load_finalizando();
                    },
                    success: function ( resp ) {
                        var emailsub = JSON.parse(resp);

                        if (emailsub){
                            $(".emailsubstituido").removeClass("d-none");
                            setTimeout(function () {
                                $(".emailsubstituido").html('<span class="text-success font-weight-bold">Email enviado ao Substituido.</span>');
                            }, 2000);
                        }else{
                            $(".emailsubstituido").removeClass("d-none");
                            setTimeout(function () {
                                $(".emailsubstituido").html('<span class="text-danger font-weight-bold">Email enviado ao Substituido Falhou..!</span>');
                            }, 2000);
                        }

                    }
                });

                $.ajax({
                    url:'/blocks/eva_barema_afastamento/condicional.php?id_tb_atribuicao='+idatribuicao+'&idsubstituto='+idsubstituto,
                    data: 'acao=emailparasubstituto',

                    beforeSend: function () {
                        load_finalizando();
                    },
                    success: function ( resp ) {
                        var emailsubstituto = JSON.parse(resp);

                        if (emailsubstituto){
                            $(".emailsubstituto").removeClass("d-none");
                            setTimeout(function () {
                                $(".emailsubstituto").html('<span class="text-success font-weight-bold">Email enviado ao Substituto.</span>');
                                $(".load_2").addClass("d-none");
                                $(".modalFechar").attr("disabled", false);
                            }, 2000);
                        }else{
                            $(".emailsubstituto").removeClass("d-none");
                            setTimeout(function () {
                                $(".emailsubstituto").html('<span class="text-danger font-weight-bold">Email enviado ao Substituto Falhou..!</span>');
                                $(".load_2").addClass("d-none");
                                $(".modalFechar").attr("disabled", false);
                            }, 2000);
                        }

                    }
                });
            }

        }
    });
});

$(".infosubstituicao").click(function (event){
    event.preventDefault();
    var idatribuicao = $(this).attr('data-idatribuicao');

    $.ajax({
        url: '/blocks/eva_barema_afastamento/condicional.php?id_atribuicao='+idatribuicao,
        data: 'acao=infoSubstituicao',

        success: function (resut) {
            var infosubstituicao = JSON.parse(resut);

            $('#ava_substituido').html('<b>Nome :</b> '+infosubstituicao.avaliador_substituido);
            $('.qt_dia_substituido').html('<b>Dias restantes :</b> '+infosubstituicao.qt_dia_substituido);
            $('.data_substituido').html('<b>Data :</b> '+infosubstituicao.data_substituido);

            $('#ava_substituto').html('<b>Nome :</b> '+infosubstituicao.avaliador_substituto);
            $('.qt_dia_substituto').html('<b>Dias restantes :</b> '+infosubstituicao.qt_dia_substituto);
            $('.data_substituto').html('<b>Data :</b> '+infosubstituicao.data_substituto);

        }
    });

});