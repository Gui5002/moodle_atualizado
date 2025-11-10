
const forms = document.querySelectorAll('.solicitacao-validation')

// Loop over them and prevent submission
Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
        }

        form.classList.add('was-validated')
    }, false)
});

$("#id_biblioteca").change(function (event) {

    var externo = document.querySelector(".externo").value
    var regiao = "";
    var esconder = "";
    var acordo = "";
    let tags = '';

    if (externo == 'true' && event.target.value){
        $(".regiao").addClass("d-none");
        $(".checking").removeClass("d-none");
    }else{
        if (event.target.value == "Biblioteca Central de Brasilia") {
            regiao =  "O empréstimo de livros físicos está disponível apenas para membros e servidores devidamente cadastrados na Bilioteca Central e em exercício em Brasília.</br>" +
                " - Outras solicitações, como cópia de capítulos de livros e artigos de periódico <u>não é necessário cadastro na biblioteca</u> ."
            acordo = "<span> Sou de Brasília e estou de acordo com o Regulamento da Biblioteca <a href='https://agudf.sharepoint.com/:b:/r/sites/BibliotecadaEAGU1Regio/Documentos%20Compartilhados/Regulamento%20da%20Biblioteca.pdf?csf=1&web=1&e=I15xOv'>(Portaria nº 4, de 11 de março de 2020).</a></span> "
            $(".regiao").removeClass("d-none");
            $(".checking").addClass("d-none");
            $("#fitem_id_celular").addClass('d-none');
        }
        if (event.target.value == "Biblioteca 1ª Região de Belo Horizonte"){
            regiao =  "O <u>empréstimo de livros físicos</u>  está disponível apenas para membros, servidores e estagiários devidamente cadastrados na Biblioteca Central.</br>"+
                " - Outras solicitações, como cópia de capítulos de livros e artigos de periódico <u>não é necessário cadastro na biblioteca</u> ."
            acordo = "<span>Sou de Belo Horizonte e estou de acordo com o Regulamento da Biblioteca <a href='https://agudf.sharepoint.com/:b:/r/sites/BibliotecadaEAGU1Regio/Documentos%20Compartilhados/Regulamento%20da%20Biblioteca.pdf?csf=1&web=1&e=I15xOv'> (Portaria nº 01, de 13 de Abril de 2018, Publicado no Boletim Ano XXV - Nº16 de Abril de 2018).</a></span> "

            $(".regiao").removeClass("d-none");
            $(".checking").addClass("d-none");
            $("#fitem_id_celular").addClass('d-none')
        }
        if (event.target.value == "Biblioteca 4ª Região de Porto Alegre") {
            var esconder = false
            $(".regiao").addClass("d-none");
            $(".checking").removeClass("d-none");
            checke_celular(esconder);
        }
        if (event.target.value == ""){
            tags = '';
            $(".regiao").html(tags);
        }
    }


    tags = '' +
    ' <div id="fitem_id_1_info" class="form-group row fitem d-">\n' +
    '     <div class="col-md-3"></div>\n' +
    '     <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="static">\n' +
    '         <div class="form-control-static">\n' +
    '             <div class="ml-2" style="font-family: montserrat, verdana, sans-serif">\n' +
    '                 <b>ATENÇÃO! <br></b><p>-'+ regiao +'</p>\n' +
    '              </div>\n' +
    '          </div>\n' +
    '          <div class="form-check">' +
    '              <label for="label"> Para cadastro : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>\n' +
    '              <input class="hidden" type="checkbox" id="label">\n' +
    '          </div>\n' +
    '          <div class="form-check">' +
    '              <input type="checkbox" name="acordado" class="form-check-input acordo" value="sim" id="id_acordo" onclick="checke_celular(this)" style="margin-left: -1px;">\n' +
    '              <label for="id_acordo"> ' + acordo + ' </label>\n' +
    '          </div>\n' +
    '          <div class="form-check d-flex ml-2 mt-3">' +
    '              <p>* A Biblioteca receberá as seguintes informações do usuário logado para fins de cadastro nos sistemas de gerenciamento de acervo: nome completo, e-mail institucional, e-mail alternativo, cargo, lotação e SIAPE, CPF. </p>\n' +
    '          </div>\n' +
    '      </div>\n' +
    '  </div>';

    $(".regiao").html(tags);
})

let acordos = document.querySelector(".acordo").checked
$("#id_submitbutton").click(function () {
    var error = 0;
    let selecte = document.querySelector(".biblioteca").value
    if (selecte == "Biblioteca 4ª Região de Porto Alegre") {
        let chbox_solicitacao = [];
        $.each($(".ch_solicitacao:checked"), function () {
            chbox_solicitacao.push($(this).val());
        });
        error += verificacheck(chbox_solicitacao)
    }
    if ( acordos == "") {
        error += 1
        $("#id_celular").addClass('is-invalid').focus()
    }
    if (error > 0) {
        return false;
    }
})

function checke_celular(event){

    if (event.checked){

        var tel = '' +
'       <div id="fitem_id_celular" class="form-group row fitem">\n' +
'           <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0"></div>\n' +
'           <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">\n' +
'               <label for="id_celular"> Telefone Celular: </label>\n' +
'               <input type="text" name="celular" id="id_celular" class="form-control phone" maxlength="15" onkeyup="handlePhone(event)" required>\n' +
'               <div class="invalid-feedback">- Informar o Celular.</div>\n' +
'           </div>\n' +
'       </div>'


        $(".telefone").html(tel);
    }else{
        $(".telefone").html('');
    }
}

function handlePhone(event) {
    let input = event.target
    console.log(input)
    input.value = phoneMask(input.value)
}

function phoneMask(value) {
    if (!value) return ""
    value = value.replace(/\D/g,'')
    value = value.replace(/(\d{2})(\d)/,"($1) $2")
    value = value.replace(/(\d)(\d{4})$/,"$1-$2")
    return value
}


function verificacheck(chbox_solicitacao) {
    let error =0;
    if (chbox_solicitacao == ""){
        error += 1;
        $(".ch_solicit").addClass('is-invalid').focus()

    }
    return error;
}


