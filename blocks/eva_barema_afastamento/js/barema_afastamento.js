
const wwwroot = $("#id_wwwroot").val();
var apresenta = $('.resultados');

apresenta.hide().html('<sapn style="color: green">Aguarde, Carregando...</sapn>');


$(function (){
    var toastTrigger = document.getElementById('id_submitbuttonBtn')
    var toastLiveExample = document.getElementById('id_submitbutton')
    if (toastTrigger) {
        toastTrigger.addEventListener('click', function () {

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
                if (i == qtd) {
                    var toast = new bootstrap.Toast(toastLiveExample)
                    toast.show()
                }
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



// //==================Aqui cria o um select de autocomplete na tela de avaliacao do barema (campo Nome)====================
//
// const idaluno = document.querySelector("#id_aluno"); // id do campo input Nome
// const alunoList = document.querySelector("#alunos_list"); //id que recebe a lista ul li
//
// var url = wwwroot+'/blocks/eva_form_barema/completar.php?acao=completar&userid='+avaliadorid+'&quizid='+quizid;
// let alunos = [];
// (async () => {
//     const res = await fetch(url)
//     alunos = await res.json()
// })()
//
// const searchAlunos = alunoText => {
//     let matches = alunos.filter(aluno => {
//         const regex = new RegExp(`^${alunoText}`, 'gi')
//         return aluno.match(regex);
//     });
//     if (alunoText.length === 0){
//         matches = [];
//     }
//     outputHTML(matches);
// }
// function outputHTML (matches) {
//     if (matches.length > 0) {
//         var li = '';
//         for (var i=0; i < matches.length; i++){
//             li += "<li onclick='aluno_lista("+JSON.stringify(matches[i])+")' class='list-group-item list-group-item-action itemList'>" + matches[i] + "</li>";
//         }
//         alunoList.innerHTML = "<ul class='list-group'>"+li+"</ul>";
//
//     }else {
//         alunoList.innerHTML = '';
//     }
// }
// function aluno_lista(aluno) {
//     $("#id_aluno").val(aluno)
//     alunoList.innerHTML = '';
//     $.ajax({
//         url: wwwroot + '/blocks/eva_form_barema/completar.php',
//         data: 'acao=pesquisar&valor=' + aluno,
//         success: function (resp) {
//             var aluno = JSON.parse(resp);
//             $('#id_estudante').val(aluno.id);
//
//
//             var nome_inexistente = $('#id_aluno').val();
//             var idestudante = $('#id_estudante').val();
//             var idbarema = $('#idbarema').val();
//
//             $.ajax({
//                 url: wwwroot+'/blocks/eva_form_barema/completar.php',
//                 data: 'acao=buscaperguntaresposta&idbarema='+idbarema+'&idavaliador='+avaliadorid+'&id_curso='+cursoid+'&id_quiz='+quizid+'&idestudante='+idestudante+'&nome_naoexiste='+nome_inexistente,
//                 dataType: 'json',
//                 success: function ( resposta ) {
//                     console.log(resposta)
//                     if (resposta.question) {
//                         $('.boxes').css("overflow-y", "scroll");
//                         $('.atividades').html('<div class="alert alert-secondary" role="alert">'+resposta.question+'</div>');
//                         $('.respostacorreta').html('<div class="alert fz14 ml-6" role="alert" style="background-color: rgba(229,229,229,0.61)">' +
//                             '<b>Resposta Ideal:</b></br><p>'+resposta.responsecorreta+'</p> </div>');
//                         $('.enviados').css("overflow-y", "scroll");
//                         $('.enviados').html('<div class="alert alert-success" role="alert">'+resposta.response+'</div>');
//                     }
//                     if (resposta.status){
//                         $('.atividades').html('<div class="alert alert-warning" role="alert"><strong>Esse Aluno já foi avaliado!</strong></div>');
//                     }
//                     if (resposta.error){
//                         $('.atividades').html('<div class="alert alert-warning" role="alert"><strong>Aluno não encontrado!</strong></div>');
//                     }
//                 }
//             });
//         }
//     });
// }
// idaluno.addEventListener('input', () => searchAlunos(idaluno.value));
//
// //========================LIMPA CAMPO======================================
//
// $("#id_aluno").click(function (){
//     $("#id_aluno").val("");
//     var options = '';
//     $('.atividades').html(options);
//     $('.respostacorreta').html(options);
//     $('.enviados').html(options);
// })
//
// // ==========================RECOLHE OS FORUMARIOS======================
//
// // $("#id_avaliador_pass").click(function (){
// //     var exp = '';
// //     exp = $(".fheader").attr("aria-expanded");
// //
// //     if (exp === "true") {
// //         $("#id_avaliadorrandpass").addClass("collapsed");
// //         $("a").attr("aria-expanded", "false");
// //     } else {
// //         $("#id_avaliadorrandpass").removeClass("collapsed");
// //         $("a").attr("aria-expanded", "true");
// //     }
// // });
// //
// // $("#id_aluno_pass").click(function (){
// //     var exp = '';
// //     exp = $(".fheader").attr("aria-expanded");
// //     if (exp === "true") {
// //         $("#id_alunorandpass").addClass("collapsed");
// //         $("a").attr("aria-expanded", "false");
// //     } else {
// //         $("#id_alunorandpass").removeClass("collapsed");
// //         $("a").attr("aria-expanded", "true");
// //     }
// // });
// //
// // $("#id_dadoscurso_pass").click(function (){
// //     var exp = '';
// //     exp = $(".fheader").attr("aria-expanded");
// //
// //     if (exp === "true") {
// //         $("#id_dadoscursorandpass").addClass("collapsed");
// //         $("a").attr("aria-expanded", "false");
// //     } else {
// //         $("#id_dadoscursorandpass").removeClass("collapsed");
// //         $("a").attr("aria-expanded", "true");
// //     }
// // });
// //
// // $("#id_recursos_pass").click(function (){
// //     var exp = '';
// //     exp = $(".fheader").attr("aria-expanded");
// //
// //     if (exp === "true") {
// //         $("#id_recursosrandpass").addClass("collapsed");
// //         $("a").attr("aria-expanded", "false");
// //     } else {
// //         $("#id_recursosrandpass").removeClass("collapsed");
// //         $("a").attr("aria-expanded", "true");
// //     }
// // });
// //
// // $("#id_envio_pass").click(function (){
// //     var exp = '';
// //     exp = $(".fheader").attr("aria-expanded");
// //
// //     if (exp === "true") {
// //         $("#id_enviorandpass").addClass("collapsed");
// //         $("a").attr("aria-expanded", "false");
// //     } else {
// //         $("#id_enviorandpass").removeClass("collapsed");
// //         $("a").attr("aria-expanded", "true");
// //     }
// // });
// //
// //
// // $("#id_analise_pass").click(function (){
// //     var exp = '';
// //     exp = $(".fheader").attr("aria-expanded");
// //
// //     if (exp === "true") {
// //         $("#id_analiserandpass").addClass("collapsed");
// //         $("a").attr("aria-expanded", "false");
// //     } else {
// //         $("#id_analiserandpass").removeClass("collapsed");
// //         $("a").attr("aria-expanded", "true");
// //     }
// // });
//
// function valida_nome_aluno(element) {
//     const wwwroot = $("#id_wwwroot").val();
//
//     var value  = $("#id_aluno").val();
//     // alert(value);
//     $.ajax({
//         url: wwwroot +'/blocks/eva_form_barema/completar.php',
//         data: 'acao=validanome&valor='+value,
//         success: function ( resposta ) {
//             if (resposta) {
//                 // $("#id_aluno").addClass("is-invalid");
//                 // $("#id_error_aluno").html( resposta );
//
//
//             }
//             // return resposta
//         }
//     });
// }
//
// // =================INVALIDA OS CAMPOS VAZIOS AO SUBMETER=========================
//
// document.getElementById('id_submitbutton').addEventListener('click', function(ev) {
//     // function verifica_campo_vazio_submit(ev) {
//     try {
//         var myValidator = validate_block_eva_form;
//     } catch(e) {
//         return true;
//     }
//     if (typeof window.tinyMCE !== 'undefined') {
//         window.tinyMCE.triggerSave();
//     }
//     if (!myValidator()) {
//         ev.preventDefault();
//     }
//     // }
// });
//
//
// function validate_block_eva_form() {
//     var ret = true;
//     var frm = document.getElementById('mform1')
//     var first_focus = false;
//
//     ret = validate_block_eva_form_barema_output_form_barema(frm.elements['aluno']) && ret;
//
//     if (!ret && !first_focus) {
//         first_focus = true;
//         Y.use('moodle-core-event', function () {
//             Y.Global.fire(M.core.globalEvents.FORM_ERROR, {
//                 formid: 'mform1',
//                 elementid: 'id_error_aluno'
//             });
//             $("#id_aluno").focus();
//         });
//     }
//
//     return ret;
// }
//
// // ======================EVENTO MOUSE - CAMPOS VAZIOS====================
//
// function validate_block_eva_form_barema_output_form_barema(element) {
//     if (undefined == element) {
//         //required element was not found, then let form be submitted without client side validation
//         return true;
//     }
//     var value = '';
//     var frm = element.parentNode;
//     if ((undefined != element.name) && (frm != undefined)) {
//         while (frm && frm.nodeName.toUpperCase() != "FORM") {
//             frm = frm.parentNode;
//         }
//         var definenome = element.name
//         value = frm.elements[''+definenome].value;
//         if (value == '') {
//             $('#id_'+definenome).addClass("is-invalid");
//             return false;
//         }else {
//             $('#id_'+definenome).removeClass("is-invalid");
//             return true;
//         }
//     } else {
//         //element name should be defined else error msg will not be displayed.
//         return true;
//     }
// }
// document.getElementById('id_aluno').addEventListener('blur', function (ev) {
//     // valida_nome_aluno(ev.target);
//     validate_block_eva_form_barema_output_form_barema(ev.target);
// });
// document.getElementById('id_aluno').addEventListener('change', function (ev) {
//     // valida_nome_aluno(ev.target);
//     validate_block_eva_form_barema_output_form_barema(ev.target);
// });

// function faixa_pontos(val) {
//     var faixa = $("#id_f1"+id).val();
//     alert(val);
//     for (var i = 0; i<=30; i++){
//         if (faixa == i){
//             $("#id_nota"+id).html(faixa);
//             soma_nota_final();
//         }
//     }
// }

$("input[name=nt_faixa_1]").on('change', function() {
    var faixa = $(this).val();
    for (var i = 0; i<=100; i++){
        if (faixa == i){
            $("#idnota1").html(faixa);
            soma_nota_final();
        }
    }
});
$("input[name=nt_faixa_2]").on('change', function() {
    var faixa = $(this).val();
    for (var i = 0; i<=100; i++){
        if (faixa == i){
            $("#idnota2").html(faixa);
            soma_nota_final();
        }
    }
});

$("input[name=nt_faixa_3]").on('change', function() {
    var faixa = $(this).val();
    for (var i = 0; i<=100; i++){
        if (faixa == i){
            $("#idnota3").html(faixa);
            soma_nota_final();
        }
    }
});

$("input[name=nt_faixa_4]").on('change', function() {
    var faixa = $(this).val();
    for (var i = 0; i<=100; i++){
        if (faixa == i){
            $("#idnota4").html(faixa);
            soma_nota_final();
        }
    }
});

$("input[name=nt_faixa_5]").on('change', function() {
    var faixa = $(this).val();
    for (var i = 0; i<=100; i++){
        if (faixa == i){
            $("#idnota5").html(faixa);
            soma_nota_final();
        }
    }
});

function soma_nota_final(){
    var idtotal = $("#id_qtd").html();
    var fx = 0;
    var soma = 0;
    for (var i = 1; i <= idtotal; i++) {
        fx = $('#idnota'+i).html();
        soma = parseInt(soma) + parseInt(fx);
    }

    $("#idnota_final").html(soma);
    $("#nota_final").val(soma);

}
// .parent().find("input[name=faixa1]:checked").change();

