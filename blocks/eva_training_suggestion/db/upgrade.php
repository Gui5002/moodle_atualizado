<?php

function xmldb_block_eva_training_suggestion_upgrade($oldversion = 0) {
    global $DB;
    $dbman = $DB->get_manager();

    $result = true;

    if ($result && $oldversion < 2021020123.24) {

        // Define field id_slc_cargo to be added to eva_training_suggestion.
        $table = new xmldb_table('eva_training_suggestion');
        $field1 = new xmldb_field('id_slc_cargo', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'id_superior_organ');
        $field2 = new xmldb_field('id_slc_comp_ass', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'slc_technical_legal');
        $field3 = new xmldb_field('slc_se_necessary', XMLDB_TYPE_CHAR, '20', null, null, null, null, 'nu_estimated_value');
        $field4 = new xmldb_field('id_slc_realizacao', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'id_slc_comp_ass');

        // Conditionally launch add field id_slc_cargo.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }
        if (!$dbman->field_exists($table, $field3)) {
            $dbman->add_field($table, $field3);
        }
        if (!$dbman->field_exists($table, $field4)) {
            $dbman->add_field($table, $field4);
        }

        // Eva_training_suggestion savepoint reached.
        upgrade_block_savepoint(true, 2021020123.24, 'eva_training_suggestion');
    }
    return $result;
}
