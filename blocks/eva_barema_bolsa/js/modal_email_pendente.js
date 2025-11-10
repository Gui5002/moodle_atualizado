
$(".envioEmailPendente").click(function (event){
    const wwwroot = $(this).attr('data-root');
    let id = $(this).attr('data-id');
    let tempo = $(this).attr('data-tempo');

    $.ajax({
        url: wwwroot+'/blocks/eva_barema_bolsa/condicional.php?id='+id,
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
            $('.msg').html(texto+" para fazer a avaliação pendente relativa ao edital de concessão de bolsas de estudo. <br> Clique no link abaixo para visualizá-la.");
            $(".msg_anteprojeto").html("<b>Anteprojeto:</b> " + dados.anteprojeto);
            $(".msg_avaliador").html("<b>Avaliador:</b> "+ dados.avaliador);
            $(".msg_link").html("<b>Link:</b> " + dados.url_link);

            $("#idatribuicao").val(dados.idatribuicao);
            $("#idtempo").val(tempo);

        }
    });

});
