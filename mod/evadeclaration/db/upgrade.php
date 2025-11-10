<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

// This file keeps track of upgrades to
// the declaration module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installation to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php.
defined('MOODLE_INTERNAL') || die();

function xmldb_evadeclaration_upgrade($oldversion = 0) {
    global $DB;

    $dbman = $DB->get_manager();
    if ($oldversion < 2024053102) {

        $table = new xmldb_table('evadeclaration');
        $field = new xmldb_field('disablecode', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'requiredtime');

        // Conditionally launch add field disablecode.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('codex', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '10', 'disablecode');

        // Conditionally launch add field codex.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('codey', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '10', 'codex');

        // Conditionally launch add field codey.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('enablesecondpage', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'codey');

        // Conditionally launch add field enablesecondpage.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('secondpagex', XMLDB_TYPE_INTEGER, '4', null, null, null, '10', 'enablesecondpage');

        // Conditionally launch add field secondpagex.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('secondpagey', XMLDB_TYPE_INTEGER, '4', null, null, null, '50', 'secondpagex');

        // Conditionally launch add field secondpagey.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('secondpagetext', XMLDB_TYPE_TEXT, null, null, null, null, null, 'secondpagey');

        // Conditionally launch add field secondpagetext.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('secondpagetextformat', XMLDB_TYPE_TEXT, null, null, null, null, null, 'secondpagetext');

        // Conditionally launch add field secondpagetextformat.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('secondimage', XMLDB_TYPE_TEXT, null, null, null, null, null, 'secondpagetextformat');

        // Conditionally launch add field secondimage.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Changing type of field decldatefmt on table evadeclaration to char.
        $field = new xmldb_field('decldatefmt', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'decldate');

        // Launch change of type for field decldatefmt.
        $dbman->change_field_type($table, $field);

        // Updating old values (thanks hqhoang for reporting and fix it).
        $sql = 'UPDATE {evadeclaration} SET decldatefmt = :dateformat WHERE decldatefmt = :old_1';
        $DB->execute($sql, array('dateformat' => '%B %d, %Y', 'old_1' => '1'));

        $sql = 'UPDATE {evadeclaration} SET decldatefmt = :dateformat WHERE decldatefmt = :old_2';
        $DB->execute($sql, array('dateformat' => 'F jS, Y', 'old_2' => '2'));

        $sql = 'UPDATE {evadeclaration} SET decldatefmt = :dateformat WHERE decldatefmt = :old_3';
        $DB->execute($sql, array('dateformat' => '%d %B %Y', 'old_3' => '3'));

        $sql = 'UPDATE {evadeclaration} SET decldatefmt = :dateformat WHERE decldatefmt = :old_4';
        $DB->execute($sql, array('dateformat' => '%B %Y', 'old_4' => '4'));

        $sql = 'UPDATE {evadeclaration} SET decldatefmt = \'\' WHERE decldatefmt = :old_5 OR decldatefmt = :old_6';
        $DB->execute($sql, array('old_5' => '5', 'old_6' => '6'));

        // evadeclaration savepoint reached.
        upgrade_mod_savepoint(true, 2024053102, 'evadeclaration');
    }

    if ($oldversion < 2024092000) {

        // Changing nullability of field declarationimage on table evadeclaration to null.
        $table = new xmldb_table('evadeclaration');
        $field = new xmldb_field('declarationimage', XMLDB_TYPE_TEXT, null, null, null, null, null, 'height');

        // Launch change of type for field declarationimage.
        $dbman->change_field_type($table, $field);

        // Launch rename field disablecode->printqrcode.

        $field = new xmldb_field('disablecode', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'requiredtime');

        if ($dbman->field_exists($table, $field)) {
            $objs = $DB->get_records('evadeclaration', array("disablecode" => 0), '', 'id');
            $ids = '';

            foreach ($objs as $obj) {
                $ids = $ids . $obj->id . ',';
            }
            if (!empty($ids)) {
                $ids = chop($ids, ',');

                $sql = 'UPDATE {evadeclaration} SET disablecode = 1 WHERE id in (' . $ids . ')';
                $DB->execute($sql);

                $sql = 'UPDATE {evadeclaration} SET disablecode = 0 WHERE id not in (' . $ids . ')';
                $DB->execute($sql);
            }

            // Launch change of default for field.
            $dbman->change_field_default($table, $field);
            // Launch rename field printqrcode.
            $dbman->rename_field($table, $field, 'printqrcode');
        } else {
            $field = new xmldb_field('printqrcode', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'requiredtime');
            if (!$dbman->field_exists($table, $field)) {
                $dbman->add_field($table, $field);
            }
        }

        $field = new xmldb_field('qrcodefirstpage', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'printqrcode');

        // Conditionally launch add field qrcodefirstpage.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('savedecl');

        // Conditionally launch drop field savedecl.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        $table = new xmldb_table('evadeclaration_issues');
        $field = new xmldb_field('declarationname', XMLDB_TYPE_TEXT, null, null, null, null, null, 'userid');

        // Conditionally launch add field declarationname.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('username');

        // Conditionally launch drop field username.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        $field = new xmldb_field('coursename');

        // Conditionally launch drop field coursename.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Populating declarationname.
        $decls = $DB->get_records('evadeclaration');
        foreach ($decls as $decl) {
            $DB->execute('UPDATE {evadeclaration_issues} SET declarationname = ? WHERE declarationid = ?',
                        array($decl->name, $decl->id));
        }

        // evadeclaration savepoint reached.
        upgrade_mod_savepoint(true, 2024092000, 'evadeclaration');
    }

    if ($oldversion < 2024111900) {

        // decldate update.
        $objs = $DB->get_records('evadeclaration', array("decldate" => 1), '', 'id');
        $objs = $objs + $DB->get_records('evadeclaration', array("decldate" => 2), '', 'id');
        $ids = '';

        foreach ($objs as $obj) {
            $ids = $ids . $obj->id . ',';
        }
        if (!empty($ids)) {
            $ids = chop($ids, ',');
            $sql = 'UPDATE {evadeclaration} SET decldate = -1 * decldate where id in (' . $ids . ')';
            $DB->execute($sql);
        }

        // declgrade update.
        $objs = $DB->get_records('evadeclaration', array("declgrade" => 1), '', 'id');
        $ids = '';

        foreach ($objs as $obj) {
            $ids = $ids . $obj->id . ',';
        }
        if (!empty($ids)) {
            $ids = chop($ids, ',');
            $sql = 'UPDATE {evadeclaration} SET decldate = -1 * declgrade where id in (' . $ids . ')';
            $DB->execute($sql);
        }

        // evadeclaration savepoint reached.
        upgrade_mod_savepoint(true, 2024111900, 'evadeclaration');
    }
    if ($oldversion < 2024112500) {
        // Changing the default of field decldate on table evadeclaration to -2.
        $table = new xmldb_table('evadeclaration');
        $field = new xmldb_field('decldate', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '-2', 'outcome');

        // Launch change of default for field decldate.
        $dbman->change_field_default($table, $field);

        $field = new xmldb_field('emailothers', XMLDB_TYPE_TEXT, null, null, null, null, null, 'emailfrom');

        // Launch change of nullability for field emailothers.
        $dbman->change_field_notnull($table, $field);

        // evadeclaration savepoint reached.
        upgrade_mod_savepoint(true, 2024112500, 'evadeclaration');
    }

    if ($oldversion < 2024112901) {

        // Define field coursename to be added to evadeclaration_issues.
        $table = new xmldb_table('evadeclaration_issues');
        $field = new xmldb_field('coursename', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'timedeleted');

        // Conditionally launch add field coursename.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $sql = 'UPDATE {evadeclaration_issues} set coursename = (select fullname from {course} ';
        $sql .= 'where id = (select course from {evadeclaration} where id = declarationid)) where timedeleted is null';
        $DB->execute($sql);

        // evadeclaration savepoint reached.
        $field = new xmldb_field('haschange', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'coursename');

        // Conditionally launch add field haschange.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $sql = 'UPDATE {evadeclaration_issues} SET haschange = 1';
        $DB->execute($sql);

        // evadeclaration savepoint reached.
        upgrade_mod_savepoint(true, 2024112901, 'evadeclaration');
    }
    // ...v2.1.3.
    if ($oldversion < 2014051000) {

        // Define field timestartdatefmt to be added to evadeclaration.
        $table = new xmldb_table('evadeclaration');
        $field = new xmldb_field('timestartdatefmt', XMLDB_TYPE_CHAR, '255', null, null, null, '', 'secondimage');

        // Conditionally launch add field timestartdatefmt.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $table = new xmldb_table('evadeclaration_issues');

        $field = new xmldb_field('haschange', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'timedeleted');

        // Conditionally launch add field haschange.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('pathnamehash', XMLDB_TYPE_CHAR, '40', null, null, null, null, 'haschange');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Must move files to new area and add the declaration files hashs.
        $issueddecls = $DB->get_records('evadeclaration_issues');
        $countdecls = count($issueddecls);

        $fs = get_file_storage();

        $pbar = new progress_bar('evadeclarationmoveissuedfiles', 500, true);
        $i = 0;
        foreach ($issueddecls as $issued) {
            $i++;
            try {
                $courseid = $DB->get_field('evadeclaration', 'course', array('id' => $issued->declarationid), MUST_EXIST);
                $cm = get_coursemodule_from_instance('evadeclaration', $issued->declarationid, $courseid, false, MUST_EXIST);
                $context = context_module::instance($cm->id);

                $user = $DB->get_record("user", array('id' => $issued->userid));
                if ($user) {
                    $filename = str_replace(' ', '_',
                                            clean_filename(
                                               $issued->declarationname . ' ' . fullname($user) . ' ' . $issued->id . '.pdf'));
                } else {
                    $filename = str_replace(' ', '_', clean_filename($issued->declarationname . ' ' . $issued->id . '.pdf'));
                }

                $fileinfo = array('contextid' => $context->id, 'component' => 'mod_evadeclaration', 'filearea' => 'issues',
                    'itemid' => $issued->id, 'filepath' => '/', 'filename' => $filename);

                if ($fs->file_exists($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'], $fileinfo['itemid'],
                                    $fileinfo['filepath'], $fileinfo['filename'])) {

                    $file = $fs->get_file(
                        $fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
                        $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']
                    );

                    $fileinfo['filename'] = str_replace(
                        ' ', '_', clean_filename($issued->declarationname . ' ' . $issued->id . '.pdf')
                    );

                    $newfile = $fs->create_file_from_storedfile($fileinfo, $file);
                    if ($newfile) {
                        $file->delete();
                        $issued->pathnamehash = $newfile->get_pathnamehash();
                    }
                } else {
                    throw new moodle_exception('filenotfound', 'evadeclaration', null, null, '');
                }
            } catch (Exception $e) {
                if (empty($issued->timedeleted)) {
                    $issued->haschange = 1;
                }
                $issued->pathnamehash = '';
            }
            $pbar->update($i, $countdecls, "Moving Issued declaration files  ($i/$countdecls)");
            if (!$DB->update_record('evadeclaration_issues', $issued)) {
                print_error('upgradeerror', 'evadeclaration', null, "Can't update an issued declaration [id->$issued->id]");
            }
        }

        $field = new xmldb_field('pathnamehash', XMLDB_TYPE_CHAR, '40', null, XMLDB_NOTNULL, null, null, 'haschange');

        // Launch change of nullability for field pathnamehash.
        $dbman->change_field_notnull($table, $field);

        $field = new xmldb_field('coursename');

        // Conditionally launch drop field coursename.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // evadeclaration savepoint reached.
        upgrade_mod_savepoint(true, 2014051000, 'evadeclaration');
    }

    // ... v2.2.4.
    if ($oldversion < 2017013001) {

        // Define coursename in evadeclaration_issues table.
        $table = new xmldb_table('evadeclaration_issues');

        // ...<FIELD NAME="coursename" TYPE="char" LENGTH="255" NOTNULL="true" SEQUENCE="false" PREVIOUS="pathnamehash" />.
        $field = new xmldb_field('coursename', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '---', 'pathnamehash');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
            // Must add course name in new column.
            $issueddecls = $DB->get_records('evadeclaration_issues');
            $countdecls = count($issueddecls);
            $count = 0;
            $pbar = new progress_bar('evadeclarationupdate', 500, true);
            foreach ($issueddecls as $issued) {
                $coursename = $DB->get_field('evadeclaration', 'coursename', array('id' => $issued->declarationid));
                if (!$coursename) {
                    try {
                        $courseid = $DB->get_field(
                            'evadeclaration', 'course', array('id' => $issued->declarationid), MUST_EXIST
                        );
                        $coursename = $DB->get_field('course', 'fullname', array('id' => $courseid), MUST_EXIST);
                    } catch (Exception $e) {
                        if (empty($issued->timedeleted)) {
                            $issued->haschange = 1;
                        }
                        $coursename = '';
                    }
                }
                $issued->coursename = $coursename;
                if (!$DB->update_record('evadeclaration_issues', $issued)) {
                    print_error('upgradeerror', 'evadeclaration', null, "Can't update an issued declaration [id->$issued->id]");
                }
                $count++;
                $pbar->update($count, $countdecls, "Moving Issued declaration files  ($i/$countdecls)");
            }
        }
        // evadeclaration savepoint reached.
        upgrade_mod_savepoint(true, 2017013001, 'evadeclaration');
    }
    return true;
}
