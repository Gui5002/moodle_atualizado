/**
 * @author Celio Pereira Batalha
 * email: celio.batalha@gmail.com
 */

$(function (){
    let wwwroot = document.querySelector("#wwwroot").value;
    
    $("#btnanteprojeto").click(function (e) {
        e.preventDefault();
        let wwwroot = document.querySelector("#wwwroot").value;
        let button = document.querySelector("#btnanteprojeto").name;
        let input_anteprojeto = document.querySelector("#anteprojeto").value;

        let input_notacapes = document.querySelector(".notacapes").value;
        let select = document.querySelector("#programa").value;
        let textarea = document.querySelector("#pesquisa").value;

        let chbox_juridico = [];
        $.each($("input[name='cap_eixo_juridico']:checked"), function () {
            chbox_juridico.push($(this).val());
        });

        let chbox_tecnico_juridico = [];
        $.each($("input[name='cap_tecnico_juridico']:checked"), function () {
            chbox_tecnico_juridico.push($(this).val());
        });
        let radiobutton = $("input[name='distribuicao']:checked").val();

        // let arquivo = document.querySelector(".fc-file").value;

        let pathfile = document.querySelector("#nomeArquivo").value;
        let filename = pathfile.split("/")[1];

        var error = verificacamposvazio(input_anteprojeto, select, textarea, pathfile, chbox_juridico, chbox_tecnico_juridico, radiobutton, input_notacapes);

        if (error > 0) {
            return false;
        }

        $.ajax({
            url: wwwroot +'/blocks/eva_barema_bolsa/condicional.php',
            data: 'acao=' + button +
                '&anteprojeto='+ input_anteprojeto +
                '&programa='+ select +
                '&pesquisa='+ textarea +
                '&juridico='+ chbox_juridico +
                '&tecnicojuridico='+ chbox_tecnico_juridico +
                '&path='+ pathfile +
                '&filename='+ filename +
                '&notacapes='+ input_notacapes +
                '&distribuicao='+ radiobutton,

            success: function (resposta) {
                const dados = JSON.parse(resposta);

                if (dados.message == 'fail'){
                    $("#anteprojeto").addClass('is-invalid').focus()
                    $("#invalid_anteprojeto").html('- Esse numero ja existe.')
                }
                if (dados.message == 'success'){
                    $('#id_alert').html('Cadastrado com Sucesso...');
                    $('#id_alert').removeClass('d-none');

                    $(".anteprojeto").val("");
                    var options_programa = '';

                    options_programa += '<option value="">--Selecione--</option>';
                    options_programa += '<option value="Doutorado">Doutorado</option>';
                    options_programa += '<option value="Mestrado">Mestrado</option>';
                    options_programa += '<option value="llm">LL.M.</option>';
                    options_programa += '<option value="pos-graduacao lato sensu">Pós-graduação Lato Sensu</option>';

                    $('#programa').html(options_programa);

                    $("textarea[name='pesquisa']").val("");
                    $("input[name='cap_eixo_juridico']").each(function () {
                        if (this.checked)
                            this.checked = false;
                    });
                    $("input[name='cap_tecnico_juridico']").each(function () {
                        if (this.checked)
                            this.checked = false;
                    });
                    $("input[name='distribuicao']").each(function () {
                        if (this.checked)
                            this.checked = false;
                    });

                    var options_anteprojeto = '';

                    options_anteprojeto += '<option value="">--Selecione--</option>'
                    // options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_302-e.pdf">Anteprojeto_302</option>'

                    if (dados.length > 0 ){
                        for (var i = 0; i < dados.length; i++) {
                            options_anteprojeto += '<option value="' + dados[i].file_path + '">' + dados[i].file_nome + '</option>';
                        }
                    }


                    $('#nomeArquivo').html(options_anteprojeto);

                    // $(".fileanteprojeto").val("");

                    $(".notacapes").val("");

                    $("#atp").html("");
                    $("#prog").html("");
                    $("#pesq").html("");
                    $("#area").html("");
                    $("#file").html("");
                    $(".modal-foot").addClass("hidden");

                    setTimeout(function () {
                        $('#id_alert').addClass('d-none');
                    }, 5000);
                }
            }
        });
    });
    $(".modalFechar").click(function (ev){
          window.location.href = wwwroot + "/blocks/eva_barema_bolsa/create.php?id=1";
    });

    $(".btnEventoPreview").click(function (e) {
        e.preventDefault();
        let wwwroot = document.querySelector("#wwwroot").value;
        let button = document.querySelector("#btnanteprojeto").name;
        let input_anteprojeto = document.querySelector("#anteprojeto").value;
        $("#atp").html(input_anteprojeto);
        
        let select = document.querySelector("#programa").value;
        $("#prog").html(select);
        let textarea = document.querySelector("#pesquisa").value;
        $("#pesq").html(textarea);
        let input_notacapes = document.querySelector(".notacapes").value;
        
        let chbox_juridico = [];
        $.each($("input[name='cap_eixo_juridico']:checked"), function () {
            chbox_juridico.push($(this).attr("data-label"));
            if(chbox_juridico[0] == undefined){
                chbox_juridico[0] = "Eixo Jurídico nao definido";
            }
        });

        
        let chbox_tecnico_juridico = [];
        $.each($("input[name='cap_tecnico_juridico']:checked"), function () {
            chbox_tecnico_juridico.push($(this).attr("data-label"));
            if(chbox_tecnico_juridico[0] == undefined){
                chbox_tecnico_juridico[0] = "Eixo Governaça e Gestão nao definido";
            }
        });
        const areaPrioritaria = chbox_juridico.concat(chbox_tecnico_juridico);
        var li = '';
        for (var i=0; i < areaPrioritaria.length; i++){
            li += '<li>'+ areaPrioritaria[i] + '</li>';
        }
        $("#area").html('<ul>'+li+'</ul>');
        
        let radiobutton = $("input[name='distribuicao']:checked").val();
        
        // let arquivo = document.querySelector(".fc-file").value;
        
        let pathfile = document.querySelector("#nomeArquivo").value;
        var file = '<a target="_blank" href="'+pathfile+'" class="btn" style="margin-left: 20px;">' +
        '<i class="icon fa fa-file-pdf-o"></i> Ver Anteprojeto</a>'
        $("#file").html(file);
        $(".modal-foot").removeClass("hidden");

    });

});

$(".anteprojeto").click(function (){
    $('.anteprojeto').val("");
});

$(".anteprojeto").mask('000-0000')
$(".notacapes").mask('00.0')


function verificacamposvazio(input_anteprojeto, select, textarea, pathfile, chbox_juridico, chbox_tecnico_juridico, radiobutton, input_notacapes){
    let error =0;

    if (input_anteprojeto == ""){
        error += 1;
        $("#anteprojeto").addClass('is-invalid').focus()
    }
    if (select == ""){
        error += 1;
        $("#programa").addClass('is-invalid').focus()
    }
    if (textarea == ""){
        error += 1;
        $("#pesquisa").addClass('is-invalid').focus()
    }
    // if (arquivo == "") {
    //     error += 1;
    //     $(".fc-file").addClass('is-invalid').focus()
    //     $("#invalid_file").html('- Nenhum doc_anteprojetos_pdf foi selecionado.')
    // }

    if (pathfile == "") {
        error += 1;
        $("#nomeArquivo").addClass('is-invalid').focus()
        $("#invalid_file").html('- Nome do Arquivo não foi selecionado.')
    }

    if (chbox_juridico == ""){
        error += 1;
        $(".juridico").addClass('is-invalid').focus()

    }

    if (chbox_tecnico_juridico == ""){
        error += 1;
        $(".tec_juridico").addClass('is-invalid').focus()

    }
    if (radiobutton == undefined){
        error += 1;
        $(".distribuicao").addClass('is-invalid').focus()
    }

    if (input_notacapes == ""){
        error += 1;
        $(".notacapes").addClass('is-invalid').focus()
    }

    return error;
}



