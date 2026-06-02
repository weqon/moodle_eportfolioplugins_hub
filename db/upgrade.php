<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin upgrade steps are defined here.
 *
 * @package     eportfolioplugins_hub
 * @copyright   2025 weQon UG <support@weqon.net>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute eportfolioplugins_hub upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_eportfolioplugins_hub_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    // For further information please read {@link https://docs.moodle.org/dev/Upgrade_API}.
    //
    // You will also have to create the db/install.xml file by using the XMLDB Editor.
    // Documentation for the XMLDB Editor can be found at {@link https://docs.moodle.org/dev/XMLDB_editor}.

    if ($oldversion < 2025121204) {

        // Define table eportfolioplugins_hub to be created.
        $table = new xmldb_table('eportfolioplugins_hub');

        // Adding fields to table eportfolioplugins_hub.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('eportid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('access', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('approver', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('fileid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('fileidcontext', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('h5pid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table eportfolioplugins_hub.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for eportfolioplugins_hub.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Hub savepoint reached.
        upgrade_plugin_savepoint(true, 2025121204, 'eportfolioplugins', 'hub');
    }

    if ($oldversion < 2025121205) {

        // Define field approved to be added to eportfolioplugins_hub.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('approved', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'access');

        // Conditionally launch add field approved.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Hub savepoint reached.
        upgrade_plugin_savepoint(true, 2025121205, 'eportfolioplugins', 'hub');
    }

    if ($oldversion < 2025121206) {

        // Define field step to be added to eportfolioplugins_hub.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('step', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'access');

        // Conditionally launch add field step.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field description to be added to eportfolioplugins_hub.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null, 'title');

        // Conditionally launch add field description.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field notes to be added to eportfolioplugins_hub.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('notes', XMLDB_TYPE_TEXT, null, null, null, null, null, 'h5pid');

        // Conditionally launch add field notes.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Rename field access on table eportfolioplugins_hub to accesstype.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('access', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'eportid');

        // Launch rename field access.
        $dbman->rename_field($table, $field, 'accesstype');

        // Hub savepoint reached.
        upgrade_plugin_savepoint(true, 2025121206, 'eportfolioplugins', 'hub');
    }

    if ($oldversion < 2025121207) {
        // Changing type of field accesstype on table eportfolioplugins_hub to int.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('accesstype', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'eportid');

        // Launch change of type for field accesstype.
        $dbman->change_field_type($table, $field);

        // Hub savepoint reached.
        upgrade_plugin_savepoint(true, 2025121207, 'eportfolioplugins', 'hub');
    }

    if ($oldversion < 2026021800) {

        // Define field active to be added to eportfolioplugins_hub.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('active', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'eportid');

        // Conditionally launch add field active.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field deleted to be added to eportfolioplugins_hub.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('deleted', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'active');

        // Conditionally launch add field deleted.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Hub savepoint reached.
        upgrade_plugin_savepoint(true, 2026021800, 'eportfolioplugins', 'hub');
    }

    if ($oldversion < 2026042200) {

        // Define field publishedby to be added to eportfolioplugins_hub.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('publishedby', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'notes');

        // Conditionally launch add field publishedby.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Hub savepoint reached.
        upgrade_plugin_savepoint(true, 2026042200, 'eportfolioplugins', 'hub');
    }

    if ($oldversion < 2026042400) {

        // Define field approvedby to be added to eportfolioplugins_hub.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('approvedby', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'publishedby');

        // Conditionally launch add field approvedby.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field approveddate to be added to eportfolioplugins_hub.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('approveddate', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'approvedby');

        // Conditionally launch add field approveddate.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Hub savepoint reached.
        upgrade_plugin_savepoint(true, 2026042400, 'eportfolioplugins', 'hub');
    }

    if ($oldversion < 2026052600) {

        // Rename field courseid on table eportfolioplugins_hub to confirmed.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'description');

        // Launch rename field courseid.
        $dbman->rename_field($table, $field, 'confirmed');

        // Changing type of field confirmed on table eportfolioplugins_hub to int.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('confirmed', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'description');

        // Launch change of type for field confirmed.
        $dbman->change_field_type($table, $field);

        // Hub savepoint reached.
        upgrade_plugin_savepoint(true, 2026052600, 'eportfolioplugins', 'hub');
    }

    if ($oldversion < 2026052700) {

        // Define field usernotes to be added to eportfolioplugins_hub.
        $table = new xmldb_table('eportfolioplugins_hub');
        $field = new xmldb_field('usernotes', XMLDB_TYPE_TEXT, null, null, null, null, null, 'notes');

        // Conditionally launch add field usernotes.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Hub savepoint reached.
        upgrade_plugin_savepoint(true, 2026052700, 'eportfolioplugins', 'hub');
    }

    return true;
}