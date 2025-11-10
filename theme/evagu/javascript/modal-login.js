$("#login_username, #username").change(function () {
    var caracteres = $(this).val();
    caracteres = caracteres.trim(caracteres);
    var tirar = caracteres.split("@");
    if (tirar[1] == "agu.gov.br" || tirar[1] == "agu.gov" || tirar[1] == "agu") {
        $(this).val(tirar[0]);
    } else {
        $(this).val(caracteres);
    }
});
