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
        let filename = pathfile.split("/")[5];


        var error = verificacamposvazio(input_anteprojeto, select, textarea, pathfile, chbox_juridico, chbox_tecnico_juridico, radiobutton, input_notacapes);

        // var fd = new FormData();
        // var files = $("#formFile")[0].files[0];
        // fd.append('arquivo', files);


        if (error > 0) {
            return false;
        }
        // $.ajax({
        //     url: wwwroot +'/blocks/eva_barema_afastamento/file_data.php',
        //     type: 'post',
        //     data: fd,
        //     contentType: false,
        //     processData: false,
        //     success: function (response) {
        //     const dadosfiles = JSON.parse(response);
        //
        //         if (dadosfiles.msg == 'error'){
        //             $('#id_alert_w').html(''+dadosfiles.mensagem);
        //             $('#id_alert_w').removeClass('d-none');
        //             setTimeout(function (){
        //                 $('#id_alert_w').addClass('d-none');
        //             },5000);
        //         }
        //         let filename = dadosfiles.nomeArquivo;
        //         let pathfile = dadosfiles.pathfile;
        //
        //         if(dadosfiles.msg == 'salvo'){
        $.ajax({
            url: wwwroot +'/blocks/eva_barema_afastamento/condicional.php',
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

            beforeSend: function () {
                status_loading();
            },
            success: function (resposta) {
                const dados = JSON.parse(resposta);

                console.log(dados);

                if (dados.message == 'fail'){
                    $("#anteprojeto").addClass('is-invalid').focus()
                    $("#invalid_anteprojeto").html('- Esse numero ja existe.')
                }
                if (dados.message == 'success'){
                    var alerta = '<div class="alert alert-primary" role="alert">Cadastrado com Sucesso...</div>';
                    setTimeout(function (){
                        $('.spiner_msg').html(alerta);
                    }, 700);

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
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_302-e.pdf">Anteprojeto_302</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_304-e.pdf">Anteprojeto_304</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_305-e.pdf">Anteprojeto_305</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_313-e.pdf">Anteprojeto_313</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_314-e.pdf">Anteprojeto_314</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_315-e.pdf">Anteprojeto_315</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_316-e.pdf">Anteprojeto_316</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_318-e.pdf">Anteprojeto_318</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_320-e.pdf">Anteprojeto_320</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_322-e.pdf">Anteprojeto_322</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_323-e.pdf">Anteprojeto_323</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_325-e.pdf">Anteprojeto_325</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_326-e.pdf">Anteprojeto_326</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_328-e.pdf">Anteprojeto_328</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_330-e.pdf">Anteprojeto_330</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_333-e.pdf">Anteprojeto_333</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Anteprojeto_335-e.pdf">Anteprojeto_335</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Exposicao_310-e.pdf">Exposicao_310</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Exposicao_321-e.pdf">Exposicao_321</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Exposicao_324-e.pdf">Exposicao_324</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Exposicao_334-e.pdf">Exposicao_334</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Exposicao_336-e.pdf">Exposicao_336</option>'
                    options_anteprojeto += '<option value="doc_anteprojetos_pdf/Exposicao_337-e.pdf">Exposicao_337</option>'

                    $('#nomeArquivo').html(options_anteprojeto);

                    // $(".fileanteprojeto").val("");

                    $(".notacapes").val("");

                    setTimeout(function () {
                        $('.spiner_msg').addClass('d-none');
                    }, 5000);
                }
            }
        });
        //     }
        // }
        // });
    });
    $(".modalFechar").click(function (ev){
        window.location.href = wwwroot + "/blocks/eva_barema_afastamento/create.php?id=1";
    });
});

function status_loading (){
    var spiner = load_animation_spiner()
    $(".spiner_msg").html(spiner)
}

function load_animation_spiner(){
    var spiner = '' +
        '<div class="spinner-wrapper">\n' +
        '   <div class="spinner-border" role="status">\n' +
        '      <span class="hidden">Loading...</span>\n' +
        '   </div>\n' +
        '</div>'
    return spiner;
}


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
        $("#invalid_anteprojeto").html('- O Campo anteprojeto esta vazio.')
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

