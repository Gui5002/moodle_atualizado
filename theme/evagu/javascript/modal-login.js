$("#login_username, #username").change(function () {
    var caracteres = $(this).val();
    caracteres = caracteres.trim(caracteres);
    var tirar = caracteres.split('@');

    if ((tirar[1] == "agu.gov.br") || (tirar[1] == "agu.gov") || (tirar[1] == "agu")){
        $(this).val(tirar[0]);
    }else{
        $(this).val(caracteres);
    }
})

function toggleDivs() {
    const div1 = document.getElementById('login');
    const div2 = document.getElementById('forgotSenha');
    const div3 = document.getElementById('img-login');
    const hidden = document.getElementsByClassName('forHidden');

    div1.classList.toggle('hidden');
    div2.classList.toggle('hidden');
    div3.classList.toggle('hidden');

    for (let i = 0; i < hidden.length; i++) {
        hidden[i].classList.toggle('hidden');
    }
}
