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
 * View page for eportfolio Hub
 *
 * @package local_eportfolio
 * @copyright   2025 weQon UG <support@weqon.net>
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
#require_once('locallib.php');
#require_once(__DIR__ . '/../lib.php');

$id = required_param('id', PARAM_INT);

// Get config for pre-checks.
$config = get_config('eportfolioplugins_hub');

// Pre-check, before rendering the overview page.
if (!$config->enablehub) {
    $redirecturl = new moodle_url('/local/eportfolio/index.php');
    redirect($redirecturl,
            get_string('hub:error:notenabled', 'eportfolioplugins_hub'),
            null, \core\output\notification::NOTIFY_ERROR);
} else if ($config->access === 'internal') {
    require_login();
}

$url = new moodle_url('/local/eportfolio/hub/view.php', ['id' => $id]);
$context = context_system::instance();

// Set page layout.
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('hub:view:header', 'eportfolioplugins_hub'));
$PAGE->set_pagelayout('base');

// ToDo: Implement new DB query once Workflow is finalised.
$eport = $DB->get_record('local_eportfolio', ['id' => $id], '*', MUST_EXIST);

// Get the ePortfolio entry and file storage.
$fs = get_file_storage();

// Get the file.
$file = $fs->get_file_by_id($eport->fileid);

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

$backurl = new moodle_url('/local/eportfolio/hub/index.php');
$backurlstring = get_string('hub:view:button:backtohub', 'eportfolioplugins_hub');

$user = $DB->get_record('user', ['id' => $eport->usermodified]);
$userfullname = fullname($user);

// Prepare data for template files.
$eportfolio = new stdClass();

$eportfolio->title = $eport->title;
#$eportfolio->description = (!empty($eport->description)) ? $eport->description : '';

$eportfolio->description =
        'Jemand musste Josef K. verleumdet haben, denn ohne dass er etwas Böses getan hätte, wurde er eines Morgens verhaftet. »Wie ein Hund!« sagte er, es war, als sollte die Scham ihn überleben.';

$eportfolio->backurl = $backurl;
$eportfolio->backurlstring = $backurlstring;
$eportfolio->userfullname = $userfullname;

$userdatemodified = date('d.m.Y', $eport->timemodified);
$date = new \DateTime($userdatemodified); // For today/now, don't pass an arg.
$date->modify("-1 day");
$eportfolio->timecreated = $date->format("d.m.Y");
$eportfolio->timemodified = $date->format("d.m.Y");

#$eportfolio->timecreated = date('d.m.Y', $eport->timecreated);
#$eportfolio->timemodified = date('d.m.Y', $eport->timemodified);

$eportfolio->h5pplayer = \core_h5p\player::display($fileurl, $config, false, 'local_eportfolio', false);;

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('eportfolioplugins_hub/hub_view_h5p_player', $eportfolio);
echo $OUTPUT->footer();
