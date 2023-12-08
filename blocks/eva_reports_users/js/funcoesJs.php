<?php
global $CFG, $DB, $USER, $PAGE;

$path = $CFG->dirroot . '/blocks/eva_reports_users/img/header-logo4.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script>
    function getSubCategorys(category = "", idReport = ""){
        $('#filterSubCategory').find('option').remove();

        $.ajax({
            url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/consultas.php',
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
            url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/notas.php',
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
                    "url" : "<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php",
                    "dataSrc": "",
                    "data": {
                        "id": idReport
                    }
                },
                "aaSorting": [
                    [3, 'asc']
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
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
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
                    { "data": "id" },
                    { "data": "categoryname" },
                    { "data": "subcategoryname" },
                    { "data": "fullname" },
                    { "data": "total_acessos" },
                    { "data": "workload" },
                    { "data": "total_inscritos" },
                    { "data": "concluidos" },
                    { "data": "certificados" },
                    { "data": "dta_inicio" },
                    { "data": "dta_final" },
                    { "data": "status_curso" }
                ],
                "columnDefs": [
                    {
                        targets: [0,4,5,6,7,8,9,10,11],
                        className: 'dt-center'
                    }
                ]
            });

            // myTable.column(1).search(filterCategory).draw();
            // myTable.column(2).search(filterSubCategory).draw();
            // myTable.column(3).search(filterCursos).draw();
            // myTable.column(10).search(filterCursos).draw();

            $("#filterCategory").on('change',function(){
                var filterCategory = this.value;

                if($(this).val() == ""){
                    $('#filterSubCategory').prop('disabled',true);
                    $('#filterSubCategory').find('option').remove();

                    // getCourses($(this).val(),idReport);
                    // getStatus($(this).val(),idReport);
                    myTable.column(1).search('', true, false).draw();
                }else{
                    if($('#filterSubCategory').prop('disabled') == true){
                        $('#filterSubCategory').prop('disabled',false);
                    }

                    getSubCategorys($(this).val(),idReport);

                    // if($('#filterCursos').val() == ""){
                    //     getCourses($(this).val(),idReport);
                    //     getStatus($(this).val(),idReport);
                    // }
                }

                myTable.column(1).search(filterCategory, true, false).draw();
            });

            $("#filterSubCategory").on('change',function(){
                // if($(this).val() !== ""){
                //     if($('#filterCursos').val() == ""){
                //         getCourses($(this).val(),idReport,'1');
                //         getStatus($(this).val(),idReport,'1');
                //     }
                // }else{
                //     if($('#filterCursos').val() == ""){
                //         getCourses($("#filterCategory").val(),idReport);
                //         getStatus($("#filterCategory").val(),idReport);
                //     }
                // }

                var filterSubCategory = this.value;
                myTable.column(2).search(filterSubCategory, true, false).draw();
            });

            $("#filterCursos").on('change',function(){
                //if(this.value !== ""){
                    myTable.column(3).search($(this).val(), false, false, false).draw();
                    //myTable.column(3).search(this.value, true, false).draw();
                //}else{
                    //myTable.column(3).search("", true, false).draw();
                //}
            });

            $("#filterStatus").on('change',function(){
                var filterStatus = this.value;

                // if($('#filterCursos').val() == ""){
                //     getCourses($("#filterCategory").val(),idReport);
                // }

                myTable.column(11).search(filterStatus, true, false).draw();
            });

            $("#start").on('change',function(){
                var start = this.value;
                var end = $("#end").val();
                var filterCategory = $("#filterCategory").val();
                var filterSubCategory = $("#filterSubCategory").val();
                var filterCursos = $("#filterCursos").val();
                var filterStatus = $("#filterStatus").val();

                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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
                            url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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
                $("#start").val('');
                $("#end").val('');

                $('#filterSubCategory').prop('disabled',true);
                $('#filterSubCategory').find('option').remove();
                $('#filterSubCategory').append('<option value="">Selecione uma opção</option>');


                myTable.column(3).search('').draw();
                myTable.column(1).search('').draw();
                myTable.column(2).search('').draw();
                myTable.column(11).search('').draw();

                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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
//========================================== --- RELATORIO 2 --- ===================================================
        }else if(idReport == '2'){
            let filterCategory2 = $("#filterCategory2").val();
            let filterMencao2 = $("#filterMencao2").val();
            let filterCursos2 = $("#filterCursos2").val();
            let filterStatus2 = $("#filterStatus2").val();
            let filterUsers2 = $("#filterUsers2").val();
            let filterLotacao2 = $("#filterLotacao2").val();
            let filterCargo2 = $("#filterCargo2").val();
            let primeiroAcesso = $("#primeiroAcesso").val();
            let matricula = $("#matricula").val();
            let ultacesso = $("#ultacesso").val();

            $("#filterCategory2").select2({theme: "classic"});
            $("#filterMencao2").select2({theme: "classic"});
            $("#filterCursos2").select2({theme: "classic"});
            $("#filterStatus2").select2({theme: "classic"});
            $("#filterUsers2").select2({theme: "classic"});
            $("#filterLotacao2").select2({theme: "classic"});
            $("#filterCargo2").select2({theme: "classic"});

            $('.demo-4-acesso').datepicker({
                format:'dd/mm/yyyy',
                language:'pt-BR'
            });

            $('.demo-4-matricula').datepicker({
                format:'dd/mm/yyyy',
                language:'pt-BR'
            });

            $('.demo-4-ultacesso').datepicker({
                format:'dd/mm/yyyy',
                language:'pt-BR'
            });

            var myTable = $("#tab_sug2").DataTable({
                "dom": 'Bfrtip',
                "fixedHeader": false,
                "responsive": true,
                "deferRender": true,
                "searching": true,
                "ajax": {
                    "url" : "<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php",
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
                "columnDefs": [
                    {
                        "targets": [ 0 ],
                        "visible": false
                    },
                    {
                        targets: [1,7,8,9],
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
                        title: 'Resultados por curso',
                        image: 'theme/evagu/images/footer-logo-h8.png',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                        },
                        customize: function ( doc ) {
                            doc.content.splice( 1, 0, {
                                margin: [ 0, -50, 130, 12 ],
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
                    { "data": "userid" },
                    { "data": "nome" },
                    { "data": "cargo" },
                    { "data": "lotacao" },
                    { "data": "categoria" },
                    { "data": "curso_concluido" },
                    { "data": "dta_matricula" },
                    // { "data": "primeiro_acesso" },
                    // { "data": "ultimo_acesso" },
                    { "data": "carga" },
                    { "data": "percent" }
                    // { "data": "acoes" }
                ]
            });

            // myTable.column(2).search(filterCategory2).draw();
            // myTable.column(17).search(filterMencao2).draw();
            // myTable.column(5).search(filterCursos2).draw();
            // myTable.column(9).search(filterCursos2).draw();
            // myTable.column(1).search(filterUsers2).draw();
            // myTable.column(3).search(filterLotacao2).draw();
            // myTable.column(2).search(filterCargo2).draw();

            $("#filterCategory2").on('change',function(){
                var filterCategory2 = this.value;
                myTable.column(5).search(filterCategory2, true, false).draw();
            });

            $("#filterMencao2").on('change',function(){
                var filterMencao2 = this.value;
                myTable.column(12).search(filterMencao2, true, false).draw();
            });

            $("#filterCursos2").on('change',function(){
                var filterCursos2 = this.value;
                myTable.column(6).search(filterCursos2, true, false).draw();
            });

            $("#filterStatus2").on('change',function(){
                var filterStatus2 = this.value;
                myTable.column(10).search(filterStatus2, true, false).draw();
            });

            $("#filterUsers2").on('change',function(){
                var filterUsers2 = this.value;
                myTable.column(2).search(filterUsers2, true, false).draw();
            });

            $("#filterLotacao2").on('change',function(){
                var filterLotacao2 = this.value;
                myTable.column(4).search(filterLotacao2, true, false).draw();
            });

            $("#filterCargo2").on('change',function(){
                var filterCargo2 = this.value;
                myTable.column(3).search(filterCargo2, true, false).draw();
            });

            $("#primeiroAcessoStart").on('change',function(){
                var primeiroAcessoStart = $("#primeiroAcessoStart").val();
                var primeiroAcessoEnd = $("#primeiroAcessoEnd").val();
                var matriculaStart = $("#matriculaStart").val();
                var matriculaEnd = $("#matriculaEnd").val();
                var ultacessoStart = $("#ultacessoStart").val();
                var ultacessoEnd = $("#ultacessoEnd").val();

                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "primeiroAcessoStart": primeiroAcessoStart,
                        "primeiroAcessoEnd": primeiroAcessoEnd,
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

            $("#primeiroAcessoEnd").on('change',function(){
                var primeiroAcessoStart = $("#primeiroAcessoStart").val();
                var primeiroAcessoEnd = $("#primeiroAcessoEnd").val();
                var matriculaStart = $("#matriculaStart").val();
                var matriculaEnd = $("#matriculaEnd").val();
                var ultacessoStart = $("#ultacessoStart").val();
                var ultacessoEnd = $("#ultacessoEnd").val();

                if(primeiroAcessoStart == ""){
                    alert('Selecione a data inicial primeiro');
                }else{
                    if(primeiroAcessoStart == primeiroAcessoEnd){
                        return true;
                    }else{
                        myTable.rows().remove().draw();

                        $.ajax({
                            url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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
                    }
                }
            });

            $("#matriculaStart").on('change',function(){
                var primeiroAcessoStart = $("#primeiroAcessoStart").val();
                var primeiroAcessoEnd = $("#primeiroAcessoEnd").val();
                var matriculaStart = $("#matriculaStart").val();
                var matriculaEnd = $("#matriculaEnd").val();
                var ultacessoStart = $("#ultacessoStart").val();
                var ultacessoEnd = $("#ultacessoEnd").val();

                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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

            $("#matriculaEnd").on('change',function(){
                var primeiroAcessoStart = $("#primeiroAcessoStart").val();
                var primeiroAcessoEnd = $("#primeiroAcessoEnd").val();
                var matriculaStart = $("#matriculaStart").val();
                var matriculaEnd = $("#matriculaEnd").val();
                var ultacessoStart = $("#ultacessoStart").val();
                var ultacessoEnd = $("#ultacessoEnd").val();

                if(matriculaStart == ""){
                    alert('Selecione a data inicial primeiro');
                }else{
                    if(matriculaStart == matriculaEnd){
                        return true;
                    }else{
                        myTable.rows().remove().draw();

                        $.ajax({
                            url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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
                    }
                }
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
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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
                var primeiroAcessoStart = $("#primeiroAcessoStart").val();
                var primeiroAcessoEnd = $("#primeiroAcessoEnd").val();
                var matriculaStart = $("#matriculaStart").val();
                var matriculaEnd = $("#matriculaEnd").val();
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
                            url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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
                    }
                }
            });

            $("#btnLimparFiltro2").on('click',function(){
                $('#select2-filterCategory2-container').text('Selecione uma opção');
                $("#filterCategory2").val('');
                $('#select2-filterMencao2-container').text('Selecione uma opção');
                $("#filterMencao2").val('');
                $('#select2-filterCursos2-container').text('Selecione uma opção');
                $("#filterCursos2").val('');
                $('#select2-filterStatus2-container').text('Selecione uma opção');
                $("#filterStatus2").val('');
                $('#select2-filterUsers2-container').text('Selecione uma opção');
                $("#filterUsers2").val('');
                $('#select2-filterLotacao2-container').text('Selecione uma opção');
                $("#filterLotacao2").val('');
                $('#select2-filterCargo2-container').text('Selecione uma opção');
                $("#filterCargo2").val('');
                $("#primeiroAcessoStart").val('');
                $("#primeiroAcessoEnd").val('');
                $("#matriculaStart").val('');
                $("#matriculaEnd").val('');
                $("#ultacessoStart").val('');
                $("#ultacessoEnd").val('');

                myTable.column(2).search('').draw();
                myTable.column(3).search('').draw();
                myTable.column(4).search('').draw();
                myTable.column(5).search('').draw();
                myTable.column(6).search('').draw();

                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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
                    "url" : "<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php",
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
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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
                            url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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
            let primeiroAcesso4 = $("#primeiroAcesso4").val();
            let matricula4 = $("#matricula4").val();
            let ultacesso4 = $("#ultacesso4").val();

            $("#filterUsers4").select2({theme: "classic"});
            $("#filterCargo4").select2({theme: "classic"});
            $("#filterLotacao4").select2({theme: "classic"});
            $("#filterCategory4").select2({theme: "classic"});
            $("#filterCursos4").select2({theme: "classic"});
            $("#filterStatus4").select2({theme: "classic"});

            $('.demo-5-acesso').datepicker({
                format:'dd/mm/yyyy',
                language:'pt-BR'
            });

            $('.demo-5-matricula').datepicker({
                format:'dd/mm/yyyy',
                language:'pt-BR'
            });

            $('.demo-5-ultacesso').datepicker({
                format:'dd/mm/yyyy',
                language:'pt-BR'
            });

            var myTable = $("#tab_sug4").DataTable({
                "dom": 'Bfrtip',
                "fixedHeader": false,
                "responsive": true,
                "deferRender": true,
                "searching": true,
                "ajax": {
                    "url" : "<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php",
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
                        targets: [0,6,7,8,9],
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
                    { "data": "userid" },
                    { "data": "nome" },
                    { "data": "cargo" },
                    { "data": "lotacao" },
                    { "data": "categoria" },
                    { "data": "curso_concluido" },
                    { "data": "dta_matricula" },
                    { "data": "primeiro_acesso" },
                    { "data": "ultimo_acesso" },
                    { "data": "status" }
                ]
            });

            // myTable.column(1).search(filterUsers4).draw();
            // myTable.column(2).search(filterCargo4).draw();
            // myTable.column(3).search(filterLotacao4).draw();
            // myTable.column(4).search(filterCategory4).draw();
            // myTable.column(5).search(filterCursos4).draw();
            // myTable.column(12).search(filterMencao4).draw();

            $("#filterUsers4").on('change',function(){
                var filterUsers4 = this.value;
                myTable.column(1).search(filterUsers4, true, false).draw();
            });

            $("#filterCargo4").on('change',function(){
                var filterCargo4 = this.value;
                myTable.column(2).search(filterCargo4, true, false).draw();
            });

            $("#filterLotacao4").on('change',function(){
                var filterLotacao4 = this.value;
                myTable.column(3).search(filterLotacao4, true, false).draw();
            });

            $("#filterCategory4").on('change',function(){
                var filterCategory4 = this.value;
                myTable.column(4).search(filterCategory4, true, false).draw();
            });

            $("#filterCursos4").on('change',function(){
                var filterCursos4 = this.value;
                myTable.column(5).search(filterCursos4, true, false).draw();
            });

            $("#filterStatus4").on('change',function(){
                var filterStatus4 = this.value;
                myTable.column(9).search(filterStatus4, true, false).draw();
            });

            $("#primeiroAcessoStart4").on('change',function(){
                var primeiroAcessoStart4 = $("#primeiroAcessoStart4").val();
                var primeiroAcessoEnd4 = $("#primeiroAcessoEnd4").val();
                var matriculaStart4 = $("#matriculaStart4").val();
                var matriculaEnd4 = $("#matriculaEnd4").val();
                var ultacessoStart4 = $("#ultacessoStart4").val();
                var ultacessoEnd4 = $("#ultacessoEnd4").val();

                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "primeiroAcessoEnd4": primeiroAcessoEnd4,
                        "primeiroAcessoStart4": primeiroAcessoStart4,
                        "matriculaStart4": matriculaStart4,
                        "matriculaEnd4": matriculaEnd4,
                        "ultacessoStart4": ultacessoStart4,
                        "ultacessoEnd4": ultacessoEnd4
                    },
                    success: function(response){
                        var obj = jQuery.parseJSON(response);

                        if(obj.length > 0){
                            myTable.rows.add(obj).draw();
                        }
                    }
                });
            });

            $("#primeiroAcessoEnd4").on('change',function(){
                var primeiroAcessoStart4 = $("#primeiroAcessoStart4").val();
                var primeiroAcessoEnd4 = $("#primeiroAcessoEnd4").val();
                var matriculaStart4 = $("#matriculaStart4").val();
                var matriculaEnd4 = $("#matriculaEnd4").val();
                var ultacessoStart4 = $("#ultacessoStart4").val();
                var ultacessoEnd4 = $("#ultacessoEnd4").val();

                if(primeiroAcessoStart4 == ""){
                    alert('Selecione a data inicial primeiro');
                }else{
                    if(primeiroAcessoStart4 == primeiroAcessoEnd4){
                        return true;
                    }else{
                        myTable.rows().remove().draw();

                        $.ajax({
                            url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
                            type: 'GET',
                            dataType: 'text',
                            data: {
                                "id": idReport,
                                "primeiroAcessoEnd4": primeiroAcessoEnd4,
                                "primeiroAcessoStart4": primeiroAcessoStart4,
                                "matriculaStart4": matriculaStart4,
                                "matriculaEnd4": matriculaEnd4,
                                "ultacessoStart4": ultacessoStart4,
                                "ultacessoEnd4": ultacessoEnd4
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

            $("#matriculaStart4").on('change',function(){
                var primeiroAcessoStart4 = $("#primeiroAcessoStart4").val();
                var primeiroAcessoEnd4 = $("#primeiroAcessoEnd4").val();
                var matriculaStart4 = $("#matriculaStart4").val();
                var matriculaEnd4 = $("#matriculaEnd4").val();
                var ultacessoStart4 = $("#ultacessoStart4").val();
                var ultacessoEnd4 = $("#ultacessoEnd4").val();

                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "primeiroAcessoEnd4": primeiroAcessoEnd4,
                        "primeiroAcessoStart4": primeiroAcessoStart4,
                        "matriculaStart4": matriculaStart4,
                        "matriculaEnd4": matriculaEnd4,
                        "ultacessoStart4": ultacessoStart4,
                        "ultacessoEnd4": ultacessoEnd4
                    },
                    success: function(response){
                        var obj = jQuery.parseJSON(response);

                        if(obj.length > 0){
                            myTable.rows.add(obj).draw();
                        }
                    }
                });
            });

            $("#matriculaEnd4").on('change',function(){
                var primeiroAcessoStart4 = $("#primeiroAcessoStart4").val();
                var primeiroAcessoEnd4 = $("#primeiroAcessoEnd4").val();
                var matriculaStart4 = $("#matriculaStart4").val();
                var matriculaEnd4 = $("#matriculaEnd4").val();
                var ultacessoStart4 = $("#ultacessoStart4").val();
                var ultacessoEnd4 = $("#ultacessoEnd4").val();

                if(matriculaStart4 == ""){
                    alert('Selecione a data inicial primeiro');
                }else{
                    if(matriculaStart4 == matriculaEnd4){
                        return true;
                    }else{
                        myTable.rows().remove().draw();

                        $.ajax({
                            url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
                            type: 'GET',
                            dataType: 'text',
                            data: {
                                "id": idReport,
                                "primeiroAcessoEnd4": primeiroAcessoEnd4,
                                "primeiroAcessoStart4": primeiroAcessoStart4,
                                "matriculaStart4": matriculaStart4,
                                "matriculaEnd4": matriculaEnd4,
                                "ultacessoStart4": ultacessoStart4,
                                "ultacessoEnd4": ultacessoEnd4
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

            $("#ultacessoStart4").on('change',function(){
                var primeiroAcessoStart4 = $("#primeiroAcessoStart4").val();
                var primeiroAcessoEnd4 = $("#primeiroAcessoEnd4").val();
                var matriculaStart4 = $("#matriculaStart4").val();
                var matriculaEnd4 = $("#matriculaEnd4").val();
                var ultacessoStart4 = $("#ultacessoStart4").val();
                var ultacessoEnd4 = $("#ultacessoEnd4").val();

                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "primeiroAcessoEnd4": primeiroAcessoEnd4,
                        "primeiroAcessoStart4": primeiroAcessoStart4,
                        "matriculaStart4": matriculaStart4,
                        "matriculaEnd4": matriculaEnd4,
                        "ultacessoStart4": ultacessoStart4,
                        "ultacessoEnd4": ultacessoEnd4
                    },
                    success: function(response){
                        var obj = jQuery.parseJSON(response);

                        if(obj.length > 0){
                            myTable.rows.add(obj).draw();
                        }
                    }
                });
            });

            $("#ultacessoEnd4").on('change',function(){
                var primeiroAcessoStart4 = $("#primeiroAcessoStart4").val();
                var primeiroAcessoEnd4 = $("#primeiroAcessoEnd4").val();
                var matriculaStart4 = $("#matriculaStart4").val();
                var matriculaEnd4 = $("#matriculaEnd4").val();
                var ultacessoStart4 = $("#ultacessoStart4").val();
                var ultacessoEnd4 = $("#ultacessoEnd4").val();

                if(ultacessoStart4 == ""){
                    alert('Selecione a data inicial primeiro');
                }else{
                    if(ultacessoStart4 == ultacessoEnd4){
                        return true;
                    }else{
                        myTable.rows().remove().draw();

                        $.ajax({
                            url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
                            type: 'GET',
                            dataType: 'text',
                            data: {
                                "id": idReport,
                                "primeiroAcessoEnd4": primeiroAcessoEnd4,
                                "primeiroAcessoStart4": primeiroAcessoStart4,
                                "matriculaStart4": matriculaStart4,
                                "matriculaEnd4": matriculaEnd4,
                                "ultacessoStart4": ultacessoStart4,
                                "ultacessoEnd4": ultacessoEnd4
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

            $("#btnLimparFiltro4").on('click',function(){
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
                $("#primeiroAcessoStart4").val('');
                $("#primeiroAcessoEnd4").val('');
                $("#matriculaStart4").val('');
                $("#matriculaEnd4").val('');
                $("#ultacessoStart4").val('');
                $("#ultacessoEnd4").val('');

                myTable.column(1).search('').draw();
                myTable.column(2).search('').draw();
                myTable.column(3).search('').draw();
                myTable.column(4).search('').draw();
                myTable.column(5).search('').draw();
                myTable.column(9).search('').draw();

                myTable.rows().remove().draw();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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
                            columns: [ 1, 2, 3, 4 ]
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
                    { "data": "id" },
                    { "data": "sectionname" },
                    { "data": "activityname" },
                    { "data": "instrumento" },
                    { "data": "its_done" }
                ]
            });

            $("#filterUsers5").on('change',function(){
                var filterUsers5 = this.value;
                var courseWithGrades = $("#courseWithGrade").val();

                if($("#filterUsers5").val() !== ""){
                    $.ajax({
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/userCourses.php',
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
                var filterCursos5 = this.value;
                var filterUsers5 = $("#filterUsers5").val();

                $.ajax({
                    url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/userCabecalho.php',
                    type: 'GET',
                    dataType: 'text',
                    data: {
                        "id": idReport,
                        "filterUsers5": filterUsers5,
                        "filterCursos5":filterCursos5
                    },
                    success: function(response){
                        var obj = jQuery.parseJSON(response);

                        if(obj.length > 0){
                            for(var i=0; i<obj.length; i++){
                                $("#txtInscricao").html('');
                                $("#txtInscricao").html(obj[i].data_inscricao);

                                $("#txtNome").html('');
                                $("#txtNome").html(obj[i].nome);

                                $("#txtCargo").html('');
                                $("#txtCargo").html(obj[i].cargo);

                                $("#txtCertificado").html('');
                                $("#txtCertificado").html(obj[i].certificado);

                                $("#txtLotacao").html('');
                                $("#txtLotacao").html(obj[i].lotacao);

                                $("#txtStatus").html('');
                                $("#txtStatus").html(obj[i].status);

                                $("#txtEmissao").html('');
                                $("#txtEmissao").html(obj[i].dt_emissao_cert);

                                $("#txtCategoria").html('');
                                $("#txtCategoria").html(obj[i].categoryname);

                                $("#txtCurso").html('');
                                $("#txtCurso").html(obj[i].curso);

                                $("#txtSubCategoria").html('');
                                $("#txtSubCategoria").html(obj[i].subcategoryname);

                                $("#txtCargaHoraria").html('');
                                $("#txtCargaHoraria").html(obj[i].workload);
                            }
                        }else{
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
                        }
                    }
                });

                if(filterCursos5 !== ""){
                    myTable.rows().remove().draw();

                    $.ajax({
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
                        type: 'GET',
                        dataType: 'text',
                        data: {
                            "id": idReport,
                            "filterUsers5": filterUsers5,
                            "filterCursos5":filterCursos5
                        },
                        success: function(response){
                            var obj = jQuery.parseJSON(response);

                            if(obj.length > 0){
                                myTable.rows.add(obj).draw();
                            }
                        }
                    });
                }
            });

            $("#btnLimparFiltro5").on('click',function(){
                $("#filterUsers5").val('');
                $('#select2-filterUsers5-container').text('Selecione uma opção');
                $("#filterCursos5").html('');
                $("#filterCursos5").attr('disabled',true);

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

                myTable.rows().remove().draw();
            });
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
                //     "url" : "<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php",
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
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/tabela.php',
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
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/userCabecalho.php',
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
                        url: '<?=$CFG->wwwroot?>/blocks/eva_reports_users/servicos/userCourses.php',
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

            $("#btnLimparFiltro6").on('click',function(){
                $('#select2-filterCursos6-container').text('Selecione uma opção');
                $("#filterCursos6").val('');
                $('#select2-filterUsers6-container').text('Selecione uma opção');
                $("#filterUsers6").val('');


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

                myTable.column(5).search('').draw();
                myTable.column(1).search('').draw();

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