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
 * Locallib for eportfolioplugins_hub.
 *
 * @package eportfolioplugins_hub
 * @copyright   2026 weQon UG <support@weqon.net>
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Check if user can perform simple approval process.
 *
 * @param int $userid
 * @return bool
 */
function eportfolioplugins_hub_is_simple_approver($userid) {

    // Get all courses for the user with the specified capability.
    $courses = get_user_capability_course('eportfolioplugins/hub:approvesimple', $userid, false);

    return !empty($courses);
}

/**
 * Get users who can perform advance approval process.
 *
 * @param int $step
 * @return false|mixed|stdClass
 */
function eportfolioplugins_hub_is_advanced_approver($step) {
    global $DB;

    $config = get_config('eportfolioplugins_hub');

    if ($step === 1) {
        $roles = explode(',', $config->advancedapprovalrole);
    } else {
        return false;
    }

    $canapprove = [];

    foreach ($roles as $role) {
        // Get assigned user ids.
        $roleassign = $DB->get_records('role_assignments', ['roleid' => $role]);

        foreach ($roleassign as $ra) {
            $user = $DB->get_record('user', ['id' => $ra->userid]);

            // Check, if user has the right capability.
            if (has_capability('eportfolioplugins/hub:approveadvanced', context_system::instance(), $user)) {
                if (!in_array($ra->userid, $canapprove)) {
                    $canapprove[] = $ra->userid;
                }
            }
        }
    }

    return $canapprove;
}

/**
 * Get all users with the configured simple approval role from the courses
 * the given user is actively enrolled in.
 *
 * Roles are read from the plugin setting 'simpleapprovalrole'.
 * Only active enrolments of the given user are considered.
 * Duplicate users across multiple courses are included only once.
 *
 * @param int $userid The user whose course enrolments are searched .
 * @return array Associative array of potential approvers: [userid => fullname]
 */
function eportfolioplugins_hub_get_approvers_by_enrolment(int $userid): array {
    global $DB;
    $config = get_config('eportfolioplugins_hub');

    if (empty($config->simpleapprovalrole)) {
        return [];
    }

    // Config stores role IDs as a comma-separated string.
    $roleids = explode(',', $config->simpleapprovalrole);

    if (empty($roleids)) {
        return [];
    }

    // Get all courses the given user is actively enrolled in.
    $enrolledcourses = enrol_get_users_courses($userid, true);

    if (empty($enrolledcourses)) {
        return [];
    }

    $approvers = [];

    foreach ($enrolledcourses as $course) {

        $coursecontext = context_course::instance($course->id);

        // First check, if "intvalue" -> "is ePortfolio course" is set to 1.
        // Get the field id to identify the custm field data.
        $customfield = $DB->get_record('customfield_field', ['shortname' => 'eportfolio_course']);

        if (!empty($customfield)) {

            // Get the value for custom field id.
            $customfielddata = $DB->get_record('customfield_data', ['fieldid' => $customfield->id, 'instanceid' => $course->id]);

            if ($customfielddata->intvalue) {
                foreach ($roleids as $roleid) {
                    // Fetch only the fields needed for fullname() to avoid loading unnecessary data.
                    $roleusers = get_role_users(
                            $roleid,
                            $coursecontext,
                            false,
                            'u.id, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.middlename, u.alternatename'
                    );

                    foreach ($roleusers as $user) {
                        // Use userid as key so duplicates across courses are merged automatically.
                        if (has_capability('eportfolioplugins/hub:approvesimple', $coursecontext, $user->id)) {
                            if (!isset($approvers[$user->id])) {
                                $approvers[$user->id] = fullname($user);
                            }
                        }
                    }
                }
            }
        }
    }

    return $approvers;
}

/**
 *  Get DB entries published eportfolios.
 *
 * @param bool $canseeall
 * @param int $userid
 * @param string $tsort
 * @param int $tdir
 * @return false|mixed|stdClass
 */
function eportfolioplugins_hub_get_published_eportfolios($canseeall, $tsort = null, $tdir = null, $page = null,
        $perpage = null) {
    global $DB, $USER;

    $sql = "SELECT * FROM {eportfolioplugins_hub}";

    $params = [];
    $whereclauses = [];

    // Check if current user can see all or only specific entries.
    if ($canseeall === 'approveadvanced') {
        $params['deleted'] = 0;
        $whereclauses[] = 'deleted = :deleted';
        $params['step'] = 1;
        $whereclauses[] = 'step = :step';
    } else if ($canseeall === 'approvesimple') {
        $params['approver'] = (int) $USER->id;
        $whereclauses[] = 'approver = :approver';
        $params['deleted'] = 0;
        $whereclauses[] = 'deleted = :deleted';
        $params['step'] = 0;
        $whereclauses[] = 'step = :step';
    } else if ($canseeall === 'user') {
        // User only.
        $params['publishedby'] = (int) $USER->id;
        $whereclauses[] = 'publishedby = :publishedby';
    }

    if (!empty($whereclauses)) {
        $sql .= " WHERE ";
        $sql .= "(" . implode(" AND ", $whereclauses) . ")";
    }

    // If tsort and tdir are set.
    $sortorder = '';

    if ($tsort) {
        $orderby = eportfolioplugins_hub_get_sort_order($tdir);

        if ($tsort === 'id') {
            $orderbyfield = 'id';
        } else if ($tsort === 'deleted') {
            $orderbyfield = 'deleted';
        } else if ($tsort === 'step') {
            $orderbyfield = 'step';
        } else if ($tsort === 'publisheddate') {
            $orderbyfield = 'timecreated';
        }

        $sortorder = " ORDER BY " . $orderbyfield . " " . $orderby;
    }

    if (!empty($sortorder)) {
        $sql .= $sortorder;
    }

    return $DB->get_records_sql($sql, $params);
}

/**
 *  Output content based on set sort order.
 *
 * @param int $tdir
 * @return int|void
 */
function eportfolioplugins_hub_get_sort_order($tdir) {
    switch ($tdir) {
        case '3':
            return 'DESC';
            break;
        case '4':
            return 'ASC';
            break;
        default:
            return 'ASC';
    }
}

/**
 * Generate HTML for state label.
 *
 * @param int $state
 * @return mixed
 */
function eportfolioplugins_hub_get_state_label($state) {

    $color = '';

    switch ($state) {
        case '1':
            $color = 'secondary';
            break;
        case '2':
        case '4':
            $color = 'success';
            break;
        case '0':
        case '3':
            $color = 'warning';
            break;
        case '5':
            $color = 'danger';
            break;
    }

    $str = get_string('approval:overview:table:label:step:' . $state, 'eportfolioplugins_hub');

    return html_writer::tag('span', $str, ['class' => 'badge badge-pill badge-' . $color]);
}

/**
 * Generate HTML for access label.
 *
 * @param int $accesstype
 * @return mixed
 */
function eportfolioplugins_hub_get_access_label($accesstype) {

    $color = '';

    switch ($accesstype) {
        case '1':
            $color = 'success';
            break;
        case '2':
            $color = 'warning';
            break;
    }

    $str = get_string('approval:overview:table:label:access:' . $accesstype, 'eportfolioplugins_hub');

    return html_writer::tag('span', $str, ['class' => 'badge badge-pill badge-' . $color]);
}

/**
 * Generate HTML for access label.
 *
 * @param int $accesstype
 * @return mixed
 */
function eportfolioplugins_hub_get_deleted_label($deleted) {

    $color = '';

    switch ($deleted) {
        case '0':
            $color = 'success';
            break;
        case '1':
            $color = 'danger';
            break;
    }

    $str = get_string('approval:overview:table:label:deleted:' . $deleted, 'eportfolioplugins_hub');

    return html_writer::tag('span', $str, ['class' => 'badge badge-pill badge-' . $color]);
}

/**
 * Generate publish button.
 *
 * @param int $eportid
 * @return mixed
 */
function eportfolioplugins_hub_action_button_publish($eportid) {
    global $DB, $OUTPUT;

    // Check, if ePortfolio was already published.
    $record = $DB->get_record('eportfolioplugins_hub', ['eportid' => $eportid]);
    if (empty($record)) {
        // Create publish URL.
        $icon = $OUTPUT->pix_icon('i/publish', get_string('overview:table:actions:publish', 'eportfolioplugins_hub'));
        $url = new \moodle_url('/local/eportfolio/hub/publish.php', ['id' => $eportid]);
        $fontcolor = '';
    } else {
        // Create view URL.
        $icon = $OUTPUT->pix_icon('i/siteevent', get_string('overview:table:actions:viewhub', 'eportfolioplugins_hub'));
        $url = new \moodle_url('/local/eportfolio/hub/approval/view.php', ['id' => $record->id]);
        $fontcolor = ' text-success';
    }

    return \html_writer::link($url, $icon, ['class' => 'ml-2' . $fontcolor]);
}

/**
 * Generate delete button.
 *
 * @param string $url
 * @return mixed
 */
function eportfolioplugins_hub_action_button_delete($url, $title) {
    global $OUTPUT;

    $data = new stdClass();
    $data->deleteurl = $url->out(false);
    $data->title = $title;
    return $OUTPUT->render_from_template('eportfolioplugins_hub/button_delete', $data);

}

/**
 * Generate delete button.
 *
 * @param string $url
 * @return mixed
 */
function eportfolioplugins_hub_action_button_restore($url) {
    global $OUTPUT;

    $icon = $OUTPUT->pix_icon('e/redo', get_string('approval:overview:table:actions:restore', 'eportfolioplugins_hub'));
    return \html_writer::link($url, $icon, ['class' => 'mr-2']);

}

/**
 * Generate view button.
 *
 * @param string $url
 * @return mixed
 */
function eportfolioplugins_hub_action_button_view($url) {
    global $OUTPUT;

    $icon = $OUTPUT->pix_icon('i/search', get_string('approval:overview:table:actions:view', 'eportfolioplugins_hub'));
    return \html_writer::link($url, $icon, ['class' => 'mr-2']);

}

/**
 * Generate review button.
 *
 * @param string $url
 * @return mixed
 */
function eportfolioplugins_hub_action_button_review($url) {
    global $OUTPUT;

    $icon = $OUTPUT->pix_icon('i/log', get_string('approval:overview:table:actions:review', 'eportfolioplugins_hub'));
    return \html_writer::link($url, $icon, ['class' => 'mr-2']);
}
