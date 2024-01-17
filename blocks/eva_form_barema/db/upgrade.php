<?php

function xmldb_block_eva_form_barema_upgrade($oldversion = 0) {
    global $DB;

    $dbman = $DB->get_manager();

    $result = true;

    if ($result && $oldversion < 2022010114) {

        // Define field posgraducao to be added to eva_barema_permissao.
        $table = new xmldb_table('eva_barema_permissao');
        $field = new xmldb_field('posgraduacao', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'user_id');
        // Conditionally launch add field posgraducao.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field1 = new xmldb_field('bolsa', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'posgraduacao');
        // Conditionally launch add field posgraducao.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        $field2 = new xmldb_field('afastamento', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'bolsa');
        // Conditionally launch add field posgraducao.
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }

        // Eva_form_barema savepoint reached.
        upgrade_block_savepoint(true, 2022010114, 'eva_form_barema');
    }

    return $result;
}

