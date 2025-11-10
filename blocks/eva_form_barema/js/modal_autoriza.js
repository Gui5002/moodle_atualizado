
$(function (){

    var wwwroot;
    var id_atribuicao;
    var id_aluno;

    $(".autorizacao").click(function (ev) {
        ev.preventDefault()
        wwwroot = $(this).attr('data-root');
        id_atribuicao = $(this).attr("data-id");
        id_aluno = $(this).attr("data-idaluno");
    })

    $("#com-autho").click(function (ev) {
        ev.preventDefault();

        $.ajax({
            url: wwwroot +'/blocks/eva_form_barema/condicional.php',
            data: 'acao=comautorizacao&idatribuicao='+id_atribuicao+'&idaluno='+id_aluno,
            success: function (resp) {
                var dat = JSON.parse(resp);

                if (dat) {
                    $(".autoriza_modal").removeClass("show");
                    refreesh()
                }else{
                    alert("Error... ao Atualizar o Banco de dados")
                }
            }
        });

        // refreesh()
    });

    $("#sem-autho").click(function (ev) {
        ev.preventDefault();

        $.ajax({
            url: wwwroot +'/blocks/eva_form_barema/condicional.php',
            data: 'acao=semautorizacao&idatribuicao='+id_atribuicao+'&idaluno='+id_aluno,
            success: function (resp) {
                var dat = JSON.parse(resp);

                if (dat) {
                    $(".autoriza_modal").removeClass("show");
                    refreesh()
                }else{
                    alert("Error... ao Atualizar o Banco de dados")
                }
            }
        });
    });

    function refreesh(){
      location.replace(window.location.href = "/blocks/eva_form_barema/gerencia.php?admin=alunos&qt_aluno_por_curso="+id_atribuicao);
    }

})

