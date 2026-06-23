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
 * Delete published ePortfolios.
 *
 * @package eportfolioplugins_hub
 * @copyright   2026 weQon UG <support@weqon.net>
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../../config.php');
require_once('../locallib.php');

$id = required_param('id', PARAM_INT);  // Entry ID.
$action = required_param('action', PARAM_ALPHA);
$sesskey = required_param('sesskey', PARAM_ALPHANUM);

require_login();

// Check if user has the right capability.
if (!has_capability('eportfolioplugins/hub:publish', context_system::instance())) {
    redirect(new moodle_url('/local/eportfolio/index.php'),
            get_string('error:missingcapability', 'local_eportfolio'),
            null, \core\output\notification::NOTIFY_ERROR);
}

require_sesskey();

$params = [
        'id' => $id,
        'sesskey' => $sesskey,
        'action' => $action,
];

$url = new moodle_url('/local/eportfolio/hub/approval/delete.php', $params);

// Set page layout.
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('delete:header', 'eportfolioplugins_hub'));
$PAGE->set_heading(get_string('delete:header', 'eportfolioplugins_hub'));
$PAGE->set_pagelayout('base');
$PAGE->add_body_class('limitedwith');

$redirecturl = new moodle_url('/local/eportfolio/hub/approval/overview.php');

if ($action === 'delete') {

    // Get record for provided ID from DB.
    $eport = $DB->get_record('eportfolioplugins_hub', ['id' => $id]);

    if (!empty($eport)) {
        // If user has the right capability, we will delete the file directly.
        if (has_capability('eportfolioplugins/hub:approveadvanced', context_system::instance(), $USER->id)) {

            // Now delete the main file.
            $fs = get_file_storage();
            $file = $fs->get_file_by_id($eport->fileid);

            // We use the pathnamehash to get the H5P file.
            $pathnamehash = $file->get_pathnamehash();

            $h5pfile = $DB->get_record('h5p', ['pathnamehash' => $pathnamehash]);

            // If H5P, delete it from the H5P table as well.
            // Note: H5P will create an entry when the file was viewed for the first time.
            if ($h5pfile) {
                $DB->delete_records('h5p', ['id' => $h5pfile->id]);
                // Also delete from files where context = 1, itemid = h5p id component core_h5p, filearea content.
                $fs->delete_area_files('1', 'core_h5p', 'content', $h5pfile->id);
            }

            // Finally delete the selected file.
            $file->delete();

            if ($DB->delete_records('eportfolioplugins_hub', ['id' => $eport->id])) {

                redirect($redirecturl, get_string('delete:success', 'eportfolioplugins_hub'),
                        null, \core\output\notification::NOTIFY_SUCCESS);

            } else {
                redirect($redirecturl, get_string('delete:error', 'eportfolioplugins_hub'),
                        null, \core\output\notification::NOTIFY_ERROR);
            }
        } else if ($eport->publishedby === $USER->id) {
            // User can only soft delete their own ePortfolio.
            $eport->active = 0; // Disable access in hub.
            $eport->deleted = 1; // Flag as soft deleted.
            $eport->usermodified = $USER->id;
            $eport->timemodified = time();

            if ($DB->update_record('eportfolioplugins_hub', $eport)) {
                redirect($redirecturl, get_string('delete:success:user', 'eportfolioplugins_hub'),
                        null, \core\output\notification::NOTIFY_SUCCESS);
            } else {
                redirect($redirecturl, get_string('delete:error', 'eportfolioplugins_hub'),
                        null, \core\output\notification::NOTIFY_ERROR);
            }
        }
    } else {
        // No file found or user is not allowed to access the file.
        redirect($redirecturl,
                get_string('delete:filenotfound', 'eportfolioplugins_hub'), null, \core\output\notification::NOTIFY_ERROR);
    }
} else if ($action === 'restore') {

    // Get record for provided ID from DB.
    $eport = $DB->get_record('eportfolioplugins_hub', ['id' => $id]);

    if (!empty($eport)) {
        $eport->active = 1; // Enable access in hub.
        $eport->deleted = 0; // Remove flag as soft deleted.
        $eport->usermodified = $USER->id;
        $eport->timemodified = time();

        if ($DB->update_record('eportfolioplugins_hub', $eport)) {
            redirect($redirecturl, get_string('delete:restore:success', 'eportfolioplugins_hub'),
                    null, \core\output\notification::NOTIFY_SUCCESS);
        } else {
            redirect($redirecturl, get_string('delete:restore:error', 'eportfolioplugins_hub'),
                    null, \core\output\notification::NOTIFY_ERROR);
        }
    } else {
        // No file found or user is not allowed to access the file.
        redirect($redirecturl,
                get_string('delete:filenotfound', 'eportfolioplugins_hub'), null, \core\output\notification::NOTIFY_ERROR);
    }
}
