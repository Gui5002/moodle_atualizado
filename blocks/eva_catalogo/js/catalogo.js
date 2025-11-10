require(['jquery'], function ($) {
    $(document).ready(function () {
        // ⬆️ Botão Voltar ao Topo
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('#go-to-top').fadeIn();
            } else {
                $('#go-to-top').fadeOut();
            }
        });

        // Ativa tooltips e abre filtros ao passar o mouse
        function openFilter(filter) {
            $(filter).click(function () {
                $(`${filter} .tooltiptext`).removeClass('show');
                $(filter).addClass('open');
            });

            $(filter).hover(function () {
                if ($(`${filter} .tooltiptext span`).length >= 1) {
                    $(`${filter} .tooltiptext`).addClass('show');
                }
            }, function () {
                $(`${filter} .tooltiptext`).removeClass('show');
                $(filter).removeClass('open');
            });
        }

        // Aplica tooltips aos filtros ativos
        function addTooltipFilter(text, element) {
            const target = $(`#${element} .tooltiptext`);
            const span = $('<span>').text(text);
            target.append(span);
        }

        // Busca checkboxes marcados ao carregar
        $('#filterForm').find('input:checked').each(function () {
            const checkValue = this.value;
            const checkLabel = $.trim($(`label[for="${this.id}"]`).text()) || $(this).parent().text().trim();
            const filterParent = this.closest('.filter-box');
            const parentId = $(filterParent).attr('id');
            if (checkValue !== 'Todas') {
                addTooltipFilter(checkLabel, parentId);
            }
        });

        // Ativa interações dos filtros
        openFilter('#paramsCH');
        openFilter('#paramsModalidade');
        openFilter('#paramsInscricao');
        openFilter('#paramsContent');
    });
});


// AJAX para filtro dinâmico dos cursos
$('#filterForm').on('submit', function (e) {
    e.preventDefault(); // impede o envio tradicional

    const form = $(this);
    const formData = form.serialize();

    // Mostra spinner
    $('#catalogo-cards').html('<div class="spinner">Carregando...</div>');

    $.ajax({
        url: window.location.href,
        method: 'GET',
        data: formData,
        success: function (response) {
            // Extrai apenas a seção dos cards do HTML retornado
            const newCards = $(response).find('#catalogo-cards').html();
            $('#catalogo-cards').html(newCards);
        },
        error: function () {
            $('#catalogo-cards').html('<p class="error">Erro ao buscar cursos. Tente novamente.</p>');
        }
    });
});


function removerFiltro(nome, valor = null) {
    const form = document.getElementById('filterForm');
    if (valor === null) {
        const input = form.querySelector(`[name="${nome}"]`);
        if (input) input.value = '';
    } else {
        const checkbox = form.querySelector(`[name="${nome}"][value="${valor}"]`);
        if (checkbox) checkbox.checked = false;
    }
    form.dispatchEvent(new Event('submit'));
}
