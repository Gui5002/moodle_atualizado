<?php

function xmldb_block_eva_barema_bolsa_upgrade($oldversion = 0) {
    global $DB;

    $dbman = $DB->get_manager();

    $result = true;

    if ($result && $oldversion < 2023092605) {

        //==================ALTERACAO DA TABELA LISTA DE AVALIADORES===================================

        $field1 = new xmldb_field('titular_user_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'id');
        $field2 = new xmldb_field('suplente_user_id');
        $field3 = new xmldb_field('status_suplente');
        $field4 = new xmldb_field('status_titular', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'eixo');
        // Launch rename field user_id.
        $table = new xmldb_table('eva_bolsa_avaliadores');
        $dbman->rename_field($table, $field1, 'user_id');
        $dbman->rename_field($table, $field4, 'status');
        if ($dbman->field_exists($table, $field2)) {
            $dbman->drop_field($table, $field2);
        }
        if ($dbman->field_exists($table, $field3)) {
            $dbman->drop_field($table, $field3);
        }


        $field1 = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        $field2 = new xmldb_field('nome', XMLDB_TYPE_TEXT, null, null, null, null, null, 'id');
        $field3 = new xmldb_field('path', XMLDB_TYPE_TEXT, null, null, false, null, null, 'nome');
        $field4 = new xmldb_field('status', XMLDB_TYPE_INTEGER, '10', null, null, null, '1', 'value');

        $key = new xmldb_key('id', XMLDB_KEY_PRIMARY, array('id'), null, null);
        $table = new xmldb_table('eva_file_anteprojeto');
        $table->addField($field1);
        $table->addField($field2);
        $table->addField($field3);
        $table->addField($field4);

        $table->addKey($key);

        if (!$dbman->table_exists($table)){
            $dbman->create_table($table);
        }

        // Eva_form_barema savepoint reached.
        upgrade_block_savepoint(true, 2023092605, 'eva_barema_bolsa');
    }

    return $result;
}

