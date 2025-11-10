<?php
global $CFG, $DB, $USER, $PAGE;

$path = $CFG->dirroot . '/blocks/eva_reports_users/img/header-logo4.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = @file_get_contents($path);

// Base64 se ler o arquivo; senão, fallback por URL mesma-origem.
$imgsrc = ($data !== false)
    ? ('data:image/' . $type . ';base64,' . base64_encode($data))
    : ($CFG->wwwroot . '/blocks/eva_reports_users/img/header-logo4.png');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<script>
    // Expõe a src da logo ao JS
    const EVA_LOGO_SRC = <?= json_encode($imgsrc) ?>;
    const MOODLE_ROOT  = <?= json_encode($CFG->wwwroot) ?>;

    $(document).ready(function () {
        // 1) Garantir estrutura: dentro do MESMO <form> do botão PDF,
        // criar #pdfcontent logo após o botão e mover #tab_sug para dentro.
        const $btnPDF = $("#btn-pdf");
        const $form   = $btnPDF.closest("form");

        // Se não achou form, não forçamos nada (evita quebrar), mas logamos:
        if ($form.length) {
            // Cria #pdfcontent logo após o botão, se ainda não existir
            let $pdfcontent = $("#pdfcontent");
            if (!$pdfcontent.length) {
                $pdfcontent = $('<div id="pdfcontent" style="padding:10px 10px 0;"></div>');
                $btnPDF.after($pdfcontent); // => abaixo do botão
            }

            // Insere a LOGO centralizada no topo do #pdfcontent (se ainda não tiver)
            if (!$("#pdf-header-logo").length) {
                const $logoWrap = $(`
                    <div id="pdf-logo-wrapper"
                         style="display:flex;justify-content:center;align-items:center;margin:10px 0 14px 0;">
                        <img id="pdf-header-logo"
                             src="${EVA_LOGO_SRC}"
                             alt="ESAGU"
                             style="height:42px;max-width:320px;display:block;"
                             crossOrigin="anonymous" />
                    </div>
                `);
                $pdfcontent.append($logoWrap);
            }

            // Move #tab_sug para dentro de #pdfcontent (preserva id e DataTable)
            const $tab = $("#tab_sug");
            if ($tab.length && !$pdfcontent.find("#tab_sug").length) {
                $pdfcontent.append($tab);
            }
        }

        // 2) Clique do PDF – mantém sua lógica original e exporta só o #pdfcontent
        $("#btn-pdf").on("click", function () {
            $(".aumentaDiv").removeClass("col-md-3").addClass("col-md-5");
            $(".diminuiDiv").removeClass("col-md-9").addClass("col-md-7");

            const options = {
                margin: [5, 10, 5, 10],
                filename: 'Histórico ESAGU.pdf',
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { scale: 3, useCORS: true, backgroundColor: '#FFFFFF' },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };

            const element = document.getElementById('pdfcontent'); // logo + tabela
            if (!element) {
                // fallback: mantém seu alvo antigo para não quebrar nada
                const oldTarget = document.getElementById('tab_sug');
                if (oldTarget) {
                    html2pdf().from(oldTarget).set(options).save().then(function () {
                        $(".aumentaDiv").removeClass("col-md-5").addClass("col-md-3");
                        $(".diminuiDiv").removeClass("col-md-7").addClass("col-md-9");
                    });
                }
                return;
            }

            // Aguarda imagens do container (logo) carregarem
            const imgs = element.querySelectorAll('img');
            const waitImgs = [];
            imgs.forEach((img) => {
                if (!img.complete) {
                    waitImgs.push(new Promise((res) => { img.onload = img.onerror = res; }));
                }
            });

            Promise.all(waitImgs).then(function () {
                html2pdf().from(element).set(options).save().then(function () {
                    $(".aumentaDiv").removeClass("col-md-5").addClass("col-md-3");
                    $(".diminuiDiv").removeClass("col-md-7").addClass("col-md-9");
                });
            });
        });

        // 3) Suas funcionalidades originais (mantidas)
        let idReport = $("#idReport").val();

        if (idReport == '1') {
            function somarCargasConcluidas() {
                var totalCargaConcluida = 0;
                var totalCursosConcluidos = 0;

                myTable.rows().eq(0).each(function(index) {
                    var rowData = myTable.row(index).data();
                    if (rowData.mencao === "CONCLUÍDO") {
                        var cargaArray = rowData.carga.split(":");
                        var horas = parseInt(cargaArray[0]);
                        var minutos = parseInt(cargaArray[1]);
                        var segundos = parseInt(cargaArray[2]);
                        var totalSegundos = horas * 3600 + minutos * 60 + segundos;
                        totalCargaConcluida += totalSegundos;
                        totalCursosConcluidos++;
                    }
                });

                var horas = Math.floor(totalCargaConcluida / 3600);
                var minutos = Math.floor((totalCargaConcluida % 3600) / 60);
                minutos = minutos.toString().padStart(2, '0');

                var total = [];
                total.push(horas + ' hrs ' + minutos + ' min');
                total.push(totalCursosConcluidos);

                return total;
            }

            var myTable = $("#tab_sug").DataTable({
                "dom": 'Bfrtip',
                "fixedHeader": false,
                "responsive": true,
                "deferRender": true,
                "searching": false,
                "oSearch": {"bSmart": false},
                "ajax": {
                    "url" : MOODLE_ROOT + "/blocks/eva_reports_users/servicos/tabela.php",
                    "dataSrc": "",
                    "data": { "id": idReport },
                    "complete": function(data) {
                        setTimeout(function() {
                            var total = somarCargasConcluidas();
                            $('#totalConcluido').text(total[0]);
                            $('#concluidos').text(total[1]);
                        }, 100);
                    }
                },
                "aaSorting": [[0, 'asc']],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                },
                "buttons": [],
                "pageLength": -1,
                "paging": false,
                "columns": [
                    {
                        "data": "nome_curso",
                        "render": function (data, type, row) {
                            const id = row.course_id ? row.course_id : '';
                            return '<a href="' + MOODLE_ROOT + '/course/view.php?id=' + id + '">' + data + '</a>';
                        }
                    },
                    { "data": "carga" },
                    { "data": "progresso" },
                    { "data": "nota" },
                    { "data": "mencao" },
                    { "data": "opcoes" }
                ],
                "columnDefs": [
                    { targets: [1,2,3,4,5], className: 'dt-center' },
                    { targets: [1,2,3,4,5], className: 'col-md-1' }
                ]
            });
        }
    });
</script>
