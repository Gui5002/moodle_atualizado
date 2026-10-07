<?php

defined('MOODLE_INTERNAL') || die;

function xmldb_theme_evagu_upgrade($oldversion) {
    global $CFG, $DB;
    $dbman = $DB->get_manager();
    $result = TRUE;


    if ($oldversion < 2020030922) {

        // Define table theme_evagu to be created.
        $table = new xmldb_table('theme_evagu');

        // Adding fields to table theme_evagu.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);

        // Adding keys to table theme_evagu.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for theme_evagu.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('theme_evagu_counter');

        // Adding fields to table theme_evagu_counter.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('course', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('ip', XMLDB_TYPE_CHAR, '15', null, XMLDB_NOTNULL, null, null);
        $table->add_field('time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table theme_evagu_counter.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table theme_evagu_counter.
        $table->add_index('time', XMLDB_INDEX_NOTUNIQUE, ['time']);
        $table->add_index('course', XMLDB_INDEX_NOTUNIQUE, ['course']);

        // Conditionally launch create table for theme_evagu_counter.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('theme_evagu_stats');

        // Adding fields to table theme_evagu_stats.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('course', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('currenttime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('sum', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table theme_evagu_stats.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table theme_evagu_stats.
        $table->add_index('course', XMLDB_INDEX_NOTUNIQUE, ['course']);

        // Conditionally launch create table for theme_evagu_stats.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // evagu savepoint reached.
        upgrade_plugin_savepoint(true, 2020030922, 'theme', 'evagu');
    }

    if ($oldversion < 2020050618) {

        // Define table theme_evagu_courserate to be created.
        $table = new xmldb_table('theme_evagu_courserate');

        // Adding fields to table theme_evagu_courserate.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('course', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('user', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('rating', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table theme_evagu_courserate.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table theme_evagu_courserate.
        $table->add_index('index', XMLDB_INDEX_UNIQUE, ['course', 'user']);

        // Conditionally launch create table for theme_evagu_courserate.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // evagu savepoint reached.
        upgrade_plugin_savepoint(true, 2020050618, 'theme', 'evagu');
    }

    // Moodle 3.5 compatibility: the core My overview block expects
    // block_myoverview/defaulttab to exist. In databases that came from a
    // newer EVAGU/Moodle setup this setting may be absent, which causes an
    // Undefined property: stdClass::$defaulttab notice before the dashboard
    // can be rendered.
    if ($oldversion < 202610070001) {
        if (get_config('block_myoverview', 'defaulttab') === false) {
            set_config('defaulttab', 'timeline', 'block_myoverview');
        }

        upgrade_plugin_savepoint(true, 202610070001, 'theme', 'evagu');
    }

    return $result;
}
