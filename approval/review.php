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
 * Overview page for ePortfolio Hub
 *
 * @package eportfolioplugins_hub
 * @copyright   2025 weQon UG <support@weqon.net>
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../../config.php');
require_once('../locallib.php');
require_once('../classes/forms/review_form.php');

// First check, if user is logged in before accessing this page.
require_login();

if (!has_capability('eportfolioplugins/hub:approveadvanced', context_system::instance()) &&
        !eportfolioplugins_hub_is_simple_approver($USER->id)) {
    redirect(new moodle_url('/local/eportfolio/hub/approval/overview.php'),
            get_string('error:missingcapability', 'local_eportfolio'),
            null, \core\output\notification::NOTIFY_ERROR);
}

// ID for ePortfolio to review.
$id = required_param('id', PARAM_INT);

// Build the URL.
$params = [
        'id' => $id,
];

$url = new moodle_url('/local/eportfolio/hub/approval/review.php', $params);

$context = context_system::instance();

$config = get_config('eportfolioplugins_hub');

$accesstype = 1; // Set default to internal.

if ($config->access === 'external') {
    $accesstype = 2;
}

// Check, if record exists. If not, we assume user is initially publishing this content.
$record = $DB->get_record('eportfolioplugins_hub', ['id' => $id], '*', MUST_EXIST);

// Get user who published the eportfolio.
$user = $DB->get_record('user', ['id' => $record->publishedby], '*', MUST_EXIST);

// Set page layout.
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('review:header', 'eportfolioplugins_hub'));
$PAGE->set_heading(get_string('review:header', 'eportfolioplugins_hub'));
$PAGE->set_pagelayout('base');

$approvalprocess = 1; // Default set to simple.

if ($config->approve === 'advanced') {
    $approvalprocess = 2;
}

$customdata = [
        'eportid' => $id,
        'accesstype' => $accesstype,
        'approvalprocess' => $approvalprocess,
        'step' => $record->step,
];

$mform = new review_form($url, $customdata);
$mform->set_data($record);

if ($formdata = $mform->is_cancelled()) {

    $redirecturl = new moodle_url('/local/eportfolio/hub/approval/overview.php');
    redirect($redirecturl, get_string('form:publish:cancelled', 'eportfolioplugins_hub'), null,
            \core\output\notification::NOTIFY_WARNING);

} else if ($formdata = $mform->get_data()) {

    $data = new stdClass();

    $data->id = $record->id;
    $data->accesstype = $formdata->accesstype;
    $data->title = $formdata->title;
    $data->description = $formdata->description;
    $data->notes = (!empty($formdata->notes)) ? $formdata->notes : '';
    $data->usernotes = (!empty($formdata->usernotes)) ? $formdata->usernotes : '';

    $selectedstep = (int) $formdata->approvalselect;
    $stepmessageuser = '';
    $stepmessagereviewer = '';

    if ($selectedstep === 1) {
        // The ePortfolio was rejected.
        $data->active = 0;
        $data->step = 5;
        $stepmessageuser = 'userfeedback';
    } else if ($selectedstep === 2) {
        // Publish ePortfolio to hub.
        $data->active = 1;
        $data->step = 2;
        $data->approveddate = time();
        $stepmessageuser = 'userpublished';
    } else if ($selectedstep === 3) {
        // Advanced approval process.
        $data->active = 0;
        $data->step = 1;
        $data->approveddate = '';
        $stepmessageuser = 'useradvanced';
        $stepmessagereviewer = 'revieweradvanced';
    } else if ($selectedstep === 4) {
        // Revoke published ePortfolio.
        $data->active = 0;
        $data->step = 0;
        $stepmessageuser = 'userrevoked';
    }

    $data->timemodified = time();
    $data->approvedby = $USER->id;
    $data->usermodified = $USER->id; // Always the user id who last modified the DB entry.

    if ($DB->update_record('eportfolioplugins_hub', $data)) {

        // Generate and queue adhoc task for sending messages.
        // Prepare task data für publishing user.
        $taskuser = new \eportfolioplugins_hub\task\send_messages();

        $taskdata = new stdClass();

        $taskdata->userfrom = $data->usermodified;
        $taskdata->userto = $record->publishedby;
        $taskdata->type = $stepmessageuser;
        $taskdata->title = $formdata->title;
        $taskdata->id = $record->id;
        $taskdata->status = $data->step;

        $taskuser->set_custom_data($taskdata);

        // Queue the task.
        \core\task\manager::queue_adhoc_task($taskuser);

        unset($taskdata);

        // Send message for advanced reviewer.
        if (!empty($stepmessagereviewer && $approvalprocess === 2)) {

            // First we need to get selected user for advanced feedback!
            $advancedapprover = eportfolioplugins_hub_is_advanced_approver($data->step);

            if (!empty($advancedapprover)) {
                foreach ($advancedapprover as $aa) {
                    $taskreviewer = new \eportfolioplugins_hub\task\send_messages();

                    $taskdata = new stdClass();

                    $taskdata->userfrom = 2; // Default admin user.
                    $taskdata->userto = $aa;
                    $taskdata->type = $stepmessagereviewer;
                    $taskdata->title = $formdata->title;
                    $taskdata->id = $record->id;
                    $taskdata->status = $data->step;

                    $taskuser->set_custom_data($taskdata);

                    // Queue the task.
                    \core\task\manager::queue_adhoc_task($taskuser);

                    unset($taskdata);
                }
            }
        }

        $str = new stdClass();
        $str->title = $formdata->title;
        $str->publishedby = fullname($user);

        if ($selectedstep === 1) {
            // User needs to review.
            redirect(new moodle_url('/local/eportfolio/hub/approval/overview.php'),
                    get_string('form:review:success:feedback', 'eportfolioplugins_hub', $str),
                    null, \core\output\notification::NOTIFY_SUCCESS);
        } else {
            redirect(new moodle_url('/local/eportfolio/hub/approval/overview.php'),
                    get_string('form:review:success', 'eportfolioplugins_hub', $str),
                    null, \core\output\notification::NOTIFY_SUCCESS);
        }

    } else {
        redirect(new moodle_url('/local/eportfolio/hub/approval/overview.php'),
                get_string('form:review:error', 'eportfolioplugins_hub'),
                null, \core\output\notification::NOTIFY_ERROR);
    }

} else {
    $renderform = $mform->render();
}

// H5P player for review process.
// Get the ePortfolio entry and file storage.
$fs = get_file_storage();

// Get the file.
$file = $fs->get_file_by_id($record->fileid);

// In case additional file types will be allowed we have to replace this.
// Convert display options to a valid object.
$factory = new \core_h5p\factory();
$core = $factory->get_core();
$configh5p = core_h5p\helper::decode_display_options($core, $context->id);

$fileurl = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(),
        $file->get_filearea(), $file->get_itemid(), $file->get_filepath(),
        $file->get_filename(), false);

// Get the times for created and modified based on h5p file.
$pathnamehash = $file->get_pathnamehash();
$h5pfile = $DB->get_record('h5p', ['pathnamehash' => $pathnamehash]);

// Print the page header.
echo $OUTPUT->header();

$data = new stdClass();

$data->renderform = $renderform;

$data->accesstype = get_string('review:accesstype:internal', 'eportfolioplugins_hub'); // Default set to internal.

if ($accesstype == 2) {
    $data->accesstype = get_string('review:accesstype:external', 'eportfolioplugins_hub');
}

$data->approvalprocess = get_string('review:approval:simple', 'eportfolioplugins_hub'); // Default set to simple.

if ($config->approve === 'advanced') {
    $data->approvalprocess = get_string('review:approval:advanced', 'eportfolioplugins_hub'); // Default set to simple.
}

$data->status = get_string('publish:status:' . $record->step, 'eportfolioplugins_hub');
$data->title = $record->title;

$data->usermodified = fullname($user);
$data->timecreated = date('d.m.Y', $record->timecreated);
$data->timemodified = (!empty($record->timemodified)) ? date('d.m.Y', $record->timemodified) : './';

$data->h5pplayer = \core_h5p\player::display($fileurl, $configh5p, false, 'eportfolioplugins_hub', false);

echo $OUTPUT->render_from_template('eportfolioplugins_hub/approval_review', $data);

echo $OUTPUT->footer();
