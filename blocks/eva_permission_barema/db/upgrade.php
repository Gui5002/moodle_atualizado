<?php

function xmldb_block_eva_permission_barema_upgrade($oldversion = 0) {
    global $DB;

    $dbman = $DB->get_manager();

    $result = true;

    if ($result && $oldversion < 20240214015) {

        // ================== DEFINIÇÕES PARA CRIAR UM TABELA NOVA COM PRIMARIKEY ================================

        // $field1 = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        // $field2 = new xmldb_field('usuario_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'id');
        // $field3 = new xmldb_field('avaliador_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'usuario_id');
        // $field4 = new xmldb_field('qt_alunos', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'avaliador_id');

        // $key = new xmldb_key('id', XMLDB_KEY_PRIMARY, array('id'), null, null);
        // $table = new xmldb_table('eva_barema_distribuicao');
        // $table->addField($field1);
        // $table->addField($field2);
        // $table->addField($field3);
        // $table->addField($field4);

        // $table->addKey($key);

        // if (!$dbman->table_exists($table)){
        //     $dbman->create_table($table);
        // }

    // ================== DEFINIÇÕES PARA ACRESCENTAR COLUNAS NOVA EM UMA TABELA EXISTENTE ================================
        $table1 = new xmldb_table('eva_barema_permissao');
        $field_b1 = new xmldb_field('admin', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'afastamento');
        $field_b2 = new xmldb_field('status', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'created_at');
        $field_b3 = new xmldb_field('logs', XMLDB_TYPE_TEXT, null, null, null, null, null, 'created_at');
        $field_b4 = new xmldb_field('name', XMLDB_TYPE_TEXT, null, null, null, null, null, 'user_id');
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
        if (!$dbman->field_exists($table1, $field_b4)) {
            $dbman->add_field($table1, $field_b4);
        }

        // Eva_form_barema savepoint reached.
        upgrade_block_savepoint(true, 20240214015, 'eva_permission_barema');

    }

    return $result;
}

