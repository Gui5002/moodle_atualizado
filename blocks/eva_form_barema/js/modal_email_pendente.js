
$(".envioEmailPendentePos").click(function (event){
    const wwwroot = $(this).attr('data-root');
    let id = $(this).attr('data-id');
    let tempo = $(this).attr('data-tempo');

    $.ajax({
        url: wwwroot+'/blocks/eva_form_barema/condicional.php?id='+id,
        data: 'acao=emailpendente',
        success: function ( resposta ) {
            var dados = JSON.parse(resposta);

            var existir = (dados.qt_alunos > 1) ? "Existem" : "Existe";

            $('#msg_para').html("<b>Para:</b> "+dados.email);
            $('.msg').html(existir + " <b> "+dados.qt_alunos+" alunos</b> pendentes da Pós graduação para fazer avaliação.");
            $(".msg_avaliador").html("<b>Avaliador:</b> "+ dados.avaliador);
            $(".msg_curso").html("<b>Curso:</b> " + dados.curso);
            $(".msg_quiz").html("<b>Atividade:</b> " + dados.quiz);
            // $(".msg_qt_alunos").html("<b>Quantidade de alunos:</b> " + dados.qt_alunos);
            $(".msg_link").html("<b>Link:</b> " + dados.url_link);

            $("#id_tbavaliador").val(dados.id_tb_avaliador);
            $("#id_qtalunos").val(dados.qt_alunos);

        }
    });

});

$(".modalClose, .modalFechar").click(function (){
    // var wwwroot = $(this).attr('data-root');
    window.location.href = "/blocks/eva_form_barema/gerencia.php?admin=curso";
});

let global_id_atual ;
$(".substituirAvaliador").click(function (e){
    e.preventDefault();
    const wwwroot = $(this).attr('data-root');
    let idatribuicao = $(this).attr('data-id');
    global_id_atual = $(this).attr('data-id');

    $.ajax({
        url: wwwroot+'/blocks/eva_form_barema/condicional.php?id='+idatribuicao,
        data: 'acao=preparandoSubstituicao',
        success: function (resp) {
            var daddosubst =  JSON.parse(resp);

            $("#js_avaliador").html('<b>'+daddosubst.avaliador+'</b>');
            $('#js_curso').html(daddosubst.curso);
            $("#js_total_alunos").html(daddosubst.total_alunos);
            $("#js_qtd_avaliados").html(daddosubst.qtd_avaliados);

        }
    });
});

$(".substituto_user_id").change(function (e) {
    e.preventDefault();
    var user_id = $(this).val();
    $(".btnSubstituir").attr("disabled", false);
});

$("#id_substituicao").click(function (event){
    event.preventDefault();
    const wwwroot = $(this).attr('data-root');
    var idsubstituto = $(".substituto_user_id").val();


    $.ajax({
        url: wwwroot+'/blocks/eva_form_barema/condicional.php?id='+idsubstituto+'&idatribuicao='+global_id_atual,
        data: 'acao=substituicao',

        beforeSend: function () {
            load_finalizando()
        },
        success: function (resp) {
            var dados = JSON.parse(resp);

            if (dados.status) {
                $(".substituicao").removeClass("d-none");
                setTimeout(function () {
                    $(".substituicao").html('<span class="text-success font-weight-bold">Avaliador Substituido.</span>');
                }, 2000);

                $.ajax({
                    url: wwwroot+'/blocks/eva_form_barema/condicional.php?tb_avaliador_id=' + dados.tbavaliadorid,
                    data: 'acao=emailparasubstituido',

                    beforeSend: function () {
                        load_finalizando();
                    },
                    success: function (resp) {
                        var emailsub = JSON.parse(resp);

                        if (emailsub){
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

function load_finalizando (){
    // $(".modalFechar").attr("disabled", true);
    // $(".btnSubstituir").attr("disabled", true);
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