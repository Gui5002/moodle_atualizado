<?php
function xmldb_block_eva_course_details_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    $table = new xmldb_table('eva_course_workload');

    if (!$dbman->table_exists($table)) {
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('workload', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('tempoemmin', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        $dbman->create_table($table);
    } else {
        if (!$dbman->field_exists($table, 'tempoemmin')) {
            $field = new xmldb_field('tempoemmin', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
            $dbman->add_field($table, $field);
        }

        if ($dbman->field_exists($table, 'workload')) {
            $field = new xmldb_field('workload', XMLDB_TYPE_INTEGER, '20');
            $field->set_attributes(XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL);
            $dbman->change_field_type($table, $field);
        }
    }

    upgrade_block_savepoint(true, 202506261306, 'eva_course_details');
    return true;
}
