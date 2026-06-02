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

require_once('../../../config.php');
require_once('classes/local/overview.php');

$search = optional_param('search', '', PARAM_ALPHA);
$tsort = optional_param('tsort', '', PARAM_ALPHA);
$page = optional_param('page', 0, PARAM_INT);

$urlparams = [];

if ($search) {
    $urlparams['search'] = $search;
}
if ($tsort) {
    $urlparams['tsort'] = $tsort;
}
if ($page) {
    $urlparams['page'] = $page;
}

$url = new moodle_url('/local/eportfolio/hub/index.php', $urlparams);

$context = context_system::instance();

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

// Set page layout.
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('hub:overview:header', 'eportfolioplugins_hub'));
$PAGE->set_heading(get_string('hub:overview:header', 'eportfolioplugins_hub'));
$PAGE->set_pagelayout('base');

// Print the header.
echo $OUTPUT->header();

$data = new stdClass();

$renderer = new eportfolioplugins_hub\local\overview\overview($url, $search, $tsort, $page);
$renderer->display();

echo $OUTPUT->footer();
