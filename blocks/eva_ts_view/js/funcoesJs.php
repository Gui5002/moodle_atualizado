<?php
global $CFG, $DB, $USER, $PAGE;

$path = $CFG->dirroot . '/blocks/eva_ts_view/img/header-logo4.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script>
    function buscaOrgaos(){
        let schInput = $("#searchInput").val();

        $.ajax({
            url: '<?=$CFG->wwwroot?>/blocks/eva_ts_view/servicos/preencher_orgao.php',
            type: 'POST',
            dataType: 'text',
            data: {
                q:schInput
            },
            success: function(response){
                var text = '';
                text = response;

                $("#checkOptions").remove();
                $("#box").html(text);
            }
        });
    }

    function modalShow(id){
        $.ajax({
            url: '<?=$CFG->wwwroot?>/blocks/eva_ts_view/servicos/aprovacao.php',
            type: 'GET',
            dataType: 'text',
            data: {
                id : id
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
        var x             = 0;
        let slcOrgan      = "";
        let slcNome       = $("#slcNome").val();
        let slcEixo       = $("#slcEixo").val();
        let slcPubAlvo    = $("#slcPubAlvo").val();
        let slcStatus     = $("#slcStatus").val();
        let slcModalidade = $("#slcModalidade").val();
        let slcTema       = $("#slcTema").val();
        let inpDateIni    = $("#inpDateIni").val();
        let inpDateFim    = $("#inpDateFim").val();

        $.each($("input[name='slcOrgan[]']:checked"), function(){
            if(x < 1){
                slcOrgan = $(this).val();
            }else{
                slcOrgan += ',' + $(this).val();
            }
            x = x + 1;
        });

        var myTable = $("#tab_sug").DataTable({
            "dom": 'Bfrtip',
            "fixedHeader": true,
            "responsive": true,
            "deferRender": true,
            "aaSorting": [
                [0, 'asc']
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
                    title: 'Lista de levantamento de Necessidades de Capacitação',
                    image: 'theme/evagu/images/footer-logo-h8.png',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [ 0, 2, 3, 4, 5, 7, 8, 12, 15, 16 ]
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
                { "data": "no_user" },
                { "data": "slc_cargo", visible: false },
                { "data": "no_organ" },
                { "data": "ds_theme" },
                { "data": "slc_priority_area_legal" },
                { "data": "slc_technical_legal" },
                { "data": "slc_comp_ass", visible: false },
                { "data": "ds_development_need" },
                { "data": "ds_target_audience" },
                { "data": "nu_participants", visible: false },
                { "data": "ds_transversality", visible: false },
                { "data": "ds_workload", visible: false },
                { "data": "slc_modality" },
                { "data": "slc_realizacao", visible: false },
                { "data": "no_instructor", visible: false },
                { "data": "nu_estimated_value" },
                { "data": "st_suggestion" },
                { "data": "slc_se_necessary", visible: false },
                { "data": "dt_suggestion", visible: false },

                { "data": "acoes" }
            ]

        });


        $.ajax({
            url: '<?=$CFG->wwwroot?>/blocks/eva_ts_view/servicos/tabela.php',
            type: 'GET',
            dataType: 'text',
            data: {
                slcOrgan : slcOrgan,
                slcNome : slcNome,
                slcEixo : slcEixo,
                slcPubAlvo : slcPubAlvo,
                slcStatus : slcStatus,
                slcModalidade : slcModalidade,
                slcTema : slcTema,
                inpDateIni : inpDateIni,
                inpDateFim : inpDateFim
            },
            success: function(response){
                var obj = jQuery.parseJSON(response);

                if(obj.length > 0){
                    myTable.rows.add(obj).draw();
                }
            }
        });

        $('#btnSlcAll').on('click',function(){
            $('input[name="slcOrgan[]"]').each(function(){
                this.checked = true;
            });
        });

        $('#btnLimpar').on('click',function(){
            $('input[name="slcOrgan[]"]').each(function(){
                this.checked = false;
            });
        });

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

        $("#btnLimparFiltro").on('click',function(){
            $("#slcNome").val('');
            $("#slcEixo").val('');
            $("#slcPubAlvo").val('');
            $("#slcStatus").val('');
            $("#slcModalidade").val('');
            $("#slcTema").val('');
            $("#inpDateIni").val('');
            $("#inpDateFim").val('');
            $("#searchInput").val('');

            $('input[name="slcOrgan[]"]').each(function(){
                this.checked = false;
            });

            myTable.rows().remove().draw();
            buscaOrgaos();

            $.ajax({
                url: '<?=$CFG->wwwroot?>/blocks/eva_ts_view/servicos/tabela.php',
                type: 'GET',
                dataType: 'text',
                data: {
                    slcOrgan : slcOrgan,
                    slcNome : slcNome,
                    slcEixo : slcEixo,
                    slcPubAlvo : slcPubAlvo,
                    slcStatus : slcStatus,
                    slcModalidade : slcModalidade,
                    slcTema : slcTema,
                    inpDateIni : inpDateIni,
                    inpDateFim : inpDateFim
                },
                success: function(response){
                    var obj = jQuery.parseJSON(response);

                    if(obj.length > 0){
                        myTable.rows.add(obj).draw();
                    }
                }
            });
        });

        $("#btnPesquisar").on('click',function(){
            var x             = 0;
            let slcOrgan      = "";
            let slcNome       = $("#slcNome").val();
            let slcEixo       = $("#slcEixo").val();
            let slcPubAlvo    = $("#slcPubAlvo").val();
            let slcStatus     = $("#slcStatus").val();
            let slcModalidade = $("#slcModalidade").val();
            let slcTema       = $("#slcTema").val();
            let inpDateIni    = $("#inpDateIni").val();
            let inpDateFim    = $("#inpDateFim").val();

            if((inpDateIni !== "") && (inpDateFim !== "")){
                var d1 = new Date(inpDateIni);
                var d2 = new Date(inpDateFim);

                if(d2 < d1){
                    alert('A data final tem que ser maior ou igual a data inicial');
                    return false;
                }
            }

            $.each($("input[name='slcOrgan[]']:checked"), function(){
                if(x < 1){
                    slcOrgan = $(this).val();
                }else{
                    slcOrgan += ',' + $(this).val();
                }
                x = x + 1;
            });

            myTable.rows().remove().draw();

            $.ajax({
                url: '<?=$CFG->wwwroot?>/blocks/eva_ts_view/servicos/tabela.php',
                type: 'GET',
                dataType: 'text',
                data: {
                    slcOrgan : slcOrgan,
                    slcNome : slcNome,
                    slcEixo : slcEixo,
                    slcPubAlvo : slcPubAlvo,
                    slcStatus : slcStatus,
                    slcModalidade : slcModalidade,
                    slcTema : slcTema,
                    inpDateIni : inpDateIni,
                    inpDateFim : inpDateFim
                },
                success: function(response){
                    var obj = jQuery.parseJSON(response);

                    if(obj.length > 0){
                        myTable.rows.add(obj).draw();
                    }
                }
            });
        });
    });
</script>