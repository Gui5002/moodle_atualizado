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
    function getSubCategorys(category = "", idReport = ""){
        $('#filterSubCategory').find('option').remove();

        $.ajax({
            url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/consultas.php',
            type: 'GET',
            dataType: 'text',
            data: {
                "id": idReport,
                "comando": '1',
                "category": category
            },
            success: function(response){
                var obj = jQuery.parseJSON(response);
                $('#filterSubCategory').append('<option value="">Selecione uma opção</option>');
                for(var i=0; i<obj.length; i++){
                    $('#filterSubCategory').append('<option value="'+obj[i].subcategoryname+'">'+obj[i].subcategoryname+'</option>');
                }
            }
        });
    }

    function modalShow(id,courseid){
        $.ajax({
            url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/notas.php',
            type: 'GET',
            dataType: 'text',
            data: {
                id : id,
                courseid: courseid
            },
            success: function(response){
                var txt = response;

                $("#modalDiv").html("");
                $("#modalDiv").html(txt);
                $('#mymodal').modal({ show: true });
            }
        });
    }

    $(document).ready(function(){
        let idReport = $("#idReport").val();

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
                    { "data": "carga_horaria" },
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
                        targets: [4,5,6,9],
                        className: 'dt-center'
                    }
                ]
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
                myTable.column(2).search(filterCursos, true, false).draw();
            });

            $("#filterStatus").on('change',function(){
                var filterStatus = this.value;

                myTable.column(9).search(filterStatus, true, false).draw();
            });



            $("#btnLimparFiltro").on('click',function(){
                $("#filterCursos").val('');
                $('#select2-filterCursos-container').text('Selecione uma opção');
                $("#filterCategory").val('');
                $('#select2-filterCategory-container').text('Selecione uma opção');
                $("#filterSubCategory").val('');
                $('#select2-filterSubCategory-container').text('Selecione uma opção');
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
                    }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
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
                ]
            });



            $("#filterCategory2").on('change',function(){
                var filterCategory2 = this.value;
                myTable.column(3).search(filterCategory2, true, false).draw();

            });

            $("#filterCursos2").on('change',function(){
                var filterCursos2 = this.value;
                myTable.column(5).search(filterCursos2, true, false).draw();
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

                        myTable.rows().remove().draw();
                        myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                        myTable.rows.add(obj).draw();
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

                        myTable.rows().remove().draw();
                        myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                        myTable.rows.add(obj).draw();
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

                                myTable.rows().remove().draw();
                                myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                                myTable.rows.add(obj).draw();


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
                ]
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
                myTable.column(5).search(filterCursos3, true, false).draw();
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

                        myTable.rows().remove().draw();
                        myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                        myTable.rows.add(obj).draw();
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

                        myTable.rows().remove().draw();
                        myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                        myTable.rows.add(obj).draw();
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

                            myTable.rows().remove().draw();
                            myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                            myTable.rows.add(obj).draw();
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