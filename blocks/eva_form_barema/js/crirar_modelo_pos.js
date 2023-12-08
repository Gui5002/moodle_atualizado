let item =  document.querySelector("#id_items");
let btn =  document.querySelector("#id_preview");

btn.addEventListener("click", function (event) {
    event.preventDefault();
    let valor = document.querySelectorAll(".valor")
    let valores = [].map.call(valor, function (input){
        return input.value;
    });

    var linhas = [
        [valores[0], valores[1], [valores[2], valores[3], valores[4], valores[5], valores[6], valores[7], valores[8], valores[9], valores[10], valores[11]]],
        [valores[12], valores[13], [valores[14], valores[15], valores[16], valores[17], valores[18], valores[19], valores[20], valores[21], valores[22], valores[23]]],
        [valores[24], valores[25], [valores[26], valores[27], valores[28], valores[29], valores[30], valores[31], valores[32], valores[33], valores[34], valores[35]]],
        [valores[36], valores[37], [valores[38], valores[39], valores[40], valores[41], valores[42], valores[43], valores[44], valores[45], valores[46], valores[47]]],
        [valores[47], valores[49], [valores[50], valores[51], valores[52], valores[53], valores[54], valores[55], valores[56], valores[57], valores[58], valores[59]]],
        [valores[60], valores[61], [valores[62], valores[63], valores[64], valores[65], valores[66], valores[67], valores[68], valores[69], valores[70], valores[71]]]
    ];

    var coluna = "";
    var id = 1;
    for (var i = 0; i < item.value; i++){
        var hidden_baixa = linhas[i][2][0] == ''?'d-none':'';
        var hidden_media1 = linhas[i][2][2] == ''?'d-none':'';
        var hidden_media2 = linhas[i][2][4] == ''?'d-none':'';
        var hidden_media3 = linhas[i][2][6] == ''?'d-none':'';
        var hidden_alta = linhas[i][2][8] == ''?'d-none':'';
        coluna +=
            '<tr>'+
                '<td>'+linhas[i][0]+'</td>'+
                '<td>'+linhas[i][1]+'</td>'+
                '<td style="padding-right: 30px;">'+
                    '<ul class="unlist">'+
                        '<li class="form-check">'+
                            '<label class="'+hidden_baixa+'"  for="id_f1'+id+'">'+linhas[i][2][0]+'</label>'+
                            '<input class="custom-radio ntfaixa_'+id+' '+hidden_baixa+'" type="radio" id="id_f1'+id+'" name="nt_faixa_'+id+'" value="'+linhas[i][2][1]+'">'+
                        '</li>'+
                        '<li class="form-check">'+
                            '<label class="'+hidden_media1+'" for="id_f2'+id+'">'+linhas[i][2][2]+'</label>'+
                            '<input class="custom-radio ntfaixa_'+id+' '+hidden_media1+'" type="radio" id="id_f2'+id+'" name="nt_faixa_'+id+'" value="'+linhas[i][2][3]+'">'+
                        '</li>'+
                        '<li class="form-check">'+
                            '<label class="'+hidden_media2+'"  for="id_f3'+id+'">'+linhas[i][2][4]+'</label>'+
                            '<input class="custom-radio ntfaixa_'+id+' '+hidden_media2+'" type="radio" id="id_f3'+id+'" name="nt_faixa_'+id+'" value="'+linhas[i][2][5]+'">'+
                        '</li>'+
                        '<li class="form-check">'+
                            '<label class="'+hidden_media3+'" for="id_f4'+id+'">'+linhas[i][2][6]+'</label>'+
                            '<input class="custom-radio ntfaixa_'+id+' '+hidden_media3+'" type="radio" id="id_f4'+id+'" name="nt_faixa_'+id+'" value="'+linhas[i][2][7]+'">'+
                        '</li>'+
                        '<li class="form-check">'+
                            '<label class="'+hidden_alta+'" for="id_f5'+id+'">'+linhas[i][2][8]+'</label>'+
                            '<input class="custom-radio ntfaixa_'+id+' '+hidden_alta+'" type="radio" id="id_f5'+id+'" name="nt_faixa_'+id+'" value="'+linhas[i][2][9]+'">'+
                        '</li>'+
                    '</ul>'+
                '</td>'+
                '<td class="text-center" id="idnota'+id+'" style="font-size: 19px;"></td>'+
            '</tr>';
        id++;
    }

    $("#id_linhas").html(coluna);

});


// Fetch all the forms we want to apply custom Bootstrap validation styles to
const forms = document.querySelectorAll('.needs-validation')

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

document.addEventListener("keydown", function(e) {
    if(e.keyCode === 13) {
        e.preventDefault();
    }
});

function so_numeros(e){
    var charCode = e.charCode ? e.charCode : e.keyCode;
    // charCode 8 = backspace
    // charCode 9 = tab
    if (charCode != 8 && charCode != 9) {
        // charCode 48 equivale a 0
        // charCode 57 equivale a 9
        if (charCode < 47 || charCode > 58) {
            if (charCode < 96 || charCode > 105){
                return false;
            }
        }
    }
}




item.addEventListener("change", function (event) {
    event.preventDefault();
    let valor = document.querySelector("#id_items").value
    // $(".hides").addClass("d-none");

    var items = '';

    for (let i = 1; i <= valor; i++) {
        if (i == 1){
            var condition = 'true';
            var show  = 'show';
            var colapsed  = '';
        }
        if (i > 1){
            var show = '';
            var condition = 'false';
            var colapsed = 'collapsed';
        }

        items +=
            '<div class="accordion-item">'+
                '<h2 class="accordion-header" id="heading'+i+'">'+
                    '<button class="accordion-button '+colapsed+'" type="button" data-bs-toggle="collapse" data-bs-target="#collapse'+i+'" aria-expanded="'+condition+'" aria-controls="collapse'+i+'">Item '+i+'</button>'+
                '</h2>' +
                '<div id="collapse'+i+'" class="accordion-collapse collapse '+show+'" aria-labelledby="heading'+i+'">' +
                    '<div class="accordion-body">' +
                        '<div class="fcontainer clearfix">' +
                            '<div id="fitem_id_criterio'+i+'" class="form-group row fitem">' +
                                '<div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">' +
                                    '<label class="d-inline word-break " for="id_criterio'+i+'">Critério</label>'+
                                    '<div class="form-label-addon d-flex align-items-center align-self-start">' +
                                        '<div class="text-danger" title="Necessários">' +
                                            '<i class="icon fa ccn-flaticon-warning text-danger fa-fw " title="Necessários" role="img" aria-label="Necessários"></i>' +
                                        '</div>' +
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">' +
                                    '<input type="text" class="form-control valor" name="criterio'+i+'" id="id_notamaxima'+i+'" value="" required>' +
                                    '<div class="invalid-feedback">- O Campo Quesito está vazio.</div>'+
                                '</div>'+
                            '</div>' +
                            '<div id="fitem_id_notamaxima'+i+'" class="form-group row fitem ">'+
                                '<div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">'+
                                    '<label class="d-inline word-break " for="id_notamaxima'+i+'">Nota Maxima</label>'+
                                    '<div class="form-label-addon d-flex align-items-center align-self-start">' +
                                        '<div class="text-danger" title="Necessários">' +
                                            '<i class="icon fa ccn-flaticon-warning text-danger fa-fw " title="Necessários" role="img" aria-label="Necessários"></i>' +
                                        '</div>' +
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">'+
                                    '<input type="text" class="form-control valor" name="notamaxima'+i+'" id="id_notamaxima'+i+'" value="" required>'+
                                    '<div class="invalid-feedback">- O Campo Critério está vazio.</div>'+
                                '</div>'+
                            '</div>' +
                            '<hr>' +
                            '<div id="fitem_id_labelbaixa'+i+'" class="form-group row fitem ">'+
                                '<div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">'+
                                    '<label class="d-inline word-break " for="id_labelbaixa'+i+'">Nome do botão</label>'+
                                    '<div class="form-label-addon d-flex align-items-center align-self-start">' +
                                        '<div class="text-danger" title="Necessários">' +
                                            '<i class="icon fa ccn-flaticon-warning text-danger fa-fw " title="Necessários" role="img" aria-label="Necessários"></i>' +
                                        '</div>' +
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">'+
                                    '<input type="text" class="form-control valor" name="labelbaixa'+i+'" id="id_labelbaixa'+i+'" value="" required>'+
                                    '<div class="invalid-feedback">- O Campo Nome do Botao está vazio.</div>'+
                                '</div>'+
                            '</div>' +
                            '<div id="fitem_id_valorbaixa'+i+'" class="form-group row fitem ">'+
                                '<div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">'+
                                    '<label class="d-inline word-break " for="id_valorbaixa'+i+'">Valor do botão</label>'+
                                    '<div class="form-label-addon d-flex align-items-center align-self-start">' +
                                        '<div class="text-danger" title="Necessários">' +
                                            '<i class="icon fa ccn-flaticon-warning text-danger fa-fw " title="Necessários" role="img" aria-label="Necessários"></i>' +
                                        '</div>' +
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">'+
                                    '<input type="text" class="form-control valor" name="valorbaixa'+i+'" id="id_valorbaixa'+i+'" value="" required onkeydown="return so_numeros(event)">'+
                                    '<div class="invalid-feedback">- O Campo Valor está vazio.</div>'+
                                '</div>'+
                            '</div>' +
                            '<hr>' +
                            '<div id="fitem_id_labelmedia1'+i+'" class="form-group row fitem ">'+
                                '<div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">'+
                                    '<label class="d-inline word-break " for="id_labelmedia1'+i+'">Nome do botão</label>'+
                                    ' <div class="form-label-addon d-flex align-items-center align-self-start"></div>'+
                                '</div>'+
                                '<div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">'+
                                    '<input type="text" class="form-control valor" name="labelmedia1'+i+'" id="id_labelmedia1'+i+'" value="">'+
                                    '<div class="form-control-feedback invalid-feedback" id="id_error_labelmedia1'+i+'"></div>'+
                                '</div>'+
                            '</div>' +
                            '<div id="fitem_id_valormedia1'+i+'" class="form-group row fitem ">'+
                                '<div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">'+
                                    '<label class="d-inline word-break " for="id_valormedia1'+i+'">Valor do botão</label>'+
                                    '<div class="form-label-addon d-flex align-items-center align-self-start"></div>'+
                                '</div>'+
                                '<div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">'+
                                    '<input type="text" class="form-control valor" name="valormedia1'+i+'" id="id_valormedia1'+i+'" value="" onkeydown="return so_numeros(event)">'+
                                    '<div class="form-control-feedback invalid-feedback" id="id_error_valormedia1'+i+'"></div>'+
                                '</div>'+
                            '</div>'+
                            '<hr>' +
                            '<div id="fitem_id_labelmedia2'+i+'" class="form-group row fitem">'+
                                '<div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">'+
                                    '<label class="d-inline word-break " for="id_labelmedia2'+i+'">Nome do botão</label>'+
                                    '<div class="form-label-addon d-flex align-items-center align-self-start"></div>'+
                                '</div>'+
                                '<div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">'+
                                    '<input type="text" class="form-control valor" name="labelmedia2'+i+'" id="id_labelmedia2'+i+'" value="">'+
                                    '<div class="form-control-feedback invalid-feedback" id="id_error_labelmedia2'+i+'"></div>'+
                                '</div>'+
                            '</div>'+
                            '<div id="fitem_id_valormedia2'+i+'" class="form-group row fitem ">'+
                                '<div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">'+
                                    '<label class="d-inline word-break " for="id_valormedia2'+i+'">Valor do botão</label>'+
                                    '<div class="form-label-addon d-flex align-items-center align-self-start"></div>'+
                                '</div>'+
                                '<div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">'+
                                    '<input type="text" class="form-control valor" name="valormedia2'+i+'" id="id_valormedia2'+i+'" value="" onkeydown="return so_numeros(event)">'+
                                    '<div class="form-control-feedback invalid-feedback" id="id_error_valormedia2'+i+'"></div>'+
                                '</div>'+
                            '</div>'+
                            '<hr>' +

                            '<div id="fitem_id_labelmedia3'+i+'" class="form-group row fitem ">'+
                                '<div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">'+
                                    '<label class="d-inline word-break " for="id_labelmedia3'+i+'">Nome do botão</label>'+
                                    '<div class="form-label-addon d-flex align-items-center align-self-start"></div>'+
                                '</div>'+
                                '<div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">'+
                                    '<input type="text" class="form-control valor" name="labelmedia3'+i+'" id="id_labelmedia3'+i+'" value="">'+
                                    '<div class="form-control-feedback invalid-feedback" id="id_error_labelmedia3'+i+'"></div>'+
                                '</div>'+
                            '</div>' +
                            '<div id="fitem_id_valormedia3'+i+'" class="form-group row fitem ">'+
                                '<div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">'+
                                    '<label class="d-inline word-break " for="id_valormedia3'+i+'">Valor do botão</label>'+
                                    '<div class="form-label-addon d-flex align-items-center align-self-start"></div>'+
                                '</div>'+
                                '<div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">'+
                                    '<input type="text" class="form-control valor" name="valormedia3'+i+'" id="id_valormedia3'+i+'" value="" onkeydown="return so_numeros(event)">'+
                                    '<div class="form-control-feedback invalid-feedback" id="id_error_valormedia3'+i+'"></div>'+
                                '</div>'+
                            '</div>' +
                            '<hr>' +

                            '<div id="fitem_id_labelalta'+i+'" class="form-group row fitem ">'+
                                '<div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">'+
                                    '<label class="d-inline word-break " for="id_labelalta'+i+'">Nome do botão</label>'+
                                    '<div class="form-label-addon d-flex align-items-center align-self-start"></div>'+
                                '</div>'+
                                '<div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">'+
                                    '<input type="text" class="form-control valor" name="labelalta'+i+'" id="id_labelalta'+i+'" value="">'+
                                    '<div class="form-control-feedback invalid-feedback" id="id_error_labelalta'+i+'"></div>'+
                                '</div>'+
                            '</div>'+
                            '<div id="fitem_id_valoralta'+i+'" class="form-group row  fitem   ">'+
                                '<div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">'+
                                    '<label class="d-inline word-break " for="id_valoralta'+i+'">Valor do botão</label>'+
                                    '<div class="form-label-addon d-flex align-items-center align-self-start"></div>'+
                                '</div>'+
                                '<div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">'+
                                    '<input type="text" class="form-control valor" name="valoralta'+i+'" id="id_valoralta'+i+'" value="" onkeydown="return so_numeros(event)">'+
                                    '<div class="form-control-feedback invalid-feedback" id="id_error_valoralta'+i+'"></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'

    }


    $(".item_padrao").html(items);

    // $(".hides").removeClass("d-none");
});



