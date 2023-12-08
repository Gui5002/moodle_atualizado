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
                "buttons": [
                    { extend: 'copy', className: 'copyButton', text: 'Copiar' },
                    { extend: 'excel', className: 'excelButton' },
                    { extend: 'csv', className: 'csvButton' },
                    {
                        extend: 'pdf',
                        className: 'pdfButton',
                        title: 'Cursos e categorias',
                        image: 'theme/evagu/images/footer-logo-h8.png',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                        },
                        customize: function ( doc ) {
                            doc.content.splice( 1, 0, {
                                margin: [ 0, -50, 130, 12 ],
                                alignment: 'center',
                                image: '<?php echo $base64;?>',
                                width: 80,
                                height: 80
                            });
                        }
                    }
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

            /*$("#start").on('change',function(){
                var start = this.value;
                var end = $("#end").val();
                var filterCategory = $("#filterCategory").val();
                var filterSubCategory = $("#filterSubCategory").val();
                var filterCursos = $("#filterCursos").val();
                var filterStatus = $("#filterStatus").val();

                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?//=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "start": start,
                        "end": end,
                        "category": filterCategory,
                        "subcategory": filterSubCategory,
                        "curso": filterCursos,
                        "statusCurso": filterStatus
                    },
                    success: function(response){
                        var obj = jQuery.parseJSON(response);
                        if(obj.length > 0){
                            myTable.rows.add(obj).draw();
                        }
                    }
                });
            });

            $("#end").on('change',function(){
                var end = this.value;
                var start = $("#start").val();
                var filterCategory = $("#filterCategory").val();
                var filterSubCategory = $("#filterSubCategory").val();
                var filterCursos = $("#filterCursos").val();
                var filterStatus = $("#filterStatus").val();

                if(start == ""){
                    alert('Selecione a data inicial primeiro');
                }else{
                    if(start == end){
                        return true;
                    }else{
                        myTable.rows().remove().draw();

                        $.ajax({
                            url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                            type: 'GET',
                            dataType: 'text',
                            data: {
                                "id": idReport,
                                "end": end,
                                "start": start,
                                "category": filterCategory,
                                "subcategory": filterSubCategory,
                                "curso": filterCursos,
                                "statusCurso": filterStatus
                            },
                            success: function(response){
                                var obj = jQuery.parseJSON(response);
                                if(obj.length > 0){
                                    myTable.rows.add(obj).draw();
                                }
                            }
                        });
                    }
                }
            });*/

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
            let matricula2 = $("#matricula").val();

            $("#filterCategory2").select2({theme: "classic"});
            $("#filterCursos2").select2({theme: "classic"});
            $("#filterUsers2").select2({theme: "classic"});

            var myTable = $("#tab_sug2").DataTable({
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
                    [1, 'asc']
                ],
                "columnDefs": [
                    {
                        targets: [5,6],
                        className: 'dt-center'
                    }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                },
                "buttons": [
                    { extend: 'copy', className: 'copyButton', text: 'Copiar' },
                    { extend: 'excel', className: 'excelButton' },
                    { extend: 'csv', className: 'csvButton' },
                    {
                        extend: 'pdf',
                        className: 'pdfButton',
                        title: 'Cursos por usuário',
                        image: 'theme/evagu/images/footer-logo-h8.png',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        },
                        customize: function ( doc ) {
                            doc.content.splice( 1, 0, {
                                margin: [ 0, -50, 120, 12 ],
                                alignment: 'center',
                                image: '<?php echo $base64;?>',
                                width: 80,
                                height: 80
                            } );
                        }
                    }
                ],
                "columns": [
                    {
                        "data": "nome_completo",
                        "render": function (data, type, row) {
                            return '<a href="/user/profile.php?id=' + (row.user_id ? row.user_id : '') + '">' + data + '</a>';
                        }
                    },
                    {
                        "data": "nome_curso",
                        "render": function (data, type, row) {
                            return '<a href="/course/view.php?id=' + (row.course_id ? row.course_id : '') + '">' + data + '</a>';
                        }
                    },
                    { "data": "nome_categoria" },
                    { "data": "matricula" },
                    { "data": "carga_horaria" },
                    { "data": "atv" },
                    { "data": "progresso" }
                ]
            });



            $("#filterCategory2").on('change',function(){
                var filterCategory2 = this.value;
                myTable.column(2).search(filterCategory2, true, false).draw();
            });

            $("#filterCursos2").on('change',function(){
                var filterCursos2 = this.value;
                myTable.column(1).search(filterCursos2, true, false).draw();
            });

            $("#filterUsers2").on('change',function(){
                var filterUsers2 = this.value;
                myTable.column(0).search(filterUsers2, true, false).draw();
            });

            $("#matriculaStart2").on('change', function () {
                var matriculaStart2 = $("#matriculaStart2").val();
                var matriculaEnd2 = $("#matriculaEnd2").val();

                myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "matriculaStart2": matriculaStart2,
                        "matriculaEnd2": matriculaEnd2,
                    },
                    success: function (response) {
                        var obj = jQuery.parseJSON(response);

                        if (obj.length > 0) {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows.add(obj).draw();
                        } else {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows.add(obj).draw();
                        }
                    },
                    error: function () {
                        myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                        myTable.rows().remove().draw();
                    }
                });
            });



            $("#matriculaEnd2").on('change', function() {
                var matriculaStart2 = $("#matriculaStart2").val();
                var matriculaEnd2 = $("#matriculaEnd2").val();

                myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "matriculaStart2": matriculaStart2,
                        "matriculaEnd2": matriculaEnd2,
                    },
                    success: function(response) {
                        var obj = jQuery.parseJSON(response);

                        if (obj.length > 0) {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows.add(obj).draw();
                        } else {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows.add(obj).draw();
                        }
                    },
                    error: function () {
                        myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                        myTable.rows().remove().draw();
                    }
                });
            });

            $("#btnLimparFiltro2").on('click',function(){
                var matriculaStart2 = $("#matriculaStart2").val();
                var matriculaEnd2 = $("#matriculaEnd2").val();
                var hasData = false;

                if (matriculaStart2 || matriculaEnd2) {
                    hasData = true;
                }

                $('#select2-filterCategory2-container').text('Selecione uma opção');
                $("#filterCategory2").val('');
                $('#select2-filterCursos2-container').text('Selecione uma opção');
                $("#filterCursos2").val('');
                $('#select2-filterUsers2-container').text('Selecione uma opção');
                $("#filterUsers2").val('');
                $("#matriculaStart2").val('');
                $("#matriculaEnd2").val('');

                myTable.column(0).search('').draw();
                myTable.column(1).search('').draw();
                myTable.column(2).search('').draw();

                if (hasData) {
                    myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                    myTable.rows().remove().draw();
                    $.ajax({
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                        type: 'GET',
                        dataType: 'text',
                        data: {
                            "id": idReport,
                            "matriculaStart2": matriculaStart2,
                            "matriculaEnd2": matriculaEnd2,
                        },
                        success: function(response) {
                            var obj = jQuery.parseJSON(response);

                            if (obj.length > 0) {
                                myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                                myTable.rows.add(obj).draw();
                            }else {
                                myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                                myTable.rows.add(obj).draw();
                            }
                        },
                        error: function () {
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
            let filterLotacao3 = $("#filterLotacao3").val();
            let filterPerfil3 = $("#filterPerfil3").val();

            $("#filterUsers3").select2({theme: "classic"});
            $("#filterCargo3").select2({theme: "classic"});
            $("#filterLotacao3").select2({theme: "classic"});
            $("#filterPerfil3").select2({theme: "classic"});

            $('.demo-periodo').datepicker({
                format:'dd/mm/yyyy',
                language:'pt-BR'
            });

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
                    [1, 'asc']
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                },
                "columnDefs": [
                    {
                        targets: [0,4,5,6,7],
                        className: 'dt-center'
                    }
                ],
                "buttons": [
                    { extend: 'copy', className: 'copyButton', text: 'Copiar' },
                    { extend: 'excel', className: 'excelButton' },
                    { extend: 'csv', className: 'csvButton' },
                    {
                        extend: 'pdf',
                        className: 'pdfButton',
                        title: 'Usuários geral',
                        image: 'theme/evagu/images/footer-logo-h8.png',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                        },
                        customize: function ( doc ) {
                            doc.content.splice( 1, 0, {
                                margin: [ 0, -50, 100, 12 ],
                                alignment: 'center',
                                image: '<?php echo $base64;?>',
                                width: 80,
                                height: 80
                            } );
                        }
                    }
                ],
                "columns": [
                    { "data": "id" },
                    { "data": "nome" },
                    { "data": "cargo" },
                    { "data": "lotacao" },
                    { "data": "perfil" },
                    // { "data": "cidade" },
                    { "data": "carga" },
                    { "data": "cursos_completos" },
                    { "data": "ultimo_acesso" }
                ]
            });

            // myTable.column(1).search(filterUsers3).draw();
            // myTable.column(3).search(filterLotacao3).draw();
            // myTable.column(2).search(filterCargo3).draw();

            $("#filterUsers3").on('change',function(){
                var filterUsers3 = this.value;
                myTable.column(1).search(filterUsers3, true, false).draw();
            });

            $("#filterLotacao3").on('change',function(){
                var filterLotacao3 = this.value;
                myTable.column(3).search(filterLotacao3, true, false).draw();
            });

            $("#filterCargo3").on('change',function(){
                var filterCargo3 = this.value;
                myTable.column(2).search(filterCargo3, true, false).draw();
            });

            $("#filterPerfil3").on('change',function(){
                var filterPerfil3 = this.value;
                myTable.column(4).search(filterPerfil3, true, false).draw();
            });

            $("#ultacessoStart").on('change',function(){
                var primeiroAcessoStart = $("#primeiroAcessoStart").val();
                var primeiroAcessoEnd = $("#primeiroAcessoEnd").val();
                var matriculaStart = $("#matriculaStart").val();
                var matriculaEnd = $("#matriculaEnd").val();
                var ultacessoStart = $("#ultacessoStart").val();
                var ultacessoEnd = $("#ultacessoEnd").val();

                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "primeiroAcessoEnd": primeiroAcessoEnd,
                        "primeiroAcessoStart": primeiroAcessoStart,
                        "matriculaStart": matriculaStart,
                        "matriculaEnd": matriculaEnd,
                        "ultacessoStart": ultacessoStart,
                        "ultacessoEnd": ultacessoEnd
                    },
                    success: function(response){
                        var obj = jQuery.parseJSON(response);

                        if(obj.length > 0){
                            myTable.rows.add(obj).draw();
                        }
                    }
                });
            });

            $("#ultacessoEnd").on('change',function(){
                var ultacessoStart = $("#ultacessoStart").val();
                var ultacessoEnd = $("#ultacessoEnd").val();

                if(ultacessoStart == ""){
                    alert('Selecione a data inicial primeiro');
                }else{
                    if(ultacessoStart == ultacessoEnd){
                        return true;
                    }else{
                        myTable.rows().remove().draw();

                        $.ajax({
                            url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                            type: 'GET',
                            dataType: 'text',
                            data: {
                                "id": idReport,
                                "ultacessoStart": ultacessoStart,
                                "ultacessoEnd": ultacessoEnd
                            },
                            success: function(response){
                                var obj = jQuery.parseJSON(response);

                                if(obj.length > 0){
                                    myTable.rows.add(obj).draw();
                                }
                            }
                        });
                    }
                }
            });

            $("#btnLimparFiltro3").on('click',function(){
                $("#filterUsers3").val('');
                $('#select2-filterUsers3-container').text('Selecione uma opção');
                $("#filterLotacao3").val('');
                $('#select2-filterLotacao3-container').text('Selecione uma opção');
                $("#filterCargo3").val('');
                $('#select2-filterCargo3-container').text('Selecione uma opção');
                $("#filterPerfil3").val('');
                $('#select2-filterPerfil3-container').text('Selecione uma opção');
                $("#ultacessoStart").val('');
                $("#ultacessoEnd").val('');

                myTable.column(1).search('').draw();
                myTable.column(2).search('').draw();
                myTable.column(3).search('').draw();
                myTable.column(4).search('').draw();

                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport
                    },
                    success: function(response){
                        var obj = jQuery.parseJSON(response);

                        if(obj.length > 0){
                            myTable.rows.add(obj).draw();
                        }
                    }
                });
            });
        }else if(idReport == '4'){
            let filterUsers4 = $("#filterUsers4").val();
            let filterCargo4 = $("#filterCargo4").val();
            let filterLotacao4 = $("#filterLotacao4").val();
            let filterCategory4 = $("#filterCategory4").val();
            let filterCursos4 = $("#filterCursos4").val();
            let matricula4 = $("#matricula4").val();

            $("#filterUsers4").select2({theme: "classic"});
            $("#filterCategory4").select2({theme: "classic"});
            $("#filterCursos4").select2({theme: "classic"});
            $("#filterStatus4").select2({theme: "classic"});
            $("#filterLotacao4").select2({theme: "classic"});
            $("#filterCargo4").select2({theme: "classic"});




            var myTable = $("#tab_sug4").DataTable({
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
                "columnDefs": [
                    {
                        targets: [7,8,9],
                        className: 'dt-center'
                    }
                ],
                "buttons": [
                    { extend: 'copy', className: 'copyButton', text: 'Copiar' },
                    { extend: 'excel', className: 'excelButton' },
                    { extend: 'csv', className: 'csvButton' },
                    {
                        extend: 'pdf',
                        className: 'pdfButton',
                        title: 'Cursos por usuário',
                        image: 'theme/evagu/images/footer-logo-h8.png',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                        },
                        customize: function ( doc ) {
                            doc.content.splice( 1, 0, {
                                margin: [ 0, -50, 120, 12 ],
                                alignment: 'center',
                                image: '<?php echo $base64;?>',
                                width: 80,
                                height: 80
                            } );
                        }
                    }
                ],
                "columns": [
                    { "data": "nome_completo" },
                    { "data": "email" },
                    { "data": "cidade" },
                    { "data": "exercicio" },
                    { "data": "cargo" },
                    { "data": "lotacao" },
                    { "data": "nome_curso" },
                    { "data": "nome_categoria" },
                    { "data": "matricula" },
                    { "data": "status" }
                ]
            });


            $("#filterUsers4").on('change',function(){
                var filterUsers4 = this.value;
                myTable.column(0).search(filterUsers4, true, false).draw();
            });

            $("#filterCargo4").on('change',function(){
                var filterCargo4 = this.value;
                myTable.column(4).search(filterCargo4, true, false).draw();
            });

            $("#filterLotacao4").on('change',function(){
                var filterLotacao4 = this.value;
                myTable.column(5).search(filterLotacao4, true, false).draw();
            });

            $("#filterCategory4").on('change',function(){
                var filterCategory4 = this.value;
                myTable.column(7).search(filterCategory4, true, false).draw();
            });

            $("#filterCursos4").on('change',function(){
                var filterCursos4 = this.value;
                myTable.column(6).search(filterCursos4, true, false).draw();
            });

            $("#matriculaStart4").on('change', function () {
                var matriculaStart4 = $("#matriculaStart4").val();
                var matriculaEnd4 = $("#matriculaEnd4").val();

                myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "matriculaStart4": matriculaStart4,
                        "matriculaEnd4": matriculaEnd4,
                    },
                    success: function (response) {
                        var obj = jQuery.parseJSON(response);

                        if (obj.length > 0) {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows.add(obj).draw();
                        } else {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows.add(obj).draw();
                        }
                    },
                    error: function () {
                        myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                        myTable.rows().remove().draw();
                    }
                });
            });



            $("#matriculaEnd4").on('change', function() {
                var matriculaStart4 = $("#matriculaStart4").val();
                var matriculaEnd4 = $("#matriculaEnd4").val();

                myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "matriculaStart4": matriculaStart4,
                        "matriculaEnd4": matriculaEnd4,
                    },
                    success: function(response) {
                        var obj = jQuery.parseJSON(response);

                        if (obj.length > 0) {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows.add(obj).draw();
                        } else {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows.add(obj).draw();
                        }
                    },
                    error: function () {
                        myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                        myTable.rows().remove().draw();
                    }
                });
            });




            $("#btnLimparFiltro4").on('click', function() {
                var matriculaStart4 = $("#matriculaStart4").val();
                var matriculaEnd4 = $("#matriculaEnd4").val();
                var hasData = false;

                if (matriculaStart4 || matriculaEnd4) {
                    hasData = true;
                }

                $("#filterUsers4").val('');
                $('#select2-filterUsers4-container').text('Selecione uma opção');
                $("#filterCargo4").val('');
                $('#select2-filterCargo4-container').text('Selecione uma opção');
                $("#filterLotacao4").val('');
                $('#select2-filterLotacao4-container').text('Selecione uma opção');
                $("#filterCategory4").val('');
                $('#select2-filterCategory4-container').text('Selecione uma opção');
                $("#filterCursos4").val('');
                $('#select2-filterCursos4-container').text('Selecione uma opção');
                $("#filterStatus4").val('');
                $('#select2-filterStatus4-container').text('Selecione uma opção');
                $("#matriculaEnd4").val('');
                $("#matriculaStart4").val('');

                myTable.column(0).search('').draw();
                myTable.column(4).search('').draw();
                myTable.column(5).search('').draw();
                myTable.column(6).search('').draw();
                myTable.column(7).search('').draw();
                myTable.column(9).search('').draw();

                if (hasData) {
                    myTable.settings()[0].oLanguage.sEmptyTable = "Carregando...";
                    myTable.rows().remove().draw();
                    $.ajax({
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                        type: 'GET',
                        dataType: 'text',
                        data: {
                            "id": idReport,
                            "matriculaEnd4": matriculaEnd4,
                        },
                        success: function(response) {
                            var obj = jQuery.parseJSON(response);

                            if (obj.length > 0) {
                                myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                                myTable.rows.add(obj).draw();
                            }else {
                                myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                                myTable.rows.add(obj).draw();
                            }
                        },
                        error: function () {
                            myTable.settings()[0].oLanguage.sEmptyTable = "Nenhum registro encontrado";
                            myTable.rows().remove().draw();
                        }
                    });
                }
            });


        }else if(idReport == '5'){
            var filterCursos5 = $("#filterCursos5").val();
            var filterUsers5 = $("#filterUsers5").val();

            $("#filterCursos5").select2({theme: "classic"});
            $("#filterUsers5").select2({theme: "classic"});

            var myTable = $("#tab_sug5").DataTable({
                "dom": 'Bfrtip',
                "fixedHeader": false,
                "responsive": true,
                "deferRender": true,
                "paging": false,
                "searching": true,

                "aaSorting": [
                    [0, 'asc']
                ],

                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                },

                "columnDefs": [
                    {
                        targets: [3,4],
                        className: 'dt-center'
                    },
                    {
                        "targets": [ 0 ],
                        "visible": false
                    }
                ],
                "buttons": [
                    // { extend: 'copy', className: 'copyButton', text: 'Copiar' },
                    // { extend: 'excel', className: 'excelButton' },
                    // { extend: 'csv', className: 'csvButton' },
                    {
                        extend: 'pdfHtml5',
                        download: 'open',
                        className: 'pdfButton',
                        title: 'Histórico por aluno',
                        // image: 'theme/evagu/images/footer-logo-h8.png',
                        // orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [2, 4]
                        },
                        customize: function ( doc ) {

                            doc.content.splice( 1, 0,
                                {
                                    margin: [ 0, -35, 0, 12 ],
                                    alignment: 'left',
                                    image: '<?php echo $base64;?>',
                                    width: 50,
                                    height: 30
                                },

                                {
                                    margin: [ 0, 0, 0, 12 ],
                                    table: {
                                        body: [
                                            [{text: 'DADOS PESSOAIS', alignment: 'center', fillColor: '#eeeeff'}],
                                            [
                                                {
                                                    table: {
                                                        widths: [70, 190, 75, 150],
                                                        borders: false,
                                                        // headerRows: 2,
                                                        body: [
                                                            ['Data Inscrição:', dadosCabecalho.data_inscricao, 'Lotação:', formatUpper(dadosCabecalho.lotacao)],
                                                            ['Nome:', formatUpperName(dadosCabecalho.nome), 'Certificado:', formatUpper(dadosCabecalho.certificado)],
                                                            ['Cargo:', formatUpper(dadosCabecalho.cargo), 'Data Emissão:', dadosCabecalho.dt_emissao_cert],
                                                        ]
                                                    },
                                                    layout: 'noBorders'
                                                }
                                            ],
                                            [{text: 'DADOS DO CURSO', alignment: 'center', fillColor: '#eeeeff'}],
                                            [
                                                {
                                                    table: {
                                                        widths: [70, 190, 75, 150],
                                                        borders: false,
                                                        // headerRows: 2,
                                                        body: [
                                                            ['Categoria:', formatUpper(dadosCabecalho.categoryname), 'Sub Categoria:', formatUpper(dadosCabecalho.subcategoryname)],
                                                            ['Curso:', formatUpper(dadosCabecalho.curso), 'Carga Horaria:', dadosCabecalho.workload],
                                                            ['Status:', formatUpper(dadosCabecalho.status), '', '']
                                                        ]
                                                    },
                                                    layout: 'noBorders'
                                                }
                                            ],
                                            [{text: 'NOTAS', alignment: 'center', fillColor: '#eeeeff'}],
                                            [
                                                {
                                                    table: {
                                                        widths: [450, 50],
                                                        borders: false,
                                                        // headerRows: 2,
                                                        body: [
                                                            ["Nome Exame", "Nota"],
                                                            ...bodys
                                                        ]
                                                    },
                                                    // layout: 'noBorders'
                                                }
                                            ]
                                        ]
                                    }
                                },
                            );
                        }
                    }
                ],
                "oLanguage": {
                    "sLoadingRecords": '<h4>Carregando Informações...</h4><div id="loader"></div>'
                },
                "columns": [
                    { "data": "id", visible: false },
                    { "data": "sectionname", visible: false },
                    { "data": "activityname", width: '100%' },
                    { "data": "instrumento", visible: false },
                    { "data": "its_done" }
                ]
            });

            $("#filterUsers5").on('change',function(){
                limpacampocabecalho();
                var filterUsers5 = this.value;
                var courseWithGrades = $("#courseWithGrade").val();

                if($("#filterUsers5").val() !== ""){
                    $.ajax({
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/userCourses.php',
                        type: 'GET',
                        dataType: 'text',
                        data: {
                            "id": idReport,
                            "filterUsers5": filterUsers5,
                            "courseWithGrades":courseWithGrades
                        },
                        success: function(response){
                            var obj = jQuery.parseJSON(response);

                            if(obj.length > 0){
                                var txt = '';
                                txt += '<option value="" selected>Selecione uma opção</option>';
                                $("#filterCursos5").html('');
                                $("#filterCursos5").removeAttr('disabled');

                                for(var i=0; i<obj.length; i++){
                                    txt += '<option value="'+obj[i].id+'">'+obj[i].fullname+'</option>';
                                }

                                $("#filterCursos5").append(txt);
                            }else{
                                var txt = '';
                                txt += '<option value="" selected>Nenhum curso encontrado</option>';
                                $("#filterCursos5").html('');
                                $("#filterCursos5").append(txt);
                            }
                        }
                    });
                }else{
                    $("#filterCursos5").find('option').remove();
                    $("#filterCursos5").attr('disabled',true);
                }
            });

            $("#filterCursos5").on('change',function(){
                $(".spinner-wrapper").removeClass("d-none");
                var filterCursos5 = this.value;
                var filterUsers5 = $("#filterUsers5").val();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/userCabecalho.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "filterUsers5": filterUsers5,
                        "filterCursos5":filterCursos5
                    },
                    success: function(response){
                        var obj = jQuery.parseJSON(response);

                        dadosCabecalho = "--";
                        if(obj.length > 0){
                                $("#txtInscricao").html('');
                                $("#txtInscricao").html(obj[0].data_inscricao);

                                $("#txtNome").html('');
                                $("#txtNome").html(obj[0].nome.toUpperCase());

                                $("#txtCargo").html('');
                                $("#txtCargo").html(obj[0].cargo.toUpperCase());

                                $("#txtCertificado").html('');
                                $("#txtCertificado").html(obj[0].certificado.toUpperCase());

                                $("#txtLotacao").html('');
                                $("#txtLotacao").html(obj[0].lotacao.toUpperCase());

                                $("#txtStatus").html('');
                                $("#txtStatus").html(obj[0].status.toUpperCase());

                                $("#txtEmissao").html('');
                                $("#txtEmissao").html(obj[0].dt_emissao_cert);

                                $("#txtCategoria").html('');
                                $("#txtCategoria").html(obj[0].categoryname.toUpperCase());

                                $("#txtCurso").html('');
                                $("#txtCurso").html(obj[0].curso.toUpperCase());

                                $("#txtSubCategoria").html('');
                                $("#txtSubCategoria").html(obj[0].subcategoryname.toUpperCase());

                                $("#txtCargaHoraria").html('');
                                $("#txtCargaHoraria").html(obj[0].workload);
                            // teste = $("#txtInscricao").html(obj[i].data_inscricao);
                            dadosCabecalho = obj[0];
                            $(".spinner-wrapper").addClass("d-none");

                        }

                        else{
                            $("#txtInscricao").html('');
                            $("#txtInscricao").html('--');

                            $("#txtNome").html('');
                            $("#txtNome").html('--');

                            $("#txtCargo").html('');
                            $("#txtCargo").html('--');

                            $("#txtCertificado").html('');
                            $("#txtCertificado").html('--');

                            $("#txtLotacao").html('');
                            $("#txtLotacao").html('--');

                            $("#txtStatus").html('');
                            $("#txtStatus").html('--');

                            $("#txtEmissao").html('');
                            $("#txtEmissao").html('--');

                            $("#txtCategoria").html('');
                            $("#txtCategoria").html('--');

                            $("#txtCurso").html('');
                            $("#txtCurso").html('--');

                            $("#txtSubCategoria").html('');
                            $("#txtSubCategoria").html('--');

                            $("#txtCargaHoraria").html('');
                            $("#txtCargaHoraria").html('--');

                            dadosCabecalho = "";
                        }
                    }
                });




                if(filterCursos5 !== ""){
                    myTable.rows().remove().draw();

                    $.ajax({
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                        type: 'GET',
                        dataType: 'text',
                        data: {
                            "id": idReport,
                            "filterUsers5": filterUsers5,
                            "filterCursos5":filterCursos5
                        },
                        success: function(response){
                            var obj = jQuery.parseJSON(response);

                            // console.log(obj)

                            if(obj.length > 0){
                                myTable.rows.add(obj).draw();

                                $.ajax({
                                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/grade.php',
                                    type: 'GET',
                                    data: {
                                        "userid": filterUsers5,
                                        "courseid":filterCursos5
                                    },
                                    success: function(response){
                                        var objGrade = jQuery.parseJSON(response);

                                        var trs = '';

                                        bodys.length = 0;
                                        if (objGrade.length > 0) {
                                            console.log('if')
                                            for (var i=0; i<objGrade.length; i++){
                                            const rows = new Array();
                                                // rows.push(objGrade[i].id)
                                                // rows.push(objGrade[i].nome)
                                                // rows.push(objGrade[i].curso)
                                                rows.push(objGrade[i].examname)
                                                rows.push(objGrade[i].pointsobtained)
                                                // rows.push(objGrade[i].finalgradepercent)

                                                var nota = (objGrade[i].pointsobtained == null) ? "--" : objGrade[i].pointsobtained;

                                                trs += '<tr>' +
                                                    '<td>'+objGrade[i].examname+'</td>' +
                                                    '<td>'+nota+'</td>' +
                                                    '</tr>';

                                                $("#notas").html(trs);

                                                console.log(objGrade)

                                                bodys.push(rows);
                                            }
                                        }else{
                                            console.log('else')

                                            const rows = new Array();
                                            rows.push('--')
                                            rows.push('--')

                                            trs += '<tr>' +
                                                '<td></td>' +
                                                '<td>--</td>' +
                                                '</tr>';

                                            $("#notas").html(trs);
                                            bodys.push(rows);
                                            $(".spinner-wrapper").addClass("d-none");

                                        }
                                    }
                                });
                            }
                        }
                    });

                }
            });

            function formatUpperName(string) {
                var text = string.charAt(0).toUpperCase() + string.slice(1);
                for (var i = 0; i < text.length; i++) {
                    if (text.charAt(i) ===" ") {

                        // Convertendo letra após o ESPAÇO em maiuscula
                        var charToUper = text.charAt(i+1).toUpperCase();

                        // Colocando texto de antes do ESPAÇO na variável
                        var sliceBegin = text.slice(0, (i+1));

                        // colocando o texto de depois do ESPAÇO na variável
                        var sliceEnd = text.slice(i + 2);

                        // Juntando tudo
                        text = sliceBegin + charToUper + sliceEnd;

                    } else {

                        // NAO CONSIGO PENSAR EM COMO TRANSFORMAR O RESTANTE DAS LETRAS EM MINUSCULA
                    }
                }
                return text;
            }

            function formatUpper(string) {
                return  string.charAt(0).toUpperCase() + string.slice(1);
            }

            $("#btnLimparFiltro5").on('click',function(){
                $("#filterUsers5").val('');
                $('#select2-filterUsers5-container').text('Selecione uma opção');
                $("#filterCursos5").html('');
                $("#filterCursos5").attr('disabled',true);
                limpacampocabecalho();
            });

            function limpacampocabecalho(){


                $("#txtInscricao").html('');
                $("#txtInscricao").html('--');

                $("#txtNome").html('');
                $("#txtNome").html('--');

                $("#txtCargo").html('');
                $("#txtCargo").html('--');

                $("#txtCertificado").html('');
                $("#txtCertificado").html('--');

                $("#txtLotacao").html('');
                $("#txtLotacao").html('--');

                $("#txtStatus").html('');
                $("#txtStatus").html('--');

                $("#txtEmissao").html('');
                $("#txtEmissao").html('--');

                $("#txtCategoria").html('');
                $("#txtCategoria").html('--');

                $("#txtCurso").html('');
                $("#txtCurso").html('--');

                $("#txtSubCategoria").html('');
                $("#txtSubCategoria").html('--');

                $("#txtCargaHoraria").html('');
                $("#txtCargaHoraria").html('--');

                $("#notas").html('');

                myTable.rows().remove().draw();
            }
        }else if(idReport == '6'){
            var filterCursos6 = $("#filterCursos6").val();
            var filterUsers6 = $("#filterUsers6").val();

            $("#filterCursos6").select2({theme: "classic"});
            $("#filterUsers6").select2({theme: "classic"});

            var myTable = $("#tab_sug6").DataTable({
                "dom": 'Bfrtip',
                "fixedHeader": false,
                "responsive": true,
                "deferRender": true,
                "searching": true,
                // "ajax": {
                //     "url" : "<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php",
                //     "dataSrc": "",
                //     "data": {
                //         "id": idReport
                //     }
                // },
                "aaSorting": [
                    [1, 'asc']
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                },
                "columnDefs": [
                    {
                        "targets": [ 10,11,12,13,14,15,16,17,18,19,20,21,22,23 ],
                        "visible": false
                    }
                ],
                "buttons": [
                    { extend: 'copy', className: 'copyButton', text: 'Copiar' },
                    { extend: 'excel', className: 'excelButton' },
                    { extend: 'csv', className: 'csvButton' },
                    {
                        extend: 'pdf',
                        className: 'pdfButton',
                        title: 'Histórico por aluno',
                        image: 'theme/evagu/images/footer-logo-h8.png',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ]
                        },
                        customize: function ( doc ) {
                            doc.content.splice( 1, 0, {
                                margin: [ 0, 0, 0, 12 ],
                                alignment: 'center',
                                image: '<?php echo $base64;?>',
                                width: 80,
                                height: 80
                            } );
                        }
                    }
                ],
                "columns": [
                    { "data": "id" },
                    { "data": "nome" },
                    { "data": "curso_concluido" },
                    { "data": "categoria" },
                    { "data": "dta_matricula" },
                    { "data": "primeiro_acesso" },
                    { "data": "ultimo_acesso" },
                    { "data": "st_status" },
                    { "data": "media" },
                    { "data": "acoes" },
                    { "data": "av1" },
                    { "data": "av2" },
                    { "data": "av3" },
                    { "data": "av4" },
                    { "data": "av5" },
                    { "data": "av6" },
                    { "data": "av7" },
                    { "data": "av8" },
                    { "data": "av9" },
                    { "data": "av10" },
                    { "data": "av11" },
                    { "data": "av12" },
                    { "data": "av13" },
                    { "data": "av14" }
                ]
            });

            $("#filterCursos6").on('change',function(){
                var filterCursos6 = this.value;
                myTable.column(2).search(filterCursos6, true, false).draw();
            });

            // $("#filterStatus6").on('change',function(){
            //     var filterStatus6 = this.value;
            //     myTable.column(9).search(filterStatus6, true, false).draw();
            // });

            // $("#filterUsers6").on('change',function(){

            // });


            $("#filterUsers6").on('change',function(){
                var filterUsers6 = this.value;
                myTable.rows().remove().draw();

                if(filterUsers6 !== ""){
                    $.ajax({
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
                        type: 'GET',
                        dataType: 'text',
                        data: {
                            "id": idReport,
                            "filterUsers6": filterUsers6
                        },
                        success: function(response){
                            var obj = jQuery.parseJSON(response);

                            if(obj.length > 0){
                                myTable.rows.add(obj).draw();
                            }
                        }
                    });

                    $.ajax({
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/userCabecalho.php',
                        type: 'GET',
                        dataType: 'text',
                        data: {
                            "id": idReport,
                            "filterUsers6": filterUsers6
                        },
                        success: function(response){
                            var obj = jQuery.parseJSON(response);

                            if(obj.length > 0){
                                for(var i=0; i<obj.length; i++){
                                    $("#txtId6").html('');
                                    $("#txtId6").html(obj[i].userid);

                                    $("#txtNome6").html('');
                                    $("#txtNome6").html(obj[i].nome);

                                    $("#txtCargo6").html('');
                                    $("#txtCargo6").html(obj[i].cargo);

                                    $("#txtLotacao6").html('');
                                    $("#txtLotacao6").html(obj[i].lotacao);

                                    $("#txtCpf6").html('');
                                    $("#txtCpf6").html(obj[i].cpf);
                                }
                            }else{
                                $("#txtId6").html('');
                                $("#txtId6").html('--');

                                $("#txtNome6").html('');
                                $("#txtNome6").html('--');

                                $("#txtCargo6").html('');
                                $("#txtCargo6").html('--');

                                $("#txtLotacao6").html('');
                                $("#txtLotacao6").html('--');

                                $("#txtCpf6").html('');
                                $("#txtCpf6").html('--');
                            }
                        }
                    });

                    $.ajax({
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/userCourses.php',
                        type: 'GET',
                        dataType: 'text',
                        data: {
                            "id": idReport,
                            "filterUsers6": filterUsers6
                        },
                        success: function(response){
                            var obj = jQuery.parseJSON(response);

                            if(obj.length > 0){
                                var txt = '';
                                txt += '<option value="" selected>Selecione uma opção</option>';
                                $("#filterCursos6").html('');
                                $("#filterCursos6").removeAttr('disabled');

                                for(var i=0; i<obj.length; i++){
                                    txt += '<option value="'+obj[i].fullname+'">'+obj[i].fullname+'</option>';
                                }

                                $("#filterCursos6").append(txt);
                            }else{
                                var txt = '';
                                txt += '<option value="" selected>Nenhum curso encontrado</option>';
                                $("#filterCursos6").html('');
                                $("#filterCursos6").append(txt);
                            }
                        }
                    });
                }else{
                    $("#filterCursos6").find('option').remove();
                    $("#filterCursos6").attr('disabled',true);
                }
            });

            // $("#filterLotacao6").on('change',function(){
            //     var filterLotacao6 = this.value;
            //     myTable.column(3).search(filterLotacao6, true, false).draw();
            // });

            // $("#filterCargo6").on('change',function(){
            //     var filterCargo6 = this.value;
            //     myTable.column(2).search(filterCargo6, true, false).draw();
            // });

            // $("#primeiroAcessoStart6").on('change',function(){
            //     var primeiroAcessoStart6 = this.value;
            //     var primeiroAcessoEnd6 = $("#primeiroAcessoEnd6").val();

            //     myTable.rows().remove().draw();

            //     $.ajax({
            //         url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
            //         type: 'GET',
            //         dataType: 'text',
            //         data: {
            //             "id": idReport,
            //             "primeiroAcessoStart6": primeiroAcessoStart6,
            //             "primeiroAcessoEnd6": primeiroAcessoEnd6
            //         },
            //         success: function(response){
            //             var obj = jQuery.parseJSON(response);

            //             if(obj.length > 0){
            //                 myTable.rows.add(obj).draw();
            //             }
            //         }
            //     });
            // });

            // $("#primeiroAcessoEnd6").on('change',function(){
            //     var primeiroAcessoEnd6 = this.value;
            //     var primeiroAcessoStart6 = $("#primeiroAcessoStart6").val();

            //     if(primeiroAcessoStart6 == ""){
            //         alert('Selecione a data inicial primeiro');
            //     }else{
            //         if(primeiroAcessoStart6 == primeiroAcessoEnd6){
            //             return true;
            //         }else{
            //             myTable.rows().remove().draw();

            //             $.ajax({
            //                 url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
            //                 type: 'GET',
            //                 dataType: 'text',
            //                 data: {
            //                     "id": idReport,
            //                     "primeiroAcessoEnd6": primeiroAcessoEnd6,
            //                     "primeiroAcessoStart6": primeiroAcessoStart6
            //                 },
            //                 success: function(response){
            //                     var obj = jQuery.parseJSON(response);

            //                     if(obj.length > 0){
            //                         myTable.rows.add(obj).draw();
            //                     }
            //                 }
            //             });
            //         }
            //     }
            // });

            // $("#matriculaStart6").on('change',function(){
            //     var matriculaStart6 = this.value;
            //     var matriculaEnd6 = $("#matriculaEnd6").val();

            //     myTable.rows().remove().draw();

            //     $.ajax({
            //         url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
            //         type: 'GET',
            //         dataType: 'text',
            //         data: {
            //             "id": idReport,
            //             "matriculaStart6": matriculaStart6,
            //             "matriculaEnd6": matriculaEnd6
            //         },
            //         success: function(response){
            //             var obj = jQuery.parseJSON(response);

            //             if(obj.length > 0){
            //                 myTable.rows.add(obj).draw();
            //             }
            //         }
            //     });
            // });

            // $("#matriculaEnd6").on('change',function(){
            //     var matriculaEnd6 = this.value;
            //     var matriculaStart6 = $("#matriculaStart6").val();

            //     if(matriculaStart6 == ""){
            //         alert('Selecione a data inicial primeiro');
            //     }else{
            //         if(matriculaStart6 == matriculaEnd6){
            //             return true;
            //         }else{
            //             myTable.rows().remove().draw();

            //             $.ajax({
            //                 url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
            //                 type: 'GET',
            //                 dataType: 'text',
            //                 data: {
            //                     "id": idReport,
            //                     "matriculaEnd6": matriculaEnd6,
            //                     "matriculaStart6": matriculaStart6
            //                 },
            //                 success: function(response){
            //                     var obj = jQuery.parseJSON(response);

            //                     if(obj.length > 0){
            //                         myTable.rows.add(obj).draw();
            //                     }
            //                 }
            //             });
            //         }
            //     }
            // });

            // $("#ultacessoStart6").on('change',function(){
            //     var ultacessoStart6 = this.value;
            //     var ultacessoEnd6 = $("#ultacessoEnd6").val();

            //     myTable.rows().remove().draw();

            //     $.ajax({
            //         url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
            //         type: 'GET',
            //         dataType: 'text',
            //         data: {
            //             "id": idReport,
            //             "ultacessoStart6": ultacessoStart6,
            //             "ultacessoEnd6": ultacessoEnd6
            //         },
            //         success: function(response){
            //             var obj = jQuery.parseJSON(response);

            //             if(obj.length > 0){
            //                 myTable.rows.add(obj).draw();
            //             }
            //         }
            //     });
            // });

            // $("#ultacessoEnd6").on('change',function(){
            //     var ultacessoEnd6 = this.value;
            //     var ultacessoStart6 = $("#ultacessoStart6").val();

            //     if(ultacessoStart6 == ""){
            //         alert('Selecione a data inicial primeiro');
            //     }else{
            //         if(ultacessoStart6 == ultacessoEnd6){
            //             return true;
            //         }else{
            //             myTable.rows().remove().draw();

            //             $.ajax({
            //                 url: '<?=$CFG->wwwroot?>/blocks/eva_reports/servicos/tabela.php',
            //                 type: 'GET',
            //                 dataType: 'text',
            //                 data: {
            //                     "id": idReport,
            //                     "ultacessoEnd6": ultacessoEnd6,
            //                     "ultacessoStart6": ultacessoStart6
            //                 },
            //                 success: function(response){
            //                     var obj = jQuery.parseJSON(response);

            //                     if(obj.length > 0){
            //                         myTable.rows.add(obj).draw();
            //                     }
            //                 }
            //             });
            //         }
            //     }
            // });

            $("#btnLimparFiltro6").on('click',function(){
                // $('#select2-filterCategory6-container').text('Selecione uma opção');
                // $("#filterCategory6").val('');
                // $('#select2-filterMencao6-container').text('Selecione uma opção');
                // $("#filterMencao6").val('');
                $('#select2-filterCursos6-container').text('Selecione uma opção');
                $("#filterCursos6").val('');
                // $('#select2-filterStatus6-container').text('Selecione uma opção');
                // $("#filterStatus6").val('');
                $('#select2-filterUsers6-container').text('Selecione uma opção');
                $("#filterUsers6").val('');
                // $('#select2-filterLotacao6-container').text('Selecione uma opção');
                // $("#filterLotacao6").val('');
                // $('#select2-filterCargo6-container').text('Selecione uma opção');
                // $("#filterCargo6").val('');
                // $("#primeiroAcessoStart6").val('');
                // $("#primeiroAcessoEnd6").val('');
                // $("#matriculaStart6").val('');
                // $("#matriculaEnd6").val('');
                // $("#ultacessoStart6").val('');
                // $("#ultacessoEnd6").val('');

                $("#txtId6").html('');
                $("#txtId6").html('--');

                $("#txtNome6").html('');
                $("#txtNome6").html('--');

                $("#txtCargo6").html('');
                $("#txtCargo6").html('--');

                $("#txtLotacao6").html('');
                $("#txtLotacao6").html('--');

                $("#txtCpf6").html('');
                $("#txtCpf6").html('--');

                // myTable.column(2).search('').draw();
                // myTable.column(17).search('').draw();
                myTable.column(5).search('').draw();
                // myTable.column(9).search('').draw();
                myTable.column(1).search('').draw();
                // myTable.column(3).search('').draw();
                // myTable.column(2).search('').draw();
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

        $("#btnVoltar").on('click',function(){
            window.location.href = '/my/';
        });
    });
</script>