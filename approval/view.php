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

/**
 * View page for eportfolio Hub approval.
 *
 * @package local_eportfolio
 * @copyright   2025 weQon UG <support@weqon.net>
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../../config.php');
require_once('../locallib.php');

// First check, if user is logged in before accessing this page.
require_login();

// Check if user has the right capability.
if (!has_capability('eportfolioplugins/hub:publish', context_system::instance())) {
    redirect(new moodle_url('/local/eportfolio/index.php'),
            get_string('error:missingcapability', 'local_eportfolio'),
            null, \core\output\notification::NOTIFY_ERROR);
}

$id = required_param('id', PARAM_INT);

$url = new moodle_url('/local/eportfolio/hub/approval/view.php', ['id' => $id]);
$context = context_system::instance();

$superuser = false; // Used for e.g. internal notes.

if (is_siteadmin() || has_capability('eportfolioplugins/hub:viewall', context_system::instance()) ||
        has_capability('eportfolioplugins/hub:approveadvanced', context_system::instance()) ||
        has_capability('eportfolioplugins/hub:approvesimple', context_system::instance())) {
    $record = $DB->get_record('eportfolioplugins_hub', ['id' => $id]);
    $superuser = true;
} else {
    $record = $DB->get_record('eportfolioplugins_hub', ['id' => $id, 'publishedby' => $USER->id]);
}

if (empty($record)) {
    redirect(new moodle_url('/local/eportfolio/hub/approval/overview.php'),
            get_string('approval:view:filenotfound', 'eportfolioplugins_hub'),
            null, \core\output\notification::NOTIFY_ERROR);
}

$config = get_config('eportfolioplugins_hub');

$accesstype = 1; // Set default to internal.

if ($config->access === 'external') {
    $accesstype = 2;
}

// Set page layout.
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('approval:view:header', 'eportfolioplugins_hub'));
$PAGE->set_heading(get_string('approval:view:header', 'eportfolioplugins_hub') . ' - ' . $record->title);
$PAGE->set_pagelayout('base');

// Get the ePortfolio entry and file storage.
$fs = get_file_storage();

// Get the file.
$file = $fs->get_file_by_id($record->fileid);

// In case additional file types will be allowed we have to replace this.
// Convert display options to a valid object.
$factory = new \core_h5p\factory();
$core = $factory->get_core();
$config = core_h5p\helper::decode_display_options($core, $context->id);

$fileurl = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(),
        $file->get_filearea(), $file->get_itemid(), $file->get_filepath(),
        $file->get_filename(), false);

// Get the times for created and modified based on h5p file.
$pathnamehash = $file->get_pathnamehash();
$h5pfile = $DB->get_record('h5p', ['pathnamehash' => $pathnamehash]);

// Prepare data for template files.
$eportfolio = new stdClass();

$eportfolio->title = $record->title;
$eportfolio->description = s($record->description);
$eportfolio->usernotes = (!empty($record->usernotes)) ? s($record->usernotes) : './.';

$eportfolio->notesset = false;

if ($superuser) {
    $eportfolio->notesset = true;
    $eportfolio->notes = (!empty($record->notes)) ? s($record->notes) : './.';
}

$eportfolio->backurl = new moodle_url('/local/eportfolio/hub/approval/overview.php');
$eportfolio->backurlstring = get_string('approval:view:button:back', 'eportfolioplugins_hub');

$eportfolio->deleted = eportfolioplugins_hub_get_deleted_label($record->deleted);
$eportfolio->state = eportfolioplugins_hub_get_state_label($record->step);
$eportfolio->access = eportfolioplugins_hub_get_access_label($record->accesstype);

$user = $DB->get_record('user', ['id' => $record->publishedby]);
$userfullname = fullname($user);
$eportfolio->publishedby = $userfullname;

$approvaluser = $DB->get_record('user', ['id' => $record->approver]);
$approvaluserfullname = fullname($approvaluser);
$eportfolio->approver = $approvaluserfullname;

$eportfolio->approvedset = false;

if (!empty($record->approvedby)) {
    $eportfolio->approvedset = true;
    $approveduser = $DB->get_record('user', ['id' => $record->approvedby]);
    $approveduserfullname = fullname($approveduser);
    $eportfolio->approvedby = $approveduserfullname;

    $eportfolio->approveddate = date('d.m.Y', $record->approveddate);
}

$eportfolio->timecreated = date('d.m.Y', $record->timecreated);
$eportfolio->timemodified = date('d.m.Y', $record->timemodified);

$eportfolio->h5pplayer = \core_h5p\player::display($fileurl, $config, false, 'eportfolioplugins_hub', false);

$eportfolio->accesstype = get_string('publish:accesstype:internal', 'eportfolioplugins_hub'); // Default set to internal.

if ($accesstype == 2) {
    $eportfolio->accesstype = get_string('publish:accesstype:external', 'eportfolioplugins_hub');
}

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('eportfolioplugins_hub/approval_view_h5p_player', $eportfolio);
echo $OUTPUT->footer();
