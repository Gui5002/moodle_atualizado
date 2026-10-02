<?php

/*
 * @ccnRef: @template block_eva_library_list
 *
 * Compatibilidade com Moodle 3.5 / tema EVAGU.
 */

defined('MOODLE_INTERNAL') || die();

global $CFG, $DB;

require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->libdir . '/coursecatlib.php');
require_once($CFG->libdir . '/filelib.php');

$_ccnlibrarylist = '';
$_ccnlibrarylist .= '<ul id="vertical-menu" class="mega-vertical-menu nav navbar-nav">';

/*
 * No Moodle desta instalação, as categorias são manipuladas
 * através da classe coursecat.
 *
 * ID 0 representa a categoria raiz do Moodle.
 */
$topcategory = coursecat::get(0);

if ($topcategory) {

    /*
     * Recupera as categorias de primeiro nível que o usuário
     * atual pode visualizar.
     */
    $categories = $topcategory->get_children();

    if (!empty($categories)) {

        /*
         * Mantém a lógica original do tema EVAGU:
         *
         * - exibe o menu quando há mais de uma categoria;
         * - ou quando existe uma categoria e o Moodle possui
         *   mais de 200 cursos.
         */
        if (
            count($categories) > 1 ||
            (count($categories) == 1 && $DB->count_records('course') > 200)
        ) {

            foreach ($categories as $category) {

                /*
                 * Recupera os cursos diretamente pertencentes
                 * à categoria.
                 */
                $children_courses = $category->get_courses();

                $outputimage = '';

                /*
                 * Mantida a lógica original para localizar
                 * a imagem de apresentação do primeiro curso.
                 */
                if (!empty($children_courses)) {

                    $firstcourse = reset($children_courses);

                    if ($firstcourse) {

                        $overviewfiles = $firstcourse->get_course_overviewfiles();

                        foreach ($overviewfiles as $file) {

                            if ($file->is_valid_image()) {

                                /*
                                 * Inclui itemid na URL do pluginfile.
                                 * Essa alteração já existia no arquivo
                                 * personalizado do EVAGU.
                                 */
                                $imagepath =
                                    '/' . $file->get_contextid() .
                                    '/' . $file->get_component() .
                                    '/' . $file->get_filearea() .
                                    '/' . $file->get_itemid() .
                                    $file->get_filepath() .
                                    $file->get_filename();

                                $imageurl = file_encode_url(
                                    $CFG->wwwroot . '/pluginfile.php',
                                    $imagepath,
                                    false
                                );

                                $outputimage = $imageurl;

                                // Utiliza apenas a primeira imagem encontrada.
                                break;
                            }
                        }
                    }
                }

                /*
                 * Nome formatado da categoria.
                 */
                $categoryname = $category->get_formatted_name();

                /*
                 * Mantém a aparência "dimmed" para categorias
                 * que não estejam visíveis.
                 */
                $linkcss = $category->visible ? '' : ' class="dimmed" ';

                /*
                 * Gera o item do menu.
                 */
                $_ccnlibrarylist .=
                    '<li>' .
                        '<a href="' .
                            $CFG->wwwroot .
                            '/course/index.php?categoryid=' .
                            $category->id .
                            '"' .
                            $linkcss .
                        '>' .
                            $categoryname .
                        '</a>' .
                    '</li>';
            }
        }
    }
}

$_ccnlibrarylist .= '</ul>';