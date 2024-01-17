
const wwwroot = $("#id_wwwroot").val();
var apresenta = $('.resultados');
var avaliadorid = $('.aluno_id').attr('data-avaliador_id');
var cursoid = $('.aluno_id').attr('data-curso_id');
var quizid = $('.aluno_id').attr('data-quiz_id');

apresenta.hide().html('<sapn style="color: green">Aguarde, Carregando...</sapn>');

//==================Aqui cria o um select de autocomplete na tela de avaliacao do barema (campo Nome)====================

const idaluno = document.querySelector("#id_aluno"); // id do campo input Nome
const alunoList = document.querySelector("#alunos_list"); //id que recebe a lista ul li

var url = wwwroot+'/blocks/eva_form_barema/completar.php?acao=completar&userid='+avaliadorid+'&quizid='+quizid;
let alunos = [];
(async () => {
    const res = await fetch(url)
    alunos = await res.json()
})()


const searchAlunos = alunoText => {
    let matches = alunos.filter(aluno => {
        const regex = new RegExp(`^${alunoText}`, 'gi')
        return aluno.match(regex);
    });
    if (alunoText.length === 0){
        matches = [];
    }
    outputHTML(matches);
}
function outputHTML (matches) {
    if (matches.length > 0) {
        var li = '';
        for (var i=0; i < matches.length; i++){
            li += "<li onclick='aluno_lista("+JSON.stringify(matches[i])+")' class='list-group-item list-group-item-action itemList'>" + matches[i] + "</li>";
        }
        alunoList.innerHTML = "<ul class='list-group'>"+li+"</ul>";

    }else {
        alunoList.innerHTML = '';
    }
}

//========================LIMPA CAMPO======================================

$("#id_aluno").click(function (){
    $("#id_aluno").val("");
    var options = '';
    $('.atividades').html(options);
    $('.respostacorreta').html(options);
    $('.enviados').html(options);
})

// ==========================RECOLHE OS FORUMARIOS======================

$("#id_avaliador_pass").click(function (){
    var exp = '';
    exp = $(".fheader").attr("aria-expanded");

    if (exp === "true") {
        $("#id_avaliadorrandpass").addClass("collapsed");
        $("a").attr("aria-expanded", "false");
    } else {
        $("#id_avaliadorrandpass").removeClass("collapsed");
        $("a").attr("aria-expanded", "true");
    }
});

$("#id_aluno_pass").click(function (){
    var exp = '';
    exp = $(".fheader").attr("aria-expanded");
    if (exp === "true") {
        $("#id_alunorandpass").addClass("collapsed");
        $("a").attr("aria-expanded", "false");
    } else {
        $("#id_alunorandpass").removeClass("collapsed");
        $("a").attr("aria-expanded", "true");
    }
});

$("#id_dadoscurso_pass").click(function (){
    var exp = '';
    exp = $(".fheader").attr("aria-expanded");

    if (exp === "true") {
        $("#id_dadoscursorandpass").addClass("collapsed");
        $("a").attr("aria-expanded", "false");
    } else {
        $("#id_dadoscursorandpass").removeClass("collapsed");
        $("a").attr("aria-expanded", "true");
    }
});

$("#id_recursos_pass").click(function (){
    var exp = '';
    exp = $(".fheader").attr("aria-expanded");

    if (exp === "true") {
        $("#id_recursosrandpass").addClass("collapsed");
        $("a").attr("aria-expanded", "false");
    } else {
        $("#id_recursosrandpass").removeClass("collapsed");
        $("a").attr("aria-expanded", "true");
    }
});

$("#id_envio_pass").click(function (){
    var exp = '';
    exp = $(".fheader").attr("aria-expanded");

    if (exp === "true") {
        $("#id_enviorandpass").addClass("collapsed");
        $("a").attr("aria-expanded", "false");
    } else {
        $("#id_enviorandpass").removeClass("collapsed");
        $("a").attr("aria-expanded", "true");
    }
});


$("#id_analise_pass").click(function (){
    var exp = '';
    exp = $(".fheader").attr("aria-expanded");

    if (exp === "true") {
        $("#id_analiserandpass").addClass("collapsed");
        $("a").attr("aria-expanded", "false");
    } else {
        $("#id_analiserandpass").removeClass("collapsed");
        $("a").attr("aria-expanded", "true");
    }
});

function valida_nome_aluno(element) {
    const wwwroot = $("#id_wwwroot").val();

    var value  = $("#id_aluno").val();
    // alert(value);
    $.ajax({
        url: wwwroot +'/blocks/eva_form_barema/completar.php',
        data: 'acao=validanome&valor='+value,
        success: function ( resposta ) {
            if (resposta) {
                // $("#id_aluno").addClass("is-invalid");
                // $("#id_error_aluno").html( resposta );


            }
            // return resposta
        }
    });
}

// =================INVALIDA OS CAMPOS VAZIOS AO SUBMETER=========================

document.getElementById('id_submitbutton').addEventListener('click', function(ev) {
    // function verifica_campo_vazio_submit(ev) {
    try {
        var myValidator = validate_block_eva_form;
    } catch(e) {
        return true;
    }
    if (typeof window.tinyMCE !== 'undefined') {
        window.tinyMCE.triggerSave();
    }
    if (!myValidator()) {
        ev.preventDefault();
    }
    // }
});


function validate_block_eva_form() {
    var ret = true;
    var frm = document.getElementById('mform1')
    var first_focus = false;

    // ret = validate_block_eva_form_barema_output_form_barema(frm.elements['aluno']) && ret;

    // if (!ret && !first_focus) {
    //     first_focus = true;
    //     Y.use('moodle-core-event', function () {
    //         Y.Global.fire(M.core.globalEvents.FORM_ERROR, {
    //             formid: 'mform1',
    //             elementid: 'id_error_aluno'
    //         });
    //         $("#id_aluno").focus();
    //     });
    // }

    return ret;
}

// ======================EVENTO MOUSE - CAMPOS VAZIOS====================

function validate_block_eva_form_barema_output_form_barema(element) {
    if (undefined == element) {
        //required element was not found, then let form be submitted without client side validation
        return true;
    }
    var value = '';
    var frm = element.parentNode;
    if ((undefined != element.name) && (frm != undefined)) {
        while (frm && frm.nodeName.toUpperCase() != "FORM") {
            frm = frm.parentNode;
        }
        var definenome = element.name
        value = frm.elements[''+definenome].value;
        if (value == '') {
            $('#id_'+definenome).addClass("is-invalid");
            return false;
        }else {
            $('#id_'+definenome).removeClass("is-invalid");
            return true;
        }
    } else {
        //element name should be defined else error msg will not be displayed.
        return true;
    }
}

$(function (){
    var toastTrigger = document.getElementById('id_submitbutton')
    // var toastLiveExample = document.getElementById('id_submitbutton')
    if (toastTrigger) {
        toastTrigger.addEventListener('click', function () {
            alert('testeeeee')
            let qtd = $("#id_qtd").html();
            let radiobutton = [];
            radiobutton[1] = $("input[name='nt_faixa_1']:checked").val();
            radiobutton[2] = $("input[name='nt_faixa_2']:checked").val();
            radiobutton[3] = $("input[name='nt_faixa_3']:checked").val();
            radiobutton[4] = $("input[name='nt_faixa_4']:checked").val();
            radiobutton[5] = $("input[name='nt_faixa_5']:checked").val();

            for (var i=1; i <= qtd; i++) {

                var error = verificacamposvazio(radiobutton[i], i);

                if (error > 0) {
                    return false;
                }
                // if (i == qtd) {
                //     var toast = new bootstrap.Toast(toastLiveExample)
                //     toast.show()
                // }
            }
            function verificacamposvazio(radiobutton, i) {
                let error = 0;

                if (radiobutton == undefined){
                    error += 1;
                    $(".nt_faixa_"+i).addClass('is-invalid');
                    return error;
                }else{
                    $(".nt_faixa_"+i).removeClass('is-invalid');
                }
            }

        });
    }

});




$("input[name=nt_faixa_1]").on('change', function() {
    var faixa = $(this).val();
    for (var i = 0; i<=100; i++){
        if (faixa == i){
            $("#id_nota1").html(faixa);
            soma_nota_final();
        }
    }
});
$("input[name=nt_faixa_2]").on('change', function() {
    var faixa = $(this).val();
    for (var i = 0; i<=100; i++){
        if (faixa == i){
            $("#id_nota2").html(faixa);
            soma_nota_final();
        }
    }
});

$("input[name=nt_faixa_3]").on('change', function() {
    var faixa = $(this).val();
    for (var i = 0; i<=100; i++){
        if (faixa == i){
            $("#id_nota3").html(faixa);
            soma_nota_final();
        }
    }
});

$("input[name=nt_faixa_4]").on('change', function() {
    var faixa = $(this).val();
    for (var i = 0; i<=100; i++){
        if (faixa == i){
            $("#id_nota4").html(faixa);
            soma_nota_final();
        }
    }
});

$("input[name=nt_faixa_5]").on('change', function() {
    var faixa = $(this).val();
    for (var i = 0; i<=100; i++){
        if (faixa == i){
            $("#id_nota5").html(faixa);
            soma_nota_final();
        }
    }
});

function soma_nota_final(){
    var idtotal = $("#id_qtd").html();

    var fx = 0;
    var soma = 0;
    var ch = 1;
    let radiobutton = [];
    console.log(idtotal)
    for (var i = 1; i <= idtotal; i++) {
        fx = $('#id_nota'+i).html();
        soma = parseInt(soma) + parseInt(fx);
        radiobutton[i] = $("input[name=nt_faixa_"+i+"]:checked").val();
        if (radiobutton[i]){
            if (ch == idtotal){
                $(".btn-none").removeClass("d-none");
            }
            ch++
        }
    }
    var percent = $("#id_desconto").html();
    if (percent){
        var perc = menos_porcentos(soma, percent);
        soma = perc;
    }
    $("#id_nota_final").html(soma);
    $("#nota_final").val(soma);
}

function menos_porcentos(soma, percent) {

    var perc = percent / 100;
    var res_perc = perc * soma;
    var resultado = soma + (res_perc);
    var arredonda = Math.round(resultado)

    return arredonda;
}


