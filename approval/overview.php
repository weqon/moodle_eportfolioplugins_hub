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
 * Internal overview page for published ePortfolios
 *
 * @package eportfolioplugins_hub
 * @copyright   2026 weQon UG <support@weqon.net>
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

$tsort = optional_param('tsort', '', PARAM_ALPHA);
$tdir = optional_param('tdir', '', PARAM_INT);
$treset = optional_param('treset', 0, PARAM_INT);

// Pagination bar.
$perpage = optional_param('perpage', 25, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);

$params = [];

if (!$treset) {
    if (!empty($tsort)) {
        $params['tsort'] = $tsort;
    }

    if (!empty($tdir)) {
        $params['tdir'] = $tdir;
    }
}

if ($page) {
    $params['page'] = $page;
}

$url = new moodle_url('/local/eportfolio/hub/approval/overview.php', $params);
$context = context_system::instance();

// Who can see what?
// Admin & Manager = See all.
// Per Role = When have approver capabilities.
// User = Only own published ePortfolios.

$canseeall = 'user'; // Default.

if (is_siteadmin() || has_capability('eportfolioplugins/hub:viewall', context_system::instance())) {
    $canseeall = 'all';
} else if (has_capability('eportfolioplugins/hub:approveadvanced', context_system::instance())) {
    $canseeall = 'approveadvanced';
} else if (has_capability('eportfolioplugins/hub:approvesimple', context_system::instance())) {
    $canseeall = 'approvesimple';
}

// Get records.
$records = eportfolioplugins_hub_get_published_eportfolios($canseeall, $tsort, $tdir, $page, $perpage);

$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('approval:overview:header', 'eportfolioplugins_hub'));
$PAGE->set_heading(get_string('approval:overview:header', 'eportfolioplugins_hub'));
$PAGE->set_pagelayout('base');

echo $OUTPUT->header();

if (empty($records)) {
    // No files found for selected section.
    $nofilesdata = new stdClass();
    $nofilesdata->infostring = get_string('approval:overview:nofiles', 'eportfolioplugins_hub');
    echo $OUTPUT->render_from_template('eportfolioplugins_hub/publish_nofiles', $nofilesdata);

} else {
    // Create overview table.
    $table = new \flexible_table('eportfolioplugins_hub:overview');

    $columns = [
            'id',
            'deleted',
            'step',
            'accesstype',
            'title',
            'publishedby',
            'approveddate',
            'actions',
    ];

    $table->define_columns($columns);

    $headers = [
            get_string('approval:overview:table:id', 'eportfolioplugins_hub'),
            get_string('approval:overview:table:deleted', 'eportfolioplugins_hub'),
            get_string('approval:overview:table:step', 'eportfolioplugins_hub'),
            get_string('approval:overview:table:accesstype', 'eportfolioplugins_hub'),
            get_string('approval:overview:table:title', 'eportfolioplugins_hub'),
            get_string('approval:overview:table:publishedby', 'eportfolioplugins_hub'),
            get_string('approval:overview:table:approveddate', 'eportfolioplugins_hub'),
            get_string('approval:overview:table:actions', 'eportfolioplugins_hub'),
    ];

    $table->define_headers($headers);

    $table->define_baseurl($url);
    $table->set_attribute('class', 'table table-hover');
    $table->sortable(true, 'id', SORT_ASC);
    $table->initialbars(true);
    $table->no_sorting('actions');
    $table->no_sorting('accesstype');
    $table->no_sorting('title');
    $table->no_sorting('description');
    $table->no_sorting('publishedby');
    $table->setup();

    foreach ($records as $rec) {

        $actions = '';

        // View url - same for all.
        $viewurl = new \moodle_url('/local/eportfolio/hub/approval/view.php', ['id' => $rec->id]);
        $actions .= eportfolioplugins_hub_action_button_view($viewurl);

        // User can't review and approve own ePortfolio.
        if (has_capability('eportfolioplugins/hub:approveadvanced', context_system::instance()) ||
                has_capability('eportfolioplugins/hub:approveadvanced', context_system::instance())) {
            $reviewurl = new \moodle_url('/local/eportfolio/hub/approval/review.php', ['id' => $rec->id]);
            $actions .= eportfolioplugins_hub_action_button_review($reviewurl);
        }

        // Delete URL.
        $deleteurl =
                new \moodle_url('/local/eportfolio/hub/approval/delete.php', ['id' => $rec->id, 'sesskey' => sesskey(),
                        'action' => 'delete']);

        if (has_capability('eportfolioplugins/hub:approveadvanced', context_system::instance()) ||
                has_capability('eportfolioplugins/hub:approveadvanced', context_system::instance())) {
            // Can delete any eportfolio.
            $actions .= eportfolioplugins_hub_action_button_delete($deleteurl, $rec->title);
        } else if ($rec->publishedby === $USER->id && !$rec->deleted) {
            // User can only delete ePortfolios not already flagged as soft deleted.
            $actions .= eportfolioplugins_hub_action_button_delete($deleteurl, $rec->title);
        }
        $deletedlabel = eportfolioplugins_hub_get_deleted_label($rec->deleted);

        $currentstep = (int) $rec->step;
        $steplabel = eportfolioplugins_hub_get_state_label($currentstep);

        $accesstypelabel = eportfolioplugins_hub_get_access_label($rec->accesstype);

        $getpublishinguser = $DB->get_record('user', ['id' => $rec->publishedby]);
        $publishinguser = fullname($getpublishinguser);

        $publishingdate = date('d.m.Y', $rec->timecreated);
        $publishingdata = $publishinguser . '<br>' . $publishingdate;

        $approveddata = './';

        if (!empty($rec->approveddate) && !empty($rec->approvedby)) {
            $approveddate = date('d.m.Y', $rec->approveddate);

            $approvedbyuser = $DB->get_record('user', ['id' => $rec->approvedby]);
            $approvedby = fullname($approvedbyuser);

            $approveddata = $approvedby . '<br>' . $approveddate;
        }


        $tabledata = [
                $rec->id,
                $deletedlabel,
                $steplabel,
                $accesstypelabel,
                $rec->title,
                $publishingdata,
                $approveddata,
                $actions,
        ];

        $table->add_data($tabledata);
    }

    $table->finish_html();
}

echo $OUTPUT->footer();
