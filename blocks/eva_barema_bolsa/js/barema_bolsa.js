
const wwwroot = $("#id_wwwroot").val();
var apresenta = $('.resultados');

apresenta.hide().html('<sapn style="color: green">Aguarde, Carregando...</sapn>');

document.addEventListener("keydown", function(e) {
    if(e.keyCode === 13) {
        e.preventDefault();
    }
});

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

