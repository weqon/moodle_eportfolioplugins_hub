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
 * Publishing page for eportfolioplugins_hub
 *
 * @package eportfolioplugins_hub
 * @copyright   2026 weQon UG <support@weqon.net>
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
require_once('locallib.php');
require_once('classes/forms/publish_form.php');

// First check, if user is logged in before accessing this page.
require_login();

$context = context_system::instance();

// Check if user has the right capability.
if (!has_capability('eportfolioplugins/hub:publish', $context)) {
    redirect(new moodle_url('/local/eportfolio/index.php'),
            get_string('error:missingcapability', 'local_eportfolio'),
            null, \core\output\notification::NOTIFY_ERROR);
}

// ID for ePortfolio to publish.
$id = required_param('id', PARAM_INT);

// Build the URL.
$params = [
        'id' => $id,
];

$url = new moodle_url('/local/eportfolio/hub/publish.php', $params);

// Check, if record exists. If not, we assume user is initially publishing this content.
$record = $DB->get_record('eportfolioplugins_hub', ['eportid' => $id, 'publishedby' => $USER->id]);

// Get the eportfolio record.
$eportrecord = $DB->get_record('local_eportfolio', ['id' => $id, 'usermodified' => $USER->id], '*', MUST_EXIST);

$config = get_config('eportfolioplugins_hub');

$accesstype = 1; // Set default to internal.

if ($config->access === 'external') {
    $accesstype = 2;
}

$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('publish:header', 'eportfolioplugins_hub'));
$PAGE->set_heading(get_string('publish:header', 'eportfolioplugins_hub'));
$PAGE->set_pagelayout('base');
$PAGE->add_body_class('limitedwith');

$customdata = [
        'eportid' => $id,
        'accesstype' => $accesstype,
        'userid' => $USER->id,
];

$mform = new publish_form($url, $customdata);

$prefill = new stdClass();

$prefill->title = (!empty($record->title)) ? $record->title : $eportrecord->title;

if (!empty($record->description)) {
    $prefill->description = $record->description;
} else if (!empty($eportrecord->description)) {
    $prefill->description = $eportrecord->description;
} else {
    $prefill->description = '';
}

$mform->set_data($prefill);

// Step 0 = User initially publishing content.
// Step 1 = Trainer reviewed content.
// Step 2 = Named users reviewed content and published it.

if (empty($record)) {
    if ($formdata = $mform->is_cancelled()) {

        $redirecturl = new moodle_url('/local/eportfolio/index.php');
        redirect($redirecturl, get_string('form:publish:cancelled', 'eportfolioplugins_hub'), null,
                \core\output\notification::NOTIFY_WARNING);
    } else if ($formdata = $mform->get_data()) {

        $data = new stdClass();

        $data->eportid = $eportrecord->id;
        $data->active = 0; // Will be set to 1 once approval is done.
        $data->deleted = 0;
        $data->confirmed = $formdata->confirm; // User accepted terms and conditions.
        $data->accesstype = $formdata->accesstype;
        $data->step = 0; //  User initially publishing content.
        $data->title = $formdata->title;
        $data->description = $formdata->description;

        // Do not confuse with table field "approvedby". The approval can also be performed by Admin/Manager.
        $data->approver = $formdata->approvaluser;

        $data->notes = (!empty($formdata->notes)) ? $formdata->notes : '';
        $data->timecreated = time();
        $data->timemodified = 0;
        $data->publishedby = $USER->id;
        $data->usermodified = $USER->id; // Always the user id who last modified the DB entry.

        $context = context_system::instance();

        // Get the file we want to create a copy of.
        $fs = get_file_storage();
        $file = $fs->get_file_by_id($eportrecord->fileid);

        // Create a new itemid to avoid conflicts.
        $itemid = file_get_unused_draft_itemid();

        $newfile = new stdClass();
        $newfile->contextid = $context->id; // System context.
        $newfile->userid = $USER->id;
        $newfile->component = 'eportfolioplugins_hub';
        $newfile->filearea = 'eportfolio';
        $newfile->itemid = $itemid;

        $filecopy = $fs->create_file_from_storedfile($newfile, $file);

        $data->fileid = $filecopy->get_id();

        // Also we have to add a new entry in the h5p table.
        // First get h5p file by "old" pathnamehash.
        $pathnamehash = $file->get_pathnamehash();

        $newh5pfile = $DB->get_record('h5p', ['pathnamehash' => $pathnamehash]);

        // Override contenthash & pathnamehash to new file.
        $newh5pfile->pathnamehash = $filecopy->get_pathnamehash();
        $newh5pfile->contenthash = $filecopy->get_contenthash();

        // We need this for the next step.
        $oldh5pfileid = $newh5pfile->id;

        unset($newh5pfile->id);

        $newh5pfileid = $DB->insert_record('h5p', $newh5pfile);

        $data->h5pid = $newh5pfileid;

        // We need to create a copy of the H5P content as well in case the file contains additional content like images.
        $h5pcontentfiles =
                $DB->get_records('files', ['itemid' => $oldh5pfileid, 'component' => 'core_h5p', 'filearea' => 'content']);

        foreach ($h5pcontentfiles as $h5pcontent) {
            if ($h5pcontent->filename != '.') {
                // Get the file we want to create a copy of.
                $fs = get_file_storage();
                $file = $fs->get_file_by_id($h5pcontent->id);

                $contentitemid = $newh5pfileid;

                $newcontentfile = new stdClass();
                $newcontentfile->contextid = '1';
                $newcontentfile->userid = $USER->id;
                $newcontentfile->itemid = $contentitemid;
                $newcontentfile->component = 'core_h5p';
                $newcontentfile->filearea = 'content';

                $filecontentcopy = $fs->create_file_from_storedfile($newcontentfile, $file);

            }
        }

        if ($insertid = $DB->insert_record('eportfolioplugins_hub', $data)) {

            // Generate and queue adhoc task for sending messages.
            // Prepare task data für publishing user.
            $taskuser = new \eportfolioplugins_hub\task\send_messages();

            $taskdata = new stdClass();

            $taskdata->userfrom = $data->usermodified;
            $taskdata->userto = $USER->id;
            $taskdata->type = 'user';
            $taskdata->title = $formdata->title;
            $taskdata->id = $insertid;
            $taskdata->status = $data->step;

            $taskuser->set_custom_data($taskdata);

            // Queue the task.
            \core\task\manager::queue_adhoc_task($taskuser);

            unset($taskdata);

            // Send message to user who can approve.
            $taskreviewer = new \eportfolioplugins_hub\task\send_messages();

            $taskdata = new stdClass();

            $taskdata->userfrom = $data->usermodified;
            $taskdata->userto = $formdata->approvaluser;
            $taskdata->type = 'reviewer';
            $taskdata->title = $formdata->title;
            $taskdata->id = $insertid;
            $taskdata->status = $data->step;

            $taskuser->set_custom_data($taskdata);

            // Queue the task.
            \core\task\manager::queue_adhoc_task($taskuser);

            /*
            // Trigger event for sharing ePortfolio.
            \local_eportfolio\event\eportfolio_shared::create([
                    'objectid' => $eport->fileid,
                    'other' => [
                            'description' => get_string('event:eportfolio:shared:' . $data->shareoption, 'local_eportfolio',
                                    ['userid' => $USER->id, 'filename' => $filename, 'fileid' => $eport->fileid]),
                    ],
            ])->trigger();
            */

            $str = new stdClass();
            $str->title = $formdata->title;

            redirect(new moodle_url('/local/eportfolio/hub/approval/overview.php'),
                    get_string('form:publish:success', 'eportfolioplugins_hub', $str),
                    null, \core\output\notification::NOTIFY_SUCCESS);

        } else {

            redirect(new moodle_url('/local/eportfolio/index.php'),
                    get_string('form:publish:error', 'eportfolioplugins_hub'),
                    null, \core\output\notification::NOTIFY_ERROR);
        }

    } else {
        $renderform = $mform->render();
    }
} else {
    // Selected ePortfolio was already published.
    $str = new stdClass();
    $str->title = $record->title;
    redirect(new moodle_url('/local/eportfolio/hub/approval/overview.php'),
            get_string('publish:alreadyshared', 'eportfolioplugins_hub', $str),
            null, \core\output\notification::NOTIFY_INFO);
}

echo $OUTPUT->header();

$data = new stdClass();

$a = new stdClass(); // String placeholder.

$data->renderform = $renderform;

$data->accesstype = get_string('publish:accesstype:internal', 'eportfolioplugins_hub'); // Default set to internal.

if ($accesstype == 2) {
    $data->accesstype = get_string('publish:accesstype:external', 'eportfolioplugins_hub');
}

$statusstr = get_string('publish:status:0', 'eportfolioplugins_hub'); // Set default to 0 = new.

if (!empty($record)) {
    $statusstr = get_string('publish:status:' . $record->step, 'eportfolioplugins_hub');
}

$data->status = $statusstr;
$data->title = $eportrecord->title;

$user = $DB->get_record('user', ['id' => $eportrecord->usermodified], '*', MUST_EXIST);
$data->usermodified = fullname($user);
$data->timecreated = date('d.m.Y H:i', $eportrecord->timecreated);
$data->timemodified = (!empty($eportrecord->timemodified)) ? date('d.m.Y H:i', $eportrecord->timemodified) : './';

$data->strinfobox = get_string('publish:sidetext', 'eportfolioplugins_hub', $a);

echo $OUTPUT->render_from_template('eportfolioplugins_hub/publish', $data);

echo $OUTPUT->footer();
