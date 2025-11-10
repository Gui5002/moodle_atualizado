require(['jquery'], function ($) {

    $(document).ready(function () {

        $('.eva-catalogo-container').each(function () {

            var block = $(this);
            var allCourses, currentPage, coursesPerPage, totalPages;

            try {
                allCourses = JSON.parse(block.attr('data-cursos'));
                currentPage = 1;
                coursesPerPage = 12;
                totalPages = 1;
            } catch (e) {
                console.error('Bloco Catálogo: Falha ao ler ou interpretar os dados JSON. Bloco não inicializado.', block.attr('id'), e);
                return;
            }

            var form = block.find('.js-filter-form');
            var searchInput = form.find('.js-filtro-nome');
            var cargaHorariaInputs = form.find('input[name="carga_horaria"]');
            var categoryInputs = form.find('input[name="category[]"]');
            var tagInputs = form.find('input[name="tags[]"]');
            var dateStartInput = form.find('.js-filtro-data-inicio');
            var dateEndInput = form.find('.js-filtro-data-fim');
            var clearButton = form.find('.js-btn-clear');
            var cardsContainer = block.find('.js-catalogo-cards');
            var paginationContainer = block.find('.js-pagination-container');

            function renderCourses(coursesToRender) {
                cardsContainer.empty();
                if (!coursesToRender.length) {
                    cardsContainer.html('<div class="no-results w-100 text-center">Nenhum curso encontrado.</div>');
                    return;
                }
                coursesToRender.forEach(function (course) {
                    var tagsHTML = course.tags_array.map(function (tag) { return '<span class="eva-tag">' + tag + '</span>'; }).join(' ');
                    var cardHTML = '<div class="eva-card">' +
                        '<img src="' + course.image + '" alt="' + course.title + '" class="eva-img" />' +
                        '<div class="eva-content">' +
                        '<a href="' + course.courseurl + '"><h3>' + course.title + '</h3></a>' +
                        '<p><strong>Carga horária:</strong> ' + course.workload + '</p>' +
                        '<div class="eva-botao">' +
                        '<a href="' + course.courseurl + '" class="btn-primary">Ver mais</a>' +
                        '<a href="' + course.url + '" class="btn-secondary">Inscrição</a>' +
                        '</div></div>' +
                        '<div class="eva_tags mb-2 ms-2" style="min-height: 2.2rem;">' + tagsHTML + '</div>' +
                        '</div>';
                    cardsContainer.append(cardHTML);
                });
            }

            function renderPagination() {
                paginationContainer.empty();
                if (totalPages <= 1) return;
                var optionsHTML = '';
                for (var i = 1; i <= totalPages; i++) {
                    optionsHTML += '<option value="' + i + '" ' + (i === currentPage ? 'selected' : '') + '>' + i + '</option>';
                }
                var paginationHTML = '<div class="eva-pagination-controls">' +
                    '<button class="btn page-arrow" data-direction="-1" ' + (currentPage === 1 ? 'disabled' : '') + '><</button>' +
                    '<div class="page-display">Página <select class="form-control page-select-dropdown">' + optionsHTML + '</select> de ' + totalPages + '</div>' +
                    '<button class="btn page-arrow" data-direction="1" ' + (currentPage === totalPages ? 'disabled' : '') + '>></button>' +
                    '</div>';
                paginationContainer.html(paginationHTML);
            }

            function applyFiltersAndRender() {
                cardsContainer.html('<div class="spinner">Filtrando...</div>');
                var searchTerm = searchInput.val().toLowerCase();
                var selectedCarga = cargaHorariaInputs.filter(':checked').val();
                var selectedCategories = categoryInputs.filter(':checked').map(function (_, el) { return $(el).val(); }).get();
                var selectedTags = tagInputs.filter(':checked').map(function (_, el) { return $(el).val(); }).get();
                var startDate = dateStartInput.val();
                var endDate = dateEndInput.val();

                var filteredCourses = allCourses.filter(function (course) {
                    if (searchTerm && course.title.toLowerCase().indexOf(searchTerm) === -1) return false;
                    if (startDate && course.startdate < startDate) return false;
                    if (endDate && course.startdate > endDate) return false;
                    if (selectedCarga) {
                        var cargaValue = parseInt(selectedCarga, 10);
                        if (cargaValue === 101) { if (course.raw_workload <= 6000) return false; }
                        else { if (course.raw_workload > (cargaValue * 60)) return false; }
                    }
                    if (selectedCategories.length > 0 && !selectedCategories.some(function (catPath) { return course.category.startsWith(catPath); })) return false;
                    if (selectedTags.length > 0 && !selectedTags.some(function (tag) { return course.tags_array.indexOf(tag) > -1; })) return false;
                    return true;
                });

                totalPages = Math.ceil(filteredCourses.length / coursesPerPage);
                var startIndex = (currentPage - 1) * coursesPerPage;
                var coursesForCurrentPage = filteredCourses.slice(startIndex, startIndex + coursesPerPage);

                renderCourses(coursesForCurrentPage);
                renderPagination();
            }

            form.on('submit', function (e) { e.preventDefault(); });
            var allFilterInputs = form.find('input, select');
            allFilterInputs.on('change', function () { currentPage = 1; applyFiltersAndRender(); });
            searchInput.on('keyup', function () { currentPage = 1; applyFiltersAndRender(); });
            clearButton.on('click', function () { form[0].reset(); currentPage = 1; applyFiltersAndRender(); });

            paginationContainer.on('click', '.page-arrow', function (e) {
                e.preventDefault();
                var newPage = currentPage + parseInt($(this).data('direction'), 10);
                if (newPage >= 1 && newPage <= totalPages) {
                    currentPage = newPage;
                    applyFiltersAndRender();
                    $('html, body').animate({ scrollTop: cardsContainer.offset().top - 30 }, 400);
                }
            });
            paginationContainer.on('change', '.page-select-dropdown', function (e) {
                e.preventDefault();
                currentPage = parseInt($(this).val(), 10);
                applyFiltersAndRender();
                $('html, body').animate({ scrollTop: cardsContainer.offset().top - 30 }, 400);
            });

            applyFiltersAndRender();
        });
    });
});
