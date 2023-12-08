<?php

function xmldb_block_eva_form_barema_upgrade($oldversion = 0) {
    global $DB;

    $dbman = $DB->get_manager();

    $result = true;

    if ($result && $oldversion < 2022061414) {

        // ================== DEFINIÇÕES PARA CRIAR UM TABELA NOVA COM PRIMARIKEY ================================

        $field1 = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        $field2 = new xmldb_field('usuario_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'id');
        $field3 = new xmldb_field('avaliador_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'usuario_id');
        $field4 = new xmldb_field('qt_alunos', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'avaliador_id');

        $key = new xmldb_key('id', XMLDB_KEY_PRIMARY, array('id'), null, null);
        $table = new xmldb_table('eva_barema_distribuicao');
        $table->addField($field1);
        $table->addField($field2);
        $table->addField($field3);
        $table->addField($field4);

        $table->addKey($key);

        if (!$dbman->table_exists($table)){
            $dbman->create_table($table);
        }

        // Define field quantidade de avaliando to be added to eva_form_barema.
        $table1 = new xmldb_table('eva_barema_avaliador');
        $field_b1 = new xmldb_field('qtd_avaliados', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'tb_atividade_id');
        $field_b2 = new xmldb_field('qtd_alunos', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'qtd_avaliados');
        $field_b3 = new xmldb_field('data_atribuicao', XMLDB_TYPE_CHAR, '25', null, false, null, null, 'url_avaliacao');
        // Conditionally launch add field posgraducao.
        if (!$dbman->field_exists($table1, $field_b1)) {
            $dbman->add_field($table1, $field_b1);
        }
        if (!$dbman->field_exists($table1, $field_b2)) {
            $dbman->add_field($table1, $field_b2);
        }
        if (!$dbman->field_exists($table1, $field_b3)) {
            $dbman->add_field($table1, $field_b3);
        }


        $field_c1 = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        $field_c2 = new xmldb_field('tb_avaliador_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'id');
        $field_c3 = new xmldb_field('avaliador_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'tb_avaliador_id');
        $field_c4 = new xmldb_field('alunos_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'avaliador_id');
        $field_c5 = new xmldb_field('status', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'alunos_id');
        $field_c6 = new xmldb_field('quiz_att_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'status');
        $field_c7 = new xmldb_field('prazo', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'quiz_att_id');
        $field_c8 = new xmldb_field('datafinish', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'prazo');

        $key_c = new xmldb_key('id', XMLDB_KEY_PRIMARY, array('id'), null, null);
        $table_c = new xmldb_table('eva_barema_alunos');
        $table_c->addField($field_c1);
        $table_c->addField($field_c2);
        $table_c->addField($field_c3);
        $table_c->addField($field_c4);
        $table_c->addField($field_c5);
        $table_c->addField($field_c6);
        $table_c->addField($field_c7);
        $table_c->addField($field_c8);

        $table_c->addKey($key_c);

        if (!$dbman->table_exists($table_c)){
            $dbman->create_table($table_c);
        }
        if (!$dbman->field_exists($table_c, $field_c7)) {
            $dbman->add_field($table_c, $field_c7);
        }
        if (!$dbman->field_exists($table_c, $field_c8)) {
            $dbman->add_field($table_c, $field_c8);
        }

        $field_d1 = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        $field_d2 = new xmldb_field('avaliador_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'id');
        $field_d3 = new xmldb_field('status', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'avaliador_id');

        $key_d = new xmldb_key('id', XMLDB_KEY_PRIMARY, array('id'), null, null);
        $table_d = new xmldb_table('eva_barema_avaliadores');
        $table_d->addField($field_d1);
        $table_d->addField($field_d2);
        $table_d->addField($field_d3);

        $table_d->addKey($key_d);

        if (!$dbman->table_exists($table_d)){
            $dbman->create_table($table_d);
        }

        // Eva_form_barema savepoint reached.
        upgrade_block_savepoint(true, 2022061414, 'eva_form_barema');

    }

    return $result;
}

