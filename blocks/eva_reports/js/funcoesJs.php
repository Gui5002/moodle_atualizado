<?php
global $CFG, $DB, $USER, $PAGE;

$path = $CFG->dirroot . '/blocks/eva_reports/img/header-logo4.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script>
    var dadosCabecalho = '';
    const bodys = [];

    function gerarPDF() {
        var cardBodyElement = document.querySelector('#print');
        var tituloPDFElement = document.querySelector('#tituloPDF');
        var evaTituloElement = document.querySelector('#evaTitulo');
        var usuariosPDFElement = document.querySelector('#usuariosPDF');
        var cursosPDFElement = document.querySelector('#cursosPDF');
        var cargaPDFElement = document.querySelector('#cargaPDF');
        var cursosTituloElement = document.querySelector('#cursosTitulo');
        var cursosElement = document.querySelector('#cursos');
        var cargaCursosElement = document.querySelector('#carga');
        var categoriasElement = document.querySelector('#categorias');
        var categoriasItens = categoriasElement.querySelectorAll('li');
        var categoriasConteudo = "";
        categoriasItens.forEach(function (item) {
            categoriasConteudo += item.innerText + "\n";
        });
        var inscricoesElement = document.querySelector('#inscricoes');
        var concluintesElement = document.querySelector('#concluintes');
        var naoConcluidosElement = document.querySelector('#naoConcluidos');
        var naoIniciadoElement = document.querySelector('#naoIniciados');

        if (!cardBodyElement || !tituloPDFElement || !evaTituloElement || !usuariosPDFElement ||
            !cursosPDFElement || !cargaPDFElement || !cursosTituloElement || !cursosElement ||
            !cargaCursosElement || !categoriasElement || !inscricoesElement || !concluintesElement ||
            !naoConcluidosElement || !naoIniciadoElement) {
            console.error("Um ou mais elementos não foram encontrados no DOM.");
            return;
        }
        var tituloPDF = tituloPDFElement.innerText;
        var evaTitulo = evaTituloElement.innerText;
        var usuariosInfo = {
            text: [
                { text: 'Usuários registrados: ', bold: true, fontSize: 10 },
                { text: usuariosPDFElement.innerText }
            ],
            style: 'info'
        };

        var cursosInfo = {
            text: [
                { text: 'Cursos, ações e capacitações: ', bold: true, fontSize: 10 },
                { text: cursosPDFElement.innerText }
            ],
            style: 'info'
        };

        var cargaInfo = {
            text: [
                { text: 'Carga horária total na EVA: ', bold: true, fontSize: 10},
                { text: cargaPDFElement.innerText }
            ],
            style: 'info'
        };
        var cursosTitulo = cursosTituloElement.innerText;
        var cursosConteudo = [
            {
                text: [
                    { text: 'Cursos: ', bold: true, fontSize: 10 },
                    { text: cursosElement.innerText }
                ],
                style: 'info'
            },
            {
                text: [
                    { text: 'Inscrições: ', bold: true, fontSize: 10 },
                    { text: inscricoesElement.innerText}
                ],
                style: 'info'
            },
            {
                text: [
                    { text: 'Concluintes: ', bold: true, fontSize: 10 },
                    { text: concluintesElement.innerText }
                ],
                style: 'info'
            },
            {
                text: [
                    { text: 'Não Concluído: ', bold: true, fontSize: 10 },
                    { text: naoConcluidosElement.innerText }
                ],
                style: 'info'
            },
            {
                text: [
                    { text: 'Não Iniciado: ', bold: true, fontSize: 10 },
                    { text: naoIniciadoElement.innerText }
                ],
                style: 'info'
            }
        ];

        var segundaColunaConteudo = [
            {
                text: [
                    { text: 'Carga horária: ', bold: true, fontSize: 10 },
                    { text: cargaCursosElement.innerText }
                ],
                style: 'info'
            },
            {
                text: [
                    { text: 'Categorias Selecionadas: \n', bold: true, fontSize: 10 },
                    { text: categoriasConteudo }
                ],
                style: 'info'
            }

        ];
        var docDefinition = {
            content: [
                {
                    text: tituloPDF,
                    style: 'titulo'
                },
                {
                    canvas: [{ type: 'line', x1: 0, y1: 5, x2: 600, y2: 5, lineWidth: 1 }]
                },
                {
                    text: evaTitulo,
                    style: 'subtitulo'
                },
                {
                    text: usuariosInfo,
                    style: 'info'
                },
                {
                    text: cursosInfo,
                    style: 'info'
                },
                {
                    text: cargaInfo,
                    style: 'info'
                },
                {
                    canvas: [{ type: 'line', x1: 0, y1: 5, x2: 600, y2: 5, lineWidth: 1 }]
                },
                {
                    text: cursosTitulo,
                    style: 'subtitulo'
                },
                {
                    columns: [
                        {
                            width: '50%',
                            stack: cursosConteudo,
                        },
                        {
                            width: '50%',
                            stack: segundaColunaConteudo,
                        }
                    ]
                },
                {
                    canvas: [{ type: 'line', x1: 0, y1: 5, x2: 600, y2: 5, lineWidth: 1 }]
                }
            ],
            pageMargins: [0, 0, 0, 0],
            styles: {
                titulo: {
                    fontSize: 16,
                    alignment: 'center',
                    bold: true,
                    margin: [0, 20, 0, 10]
                },
                subtitulo: {
                    fontSize: 12,
                    bold: true,
                    margin: [10, 10, 0, 10]
                },
                info: {
                    fontSize: 9,
                    margin: [10, 5, 0, 5],
                }
            }
        };

        pdfMake.createPdf(docDefinition).download('consolidado-eva.pdf');
    }

    $(document).ready(function(){
        let idReport = $("#idReport").val();

        $(".filterCategory").change(function() {
            var categoria = $(this).val();
            if(idReport == '1'){
                myTable.column(1).search('').draw();
            }else if(idReport == '4'){
                myTable.column(3).search('').draw();
            }
            $(".filterSubCategory").html("<option value=''>Carregando...</option>");
            console.log(categoria);

            $.ajax({
                url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    "categoria": categoria
                },
                success: function(data) {
                    var selectSubCategory = $(".filterSubCategory");
                    selectSubCategory.empty();
                    if (data.length > 0) {
                        selectSubCategory.append($('<option>', {
                            value: "",
                            text: "Selecione uma subcategoria"
                        }));
                        data.forEach(function(item) {
                            selectSubCategory.append($('<option>', {
                                value: item.categoria_nome,
                                text: item.categoria_nome

                            }));
                        });
                        selectSubCategory.prop('disabled', false);
                    } else {
                        selectSubCategory.append($('<option>', {
                            value: "",
                            text: "Nenhuma subcategoria disponível"
                        }));
                        selectSubCategory.prop('disabled', true);
                    }
                },
                error: function(error) {
                    console.error("Erro na solicitação AJAX: ", error);
                },
                complete: function() {
                    console.log("Complete");
                }
            });
        });

        if(idReport == '1'){
            let filterCategory = $("#filterCategory").val();
            let filterSubCategory = $("#filterSubCategory").val();
            let filterCursos = $("#filterCursos").val();
            let filterStatus = $("#filterStatus").val();

            $("#filterCategory").select2({theme: "classic"});
            $("#filterSubCategory").select2({theme: "classic"});
            $("#filterCursos").select2({theme: "classic"});
            $("#filterStatus").select2({theme: "classic"});

            $('.demo-3').datepicker({
                format:'dd/mm/yyyy',
                language:'pt-BR'
            });

            var myTable = $("#tab_sug").DataTable({
                "dom": 'Bfrtip',
                "fixedHeader": false,
                "responsive": true,
                "deferRender": true,
                "searching": true,
                "oSearch": {"bSmart": false},
                "ajax": {
                    "url" : "<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php",
                    "dataSrc": "",
                    "data": {
                        "id": idReport
                    }
                },
                "aaSorting": [
                    [2, 'asc']
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                },
                "oLanguage": {
                    "sEmptyTable": "Carregando..."
                },
                "buttons": [
                    { extend: 'excel', className: 'excelButton' },
                    { extend: 'csv', className: 'csvButton' }
                ],
                "columns": [
                    { "data": "categoria" },
                    { "data": "subcategoria" },
                    {
                        "data": "nome_curso",
                        "render": function (data, type, row) {
                            return '<a href="/course/view.php?id=' + (row.course_id ? row.course_id : '') + '">' + data + '</a>';
                        }
                    },
                    { "data": "carga" },
                    { "data": "inscritos" },
                    { "data": "concluintes" },
                    { "data": "total_certificados" },
                    { "data": "inicio" },
                    { "data": "fim" },
                    { "data": "status" }
                ]
                ,
                "columnDefs": [
                    {
                        targets: [3,4,5,6,7,8,9],
                        className: 'dt-center'
                    },
                    {
                        targets: [7, 8],
                        orderable: false
                    }
                ],
                "createdRow": function (row, data, index) {
                    $(row).find('td').css('font-family', 'Raleway, sans-serif');
                }
            });

            $("#filterCategory").on('change',function(){
                var filterCategory = this.value;
                myTable.column(0).search(filterCategory, true, false).draw();
            });

            $("#filterSubCategory").on('change',function(){
                var filterSubCategory = this.value;
                myTable.column(1).search(filterSubCategory, true, false).draw();
            });

            $("#filterCursos").on('change',function(){
                var filterCursos = this.value;
                myTable.column(2).search('^' + filterCursos + '$', true, false).draw();
            });

            $("#filterStatus").on('change',function(){
                var filterStatus = this.value;

                myTable.column(9).search(filterStatus, true, false).draw();
            });

            $("#btnLimparFiltro").on('click',function(){
                $(".filterSubCategory").empty();
                $(".filterSubCategory").html("<option value=''>Categoria não selecionada...</option>");
                $("#filterCursos").val('');
                $('#select2-filterCursos-container').text('Selecione uma opção');
                $("#filterCategory").val('');
                $('#select2-filterCategory-container').text('Selecione uma opção');
                $("#filterStatus").val('');
                $('#select2-filterStatus-container').text('Selecione uma opção');

                myTable.column(0).search('').draw();
                myTable.column(1).search('').draw();
                myTable.column(2).search('').draw();
                myTable.column(9).search('').draw();

            });
//========================================== --- RELATORIO 2 --- ===================================================
        }else if(idReport == '2'){
            let filterCategory2 = $("#filterCategory2").val();
            let filterCursos2 = $("#filterCursos2").val();
            let filterUsers2 = $("#filterUsers2").val();
            let filterStatus2 = $("#filterStatus2").val();
            let matricula2 = $("#matricula2").val();

            $("#filterCategory2").select2({theme: "classic"});
            $("#filterCursos2").select2({theme: "classic"});
            $("#filterUsers2").select2({theme: "classic"});
            $("#filterStatus2").select2({theme: "classic"});

            var myTable = $("#tab_sug2").DataTable({
                "dom": 'Bfrtip',
                "fixedHeader": false,
                "responsive": true,
                "deferRender": true,
                "searching": true,
                "ajax": {
                    "url": "<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php",
                    "dataSrc": "",
                    "data": {
                        "id": idReport
                    }
                },
                "aaSorting": [
                    [5, 'asc']
                ],
                "columnDefs": [
                    {
                        targets: [6,7,8,9],
                        className: 'dt-center'
                    },
                    {
                        "targets": [4],
                        "visible": false,
                        "searchable": true
                    },
                    {
                        "targets": [1, 2],
                        "className": 'col-md-1'
                    },
                    {
                        targets: [6],
                        orderable: false
                    }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                },
                "oLanguage": {
                    "sEmptyTable": "Carregando..."
            },
                "buttons": [
                    { extend: 'excel', className: 'excelButton' },
                    { extend: 'csv', className: 'csvButton' }
                ],
                "columns": [
                    {
                        "data": "nome_completo",
                        "render": function (data, type, row) {
                            return '<a href="/user/profile.php?id=' + (row.user_id ? row.user_id : '') + '">' + data + '</a>';
                        }
                    },
                    { "data": "sigla_exercicio" },
                    { "data": "cargo" },
                    { "data": "categoria" },
                    { "data": "subcategoria" },
                    {
                        "data": "nome_curso",
                        "render": function (data, type, row) {
                            return '<a href="/course/view.php?id=' + (row.course_id ? row.course_id : '') + '">' + data + '</a>';
                        }
                    },
                    { "data": "matricula" },
                    { "data": "carga" },
                    { "data": "atv" },
                    { "data": "progresso" },
                    { "data": "status" }
                ],
                "createdRow": function (row, data, index) {
                    $(row).find('td').css('font-family', 'Raleway, sans-serif');
                }
            });



            $("#filterCategory2").on('change',function(){
                var filterCategory2 = this.value;
                myTable.column(3).search(filterCategory2, true, false).draw();

            });

            $("#filterCursos2").on('change',function(){
                var filterCursos2 = this.value;
                myTable.column(5).search('^' + filterCursos2 + '$', true, false).draw();
            });

            $("#filterUsers2").on('change',function(){
                var filterUsers2 = this.value;
                myTable.column(0).search(filterUsers2, true, false).draw();
            });

            $("#filterStatus2").on('change',function(){
                var filterStatus2 = this.value;
                myTable.column(10).search(filterStatus2, true, false).draw();
            });

            $("#matriculaStart2").on('change', function () {
                var matriculaStart2 = $("#matriculaStart2").val();
                var matriculaEnd2 = $("#matriculaEnd2");

                matriculaEnd2.prop('disabled', true);

                myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "matriculaStart2": matriculaStart2,
                        "matriculaEnd2": matriculaEnd2.val(),
                    },
                    success: function (response) {
                        var obj = jQuery.parseJSON(response);

                        if (obj.length === 0) {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows().remove().draw();
                        } else {
                            myTable.rows().remove().draw();
                            myTable.rows.add(obj).draw();
                        }
                    },
                    error: function () {
                        myTable.rows().remove().draw();
                        myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                        myTable.rows().remove().draw();
                    },
                    complete: function () {

                        matriculaEnd2.prop('disabled', false);
                    }
                });
            });

            $("#matriculaEnd2").on('change', function () {
                var matriculaStart2 = $("#matriculaStart2");
                var matriculaEnd2 = $("#matriculaEnd2").val();

                matriculaStart2.prop('disabled', true);

                myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "matriculaStart2": matriculaStart2.val(),
                        "matriculaEnd2": matriculaEnd2,
                    },
                    success: function (response) {
                        var obj = jQuery.parseJSON(response);

                        if (obj.length === 0) {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows().remove().draw();
                        } else {
                            myTable.rows().remove().draw();
                            myTable.rows.add(obj).draw();
                        }
                    },
                    error: function () {
                        myTable.rows().remove().draw();
                        myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                        myTable.rows().remove().draw();
                    },
                    complete: function () {
                        matriculaStart2.prop('disabled', false);
                    }
                });
            });


            $("#btnLimparFiltro2").on('click',function(){
                var matriculaStart2 = $("#matriculaStart2").val();
                var matriculaEnd2 = $("#matriculaEnd2").val();
                var hasData = false;



                $('#select2-filterCategory2-container').text('Selecione uma opção');
                $("#filterCategory2").val('');
                $('#select2-filterCursos2-container').text('Selecione uma opção');
                $("#filterCursos2").val('');
                $('#select2-filterUsers2-container').text('Selecione uma opção');
                $("#filterUsers2").val('');
                $('#select2-filterStatus2-container').text('Selecione uma opção');
                $("#filterStatus2").val('');
                $("#matriculaStart2").val('');
                $("#matriculaEnd2").val('');

                myTable.column(0).search('').draw();
                myTable.column(3).search('').draw();
                myTable.column(5).search('').draw();
                myTable.column(10).search('').draw();

                if (matriculaStart2 || matriculaEnd2) {
                    hasData = true;
                }

                if (hasData) {
                    myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                    myTable.rows().remove().draw();
                    $.ajax({
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                        type: 'GET',
                        dataType: 'text',
                        data: {
                            "id": idReport
                        },

                        success: function(response) {
                            var obj = jQuery.parseJSON(response);

                            if (obj.length === 0) {
                                myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                                myTable.rows().remove().draw();
                            } else {
                                myTable.rows().remove().draw();
                                myTable.rows.add(obj).draw();
                            }


                        },
                        error: function () {
                            myTable.rows().remove().draw();
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows().remove().draw();
                        }
                    });
                }

            });
//========================================== --- RELATORIO 3 --- ===================================================
        }else if(idReport == '3'){
            let filterUsers3 = $("#filterUsers3").val();
            let filterCargo3 = $("#filterCargo3").val();
            let filterCursos3 = $("#filterCursos3").val();
            let filterStatus3 = $("#filterStatus3").val();
            let matricula3 = $("#matricula3").val();

            $("#filterUsers3").select2({theme: "classic"});
            $("#filterCursos3").select2({theme: "classic"});
            $("#filterStatus3").select2({theme: "classic"});
            $("#filterCargo3").select2({theme: "classic"});
            $("#filterStatus3").select2({theme: "classic"});




            var myTable = $("#tab_sug3").DataTable({
                "dom": 'Bfrtip',
                "fixedHeader": false,
                "responsive": true,
                "deferRender": true,
                "searching": true,
                "ajax": {
                    "url" : "<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php",
                    "dataSrc": "",
                    "data": {
                        "id": idReport
                    }
                },
                "aaSorting": [
                    [0, 'asc']
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                },
                "oLanguage": {
                    "sEmptyTable": "Carregando..."
                },
                "columnDefs": [
                    {
                        targets: [6,7],
                        className: 'dt-center'
                    },
                    {
                        targets: [6],
                        orderable: false
                    }
                ],
                "buttons": [
                    { extend: 'excel', className: 'excelButton' },
                    { extend: 'csv', className: 'csvButton' }
                ],
                "columns": [
                    {
                        "data": "nome_completo",
                        "render": function (data, type, row) {
                            return '<a href="/user/profile.php?id=' + (row.user_id ? row.user_id : '') + '">' + data + '</a>';
                        }
                    },
                    { "data": "email" },
                    { "data": "cidade" },
                    { "data": "exercicio" },
                    { "data": "cargo" },
                    {
                        "data": "nome_curso",
                        "render": function (data, type, row) {
                            return '<a href="/course/view.php?id=' + (row.course_id ? row.course_id : '') + '">' + data + '</a>';
                        }
                    },
                    { "data": "matricula" },
                    { "data": "status" }
                ],
                "createdRow": function (row, data, index) {
                    $(row).find('td').css('font-family', 'Raleway, sans-serif');
                }
            });


            $("#filterUsers3").on('change',function(){
                var filterUsers3 = this.value;
                myTable.column(0).search(filterUsers3, true, false).draw();
            });

            $("#filterCargo3").on('change',function(){
                var filterCargo3 = this.value;
                myTable.column(4).search(filterCargo3, true, false).draw();
            });

            $("#filterCursos3").on('change',function(){
                var filterCursos3 = this.value;
                myTable.column(5).search('^' + filterCursos3 + '$', true, false).draw();
            });
            $("#filterStatus3").on('change',function(){
                var filterStatus3 = this.value;
                myTable.column(7).search(filterStatus3, true, false).draw();
            });

            $("#matriculaStart3").on('change', function () {
                var matriculaStart3 = $("#matriculaStart3").val();
                var matriculaEnd3 = $("#matriculaEnd3");

                matriculaEnd3.prop('disabled', true);

                myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "matriculaStart3": matriculaStart3,
                        "matriculaEnd3": matriculaEnd3.val(),
                    },
                    success: function (response) {
                        var obj = jQuery.parseJSON(response);

                        if (obj.length === 0) {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows().remove().draw();
                        } else {
                            myTable.rows().remove().draw();
                            myTable.rows.add(obj).draw();
                        }
                    },
                    error: function () {
                        myTable.rows().remove().draw();
                        myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                        myTable.rows().remove().draw();
                    },
                    complete: function () {
                        matriculaEnd3.prop('disabled', false);
                    }
                });
            });




            $("#matriculaEnd3").on('change', function () {
                var matriculaStart3 = $("#matriculaStart3");
                var matriculaEnd3 = $("#matriculaEnd3").val();

                matriculaStart3.prop('disabled', true);

                myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "matriculaStart3": matriculaStart3.val(),
                        "matriculaEnd3": matriculaEnd3,
                    },
                    success: function (response) {
                        var obj = jQuery.parseJSON(response);

                        if (obj.length === 0) {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows().remove().draw();
                        } else {
                            myTable.rows().remove().draw();
                            myTable.rows.add(obj).draw();
                        }
                    },
                    error: function () {
                        myTable.rows().remove().draw();
                        myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                        myTable.rows().remove().draw();
                    },
                    complete: function () {
                        matriculaStart3.prop('disabled', false);
                    }
                });
            });





            $("#btnLimparFiltro3").on('click', function() {
                var matriculaStart3 = $("#matriculaStart3").val();
                var matriculaEnd3 = $("#matriculaEnd3").val();
                var hasData = false;

                $("#filterUsers3").val('');
                $('#select2-filterUsers3-container').text('Selecione uma opção');
                $("#filterCargo3").val('');
                $('#select2-filterCargo3-container').text('Selecione uma opção');
                $("#filterCursos3").val('');
                $('#select2-filterCursos3-container').text('Selecione uma opção');
                $("#filterStatus3").val('');
                $('#select2-filterStatus3-container').text('Selecione uma opção');
                $("#matriculaStart3").val('');
                $("#matriculaEnd3").val('');

                if (matriculaStart3 || matriculaEnd3) {
                    hasData = true;
                }

                myTable.column(0).search('').draw();
                myTable.column(4).search('').draw();
                myTable.column(5).search('').draw();
                myTable.column(7).search('').draw();

                if (hasData) {
                    myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                    myTable.rows().remove().draw();
                    $.ajax({
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                        type: 'GET',
                        dataType: 'text',
                        data: {
                            "id": idReport
                        },
                        success: function(response) {
                            var obj = jQuery.parseJSON(response);

                            if (obj.length === 0) {
                                myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                                myTable.rows().remove().draw();
                            } else {
                                myTable.rows().remove().draw();
                                myTable.rows.add(obj).draw();
                            }
                        },
                        error: function () {
                            myTable.rows().remove().draw();
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows().remove().draw();
                        }
                    });
                }
            });


        } else if(idReport == '4'){
            let filterCategory4 = $("#filterCategory4").val();
            let filterSubCategory4 = $("#filterSubCategory4").val();
            let filterCursos4 = $("#filterCursos4").val();
            let filterStatus4 = $("#filterStatus4").val();

            $("#filterCategory4").select2({theme: "classic"});
            $("#filterSubCategory4").select2({theme: "classic"});
            $("#filterCursos4").select2({theme: "classic"});
            $("#filterStatus4").select2({theme: "classic"});

            $('.demo-3').datepicker({
                format:'dd/mm/yyyy',
                language:'pt-BR'
            });

            var myTable = $("#tab_sug4").DataTable({
                "dom": 'Bfrtip',
                "fixedHeader": false,
                "responsive": true,
                "deferRender": true,
                "searching": true,
                "oSearch": {"bSmart": true},
                "ajax": {
                    "url" : "<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php",
                    "dataSrc": "",
                    "data": {
                        "id": idReport
                    }
                },
                "aaSorting": [
                    [0, 'asc']
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                },
                "oLanguage": {
                    "sEmptyTable": "Carregando..."
                },
                "buttons": [
                    { extend: 'excel', className: 'excelButton' },
                    { extend: 'csv', className: 'csvButton' }
                ],
                "columns": [
                    {
                        "data": "nome_curso",
                        "render": function (data, type, row) {
                            return '<a href="/course/view.php?id=' + (row.course_id ? row.course_id : '') + '">' + data + '</a>';
                        }
                    },
                    { "data": "criacao" },
                    { "data": "categoria" },
                    { "data": "subcategoria" },
                    { "data": "carga" },
                    { "data": "inscritos" },
                    { "data": "concluintes" },
                    { "data": "naoconcluidos" },
                    { "data": "naoiniciados" },
                ]
                ,
                "columnDefs": [
                    {
                        targets: [1,4,5,6,7,8],
                        className: 'dt-center'
                    },
                    {
                        targets: [1],
                        orderable: false
                    }
                ],
                "createdRow": function (row, data, index) {
                    $(row).find('td').css('font-family', 'Raleway, sans-serif');
                }
            });

            $("#filterCategory4").on('change',function(){
                var filterCategory4 = this.value;
                myTable.column(2).search(filterCategory4, true, false).draw();
            });

            $("#filterSubCategory4").on('change',function(){
                var filterSubCategory4 = this.value;
                myTable.column(3).search(filterSubCategory4, true, false).draw();
            });

            $("#filterCursos4").on('change',function(){
                var filterCursos4 = this.value;
                myTable.column(0).search('^' + filterCursos4 + '$', true, false).draw();
            });

            $("#filterStatus4").on('change',function(){
                var filterStatus4 = this.value;
                myTable.column(9).search(filterStatus4, true, false).draw();
            });

            $("#criacaoStart4").on('change', function () {
                var criacaoStart4 = $("#criacaoStart4").val();
                var criacaoEnd4 = $("#criacaoEnd4");

                criacaoEnd4.prop('disabled', true);

                myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "criacaoStart4": criacaoStart4,
                        "criacaoEnd4": criacaoEnd4.val(),
                    },
                    success: function (response) {
                        var obj = jQuery.parseJSON(response);

                        if (obj.length === 0) {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows().remove().draw();
                        } else {
                            myTable.rows().remove().draw();
                            myTable.rows.add(obj).draw();
                        }
                    },
                    error: function () {
                        myTable.rows().remove().draw();
                        myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                        myTable.rows().remove().draw();
                    },
                    complete: function () {
                        criacaoEnd4.prop('disabled', false);
                    }
                });
            });




            $("#criacaoEnd4").on('change', function () {
                var criacaoStart4 = $("#criacaoStart4");
                var criacaoEnd4 = $("#criacaoEnd4").val();

                criacaoStart4.prop('disabled', true);

                myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "criacaoStart4": criacaoStart4.val(),
                        "criacaoEnd4": criacaoEnd4,
                    },
                    success: function (response) {
                        var obj = jQuery.parseJSON(response);

                        if (obj.length === 0) {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows().remove().draw();
                        } else {
                            myTable.rows().remove().draw();
                            myTable.rows.add(obj).draw();
                        }
                    },
                    error: function () {
                        myTable.rows().remove().draw();
                        myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                        myTable.rows().remove().draw();
                    },
                    complete: function () {
                        criacaoStart4.prop('disabled', false);
                    }
                });
            });


            $("#btnLimparFiltro4").on('click',function(){
                var criacaoStart4 = $("#criacaoStart4").val();
                var criacaoEnd4 = $("#criacaoEnd4").val();
                $(".filterSubCategory").empty();
                $(".filterSubCategory").html("<option value=''>Categoria não selecionada...</option>");
                var hasData = false;

                $("#filterCursos4").val('');
                $('#select2-filterCursos4-container').text('Selecione uma opção');
                $("#filterCategory4").val('');
                $('#select2-filterCategory4-container').text('Selecione uma opção');
                $("#filterStatus4").val('');
                $('#select2-filterStatus4-container').text('Selecione uma opção');
                $("#criacaoStart4").val('');
                $("#criacaoEnd4").val('');

                if (criacaoStart4 || criacaoEnd4) {
                    hasData = true;
                }

                myTable.column(0).search('').draw();
                myTable.column(2).search('').draw();
                myTable.column(3).search('').draw();

                if (hasData) {
                    myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                    myTable.rows().remove().draw();
                    $.ajax({
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                        type: 'GET',
                        dataType: 'text',
                        data: {
                            "id": idReport
                        },
                        success: function(response) {
                            var obj = jQuery.parseJSON(response);

                            if (obj.length === 0) {
                                myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                                myTable.rows().remove().draw();
                            } else {
                                myTable.rows().remove().draw();
                                myTable.rows.add(obj).draw();
                            }
                        },
                        error: function () {
                            myTable.rows().remove().draw();
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows().remove().draw();
                        }
                    });
                }
            });
        }else if (idReport == '5') {
            function fetchData() {
                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        "id": idReport
                    },
                    success: function (data) {
                        populateTable(data);
                        $('input[type="checkbox"]').prop('checked', true).css('opacity', '1');
                    },
                    error: function (error) {
                        console.error('Erro ao obter dados:', error);
                    }
                });
            }

            $('input[type="checkbox"]').on('change', function () {
                if ($(this).attr('id') === 'marcarTodos') {
                    var isChecked = $(this).prop('checked');
                    $('input[type="checkbox"]').prop('checked', isChecked);
                }
                calcular();
            });

            function calcular() {
                var checkedCheckboxes = $('input[type="checkbox"]:checked');

                var checkedValues = [];

                checkedCheckboxes.each(function () {
                    var value = $(this).val();
                    if (value !== 'on') {
                        checkedValues.push(value);
                    }
                });

                var uniqueCheckedValues = Array.from(new Set(checkedValues.filter(Boolean)));
                var listaCategorias = '<ul>';
                uniqueCheckedValues.forEach(function (value) {
                    listaCategorias += '<li>' + value + '</li>';
                });
                listaCategorias += '</ul>';

                $('#categorias').html(listaCategorias);

                var quantidadeRegistros = 0;
                var totalColuna2 = 0;
                var totalColuna3 = 0;
                var totalColuna4 = 0;
                var totalColuna5 = 0;
                var totalColuna6 = 0;
                var totalColuna7 = 0;

                $('#filterCursos5 table tr:gt(0)').each(function () {
                    var categoria = $(this).find('td:eq(7)').text();
                    var valorColuna2 = parseFloat($(this).find('td:eq(1)').text());
                    var valorColuna3 = parseFloat($(this).find('td:eq(2)').text());
                    var valorColuna4 = parseFloat($(this).find('td:eq(3)').text());
                    var valorColuna5 = parseFloat($(this).find('td:eq(4)').text());
                    var valorColuna6 = parseFloat($(this).find('td:eq(5)').text());
                    var valorColuna7 = parseFloat($(this).find('td:eq(6)').text());

                    if (uniqueCheckedValues.includes(categoria)) {
                        quantidadeRegistros++;

                        totalColuna2 += valorColuna2;
                        totalColuna3 += valorColuna3;
                        totalColuna4 += valorColuna4;
                        totalColuna5 += valorColuna5;
                        totalColuna6 += valorColuna6;
                        totalColuna7 += valorColuna7;
                    }
                });

                if (totalColuna3 > 59) {
                    var calculoHoras = totalColuna3 / 60;
                    var parteInteira = Math.floor(calculoHoras);
                    var parteDecimal = (calculoHoras % 1);

                    totalColuna2 += parteInteira;
                    totalColuna3 = Math.round(parteDecimal * 60);
                }

                if ($('input[type="checkbox"]:visible').length > 0) {
                    $('#cursos').text(quantidadeRegistros);
                    $('#carga').text(totalColuna2 + ' hrs ' + totalColuna3 + ' min');
                    $('#inscricoes').text(totalColuna4);
                    $('#concluintes').text(totalColuna5);
                    $('#naoConcluidos').text(totalColuna6);
                    $('#naoIniciados').text(totalColuna7);
                } else {
                    $('#cursos').text('Selecione uma ou mais categorias');
                    $('#carga').text('...');
                    $('#inscricoes').text('...');
                    $('#concluintes').text('...');
                    $('#naoConcluidos').text('...');
                    $('#naoIniciados').text('...');
                    $('#categorias').empty();
                }
            }

            function populateTable(data) {
                $('#filterCursos5').empty();

                var table = $('<table>').addClass('table');

                var headerRow = $('<tr>');
                headerRow.append($('<th>').text('Nome do Curso'));
                headerRow.append($('<th>').text('Hora'));
                headerRow.append($('<th>').text('Minutos'));
                headerRow.append($('<th>').text('Inscritos'));
                headerRow.append($('<th>').text('Concluintes'));
                headerRow.append($('<th>').text('Não Concluídos'));
                headerRow.append($('<th>').text('Não Iniciados'));
                headerRow.append($('<th>').text('Categoria'));

                table.append(headerRow);

                $.each(data, function (index, item) {
                    var row = $('<tr>');
                    row.append($('<td>').text(item.nome_curso));
                    row.append($('<td>').text(item.horas));
                    row.append($('<td>').text(item.minutos));
                    row.append($('<td>').text(item.inscritos));
                    row.append($('<td>').text(item.concluintes));
                    row.append($('<td>').text(item.naoconcluidos));
                    row.append($('<td>').text(item.naoiniciados));
                    row.append($('<td>').text(item.categoria));

                    table.append(row);
                });

                $('#filterCursos5').append(table);

                if ($('input[type="checkbox"]:visible').length > 0) {
                    setTimeout(function () {
                        $('input[type="checkbox"]').css('display', '');
                        calcular();
                    }, 500);
                }

            }
            fetchData()
        } else if(idReport == '0'){
            let filterCargo0 = $("#filterCargo0").val();
            let filterExercicio0 = $("#filterExercicio0").val();
            let filterCursos0 = $("#filterCursos0").val();
            let filterUsers0 = $("#filterUsers0").val();
            let conclusao0 = $("#conclusao0").val();

            $("#filterCargo0").select2({theme: "classic"});
            $("#filterExercicio0").select2({theme: "classic"});
            $("#filterCursos0").select2({theme: "classic"});
            $("#filterUsers0").select2({theme: "classic"});

            var myTable = $("#tab_sug2").DataTable({
                "dom": 'Bfrtip',
                "fixedHeader": false,
                "responsive": true,
                "deferRender": true,
                "searching": true,
                "ajax": {
                    "url": "<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php",
                    "dataSrc": "",
                    "data": {
                        "id": idReport
                    }
                },
                "aaSorting": [
                    [0, 'asc']
                ],
                "columnDefs": [
                    {
                        targets: [6, 7],
                        className: 'dt-center'
                    },
                    {
                        "targets": [6, 7],
                        "className": 'col-md-1'
                    },
                    {
                        targets: [7],
                        orderable: false
                    }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                },
                "oLanguage": {
                    "sEmptyTable": "Carregando..."
                },
                "buttons": [
                    { extend: 'excel', className: 'excelButton' },
                    { extend: 'csv', className: 'csvButton' }
                ],
                "columns": [
                    {
                        "data": "nome_completo",
                        "render": function (data, type, row) {
                            return '<a href="/user/profile.php?id=' + (row.user_id ? row.user_id : '') + '">' + data + '</a>';
                        }
                    },
                    { "data": "email" },
                    { "data": "categoria" },
                    {
                        "data": "nome_curso",
                        "render": function (data, type, row) {
                            return '<a href="/course/view.php?id=' + (row.course_id ? row.course_id : '') + '">' + data + '</a>';
                        }
                    },
                    { "data": "cargo" },
                    { "data": "exercicio" },
                    { "data": "sigla" },
                    { "data": "data" }
                ],
                "createdRow": function (row, data, index) {
                    $(row).find('td').css('font-family', 'Raleway, sans-serif');
                }
            });



            $("#filterCargo0").on('change',function(){
                var filterCargo0 = this.value;
                myTable.column(4).search(filterCargo0, true, false).draw();

            });

            $("#filterExercicio0").on('change',function(){
                var filterExercicio0 = this.value;
                myTable.column(5).search(filterExercicio0, true, false).draw();

            });

            $("#filterCursos0").on('change',function(){
                var filterCursos0 = this.value;
                myTable.column(3).search('^' + filterCursos0 + '$', true, false).draw();
            });

            $("#filterUsers0").on('change',function(){
                var filterUsers0 = this.value;
                myTable.column(0).search(filterUsers0, true, false).draw();
            });


            $("#conclusaoStart0").on('change', function () {
                var conclusaoStart0 = $("#conclusaoStart0").val();
                var conclusaoEnd0 = $("#conclusaoEnd0");

                conclusaoEnd0.prop('disabled', true);

                myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "conclusaoStart0": conclusaoStart0,
                        "conclusaoEnd0": conclusaoEnd0.val(),
                    },
                    success: function (response) {
                        var obj = jQuery.parseJSON(response);

                        if (obj.length === 0) {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows().remove().draw();
                        } else {
                            myTable.rows().remove().draw();
                            myTable.rows.add(obj).draw();
                        }
                    },
                    error: function () {
                        myTable.rows().remove().draw();
                        myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                        myTable.rows().remove().draw();
                    },
                    complete: function () {

                        conclusaoEnd0.prop('disabled', false);
                    }
                });
            });

            $("#conclusaoEnd0").on('change', function () {
                var conclusaoStart0 = $("#conclusaoStart0");
                var conclusaoEnd0 = $("#conclusaoEnd0").val();

                conclusaoStart0.prop('disabled', true);

                myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "conclusaoStart0": conclusaoStart0.val(),
                        "conclusaoEnd0": conclusaoEnd0,
                    },
                    success: function (response) {
                        var obj = jQuery.parseJSON(response);

                        if (obj.length === 0) {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows().remove().draw();
                        } else {
                            myTable.rows().remove().draw();
                            myTable.rows.add(obj).draw();
                        }
                    },
                    error: function () {
                        myTable.rows().remove().draw();
                        myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                        myTable.rows().remove().draw();
                    },
                    complete: function () {
                        conclusaoStart0.prop('disabled', false);
                    }
                });
            });


            $("#btnLimparFiltro0").on('click',function(){
                var conclusaoStart0 = $("#conclusaoStart0").val();
                var conclusaoEnd0 = $("#conclusaoEnd0").val();
                var hasData = false;



                $('#select2-filterCargo0-container').text('Selecione uma opção');
                $("#filterCargo0").val('');
                $('#select2-filterExercicio0-container').text('Selecione uma opção');
                $("#filterExercicio0").val('');
                $('#select2-filterCursos0-container').text('Selecione uma opção');
                $("#filterCursos0").val('');
                $('#select2-filterUsers0-container').text('Selecione uma opção');
                $("#filterUsers0").val('');
                $("#conclusaoStart0").val('');
                $("#conclusaoEnd0").val('');

                myTable.column(0).search('').draw();
                myTable.column(3).search('').draw();
                myTable.column(4).search('').draw();
                myTable.column(5).search('').draw();

                if (conclusaoStart0 || conclusaoEnd0) {
                    hasData = true;
                }

                if (hasData) {
                    myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                    myTable.rows().remove().draw();
                    $.ajax({
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                        type: 'GET',
                        dataType: 'text',
                        data: {
                            "id": idReport
                        },

                        success: function(response) {
                            var obj = jQuery.parseJSON(response);

                            if (obj.length === 0) {
                                myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                                myTable.rows().remove().draw();
                            } else {
                                myTable.rows().remove().draw();
                                myTable.rows.add(obj).draw();
                            }


                        },
                        error: function () {
                            myTable.rows().remove().draw();
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows().remove().draw();
                        }
                    });
                }

            });
        }

        $("#arrowLabel").on('click',function(){
            if($("#arrow").hasClass('fa-angle-down') === true){
                $("#arrow").removeClass('fa-angle-down');
                $("#arrow").addClass('fa-angle-up');
            }else{
                $("#arrow").removeClass('fa-angle-up');
                $("#arrow").addClass('fa-angle-down');
            }

            $("#cardFiltros").slideToggle("slow");
        });

    });
</script>