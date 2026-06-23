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
 * Publish ePortfolio file.
 *
 * @package eportfolioplugins_hub
 * @copyright   2026 weQon UG <support@weqon.net>
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");
require_once('../locallib.php');

/**
 * Display the ePortfolio publishing form.
 *
 * @package eportfolioplugins_hub
 * @copyright   2026 weQon UG <support@weqon.net>
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class publish_form extends \moodleform {

    /**
     * Build the form.
     *
     * @return void
     */
    protected function definition() {

        $mform = $this->_form;

        $customdata = $this->_customdata;

        $mform->addElement('hidden', 'eportid', $customdata['eportid']);
        $this->_form->setType('eportid', PARAM_INT);

        $accesstype = $customdata['accesstype'];
        $userid = $customdata['userid'];

        $selectvalues = [
                '1' => get_string('form:publish:internal', 'eportfolioplugins_hub'),
        ];

        if ($accesstype == 2) {
            $selectvalues['2'] = get_string('form:publish:external', 'eportfolioplugins_hub');
        }

        $mform->addElement('select', 'accesstype',
                get_string('form:publish:accesstype', 'eportfolioplugins_hub'), $selectvalues);
        $mform->setType('accesstype', PARAM_TEXT);
        $mform->addHelpButton('accesstype', 'form:publish:accesstype', 'eportfolioplugins_hub');
        $mform->addRule('accesstype', get_string('form:field:required', 'eportfolioplugins_hub'), 'required', '', 'client');

        $mform->addElement('text', 'title', get_string('form:publish:title', 'eportfolioplugins_hub'), ['size' => '80']);
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('form:field:required', 'eportfolioplugins_hub'), 'required', '', 'client');

        $mform->addElement('textarea', 'description', get_string('form:publish:description', 'eportfolioplugins_hub'));
        $mform->setType('description', PARAM_TEXT);
        $mform->addRule('description', get_string('form:field:required', 'eportfolioplugins_hub'), 'required', '', 'client');

        // Add selection for teacher who can perform approval process.
        $approvalusers = eportfolioplugins_hub_get_approvers_by_enrolment($userid);

        if (empty($approvalusers)) {

            $options = [
                    'multiple' => false,
                    'noselectionstring' => get_string('form:publish:select:approvaluser:empty', 'eportfolioplugins_hub'),
                    'placeholder' => get_string('form:publish:select:approval', 'eportfolioplugins_hub'),
            ];
            $mform->addElement('autocomplete', 'approvaluser', get_string('form:publish:select:approvaluser',
                    'eportfolioplugins_hub'), $approvalusers, $options);
            $mform->addHelpButton('approvaluser', 'form:publish:select:approvaluser', 'eportfolioplugins_hub');
            $mform->addRule('approvaluser', get_string('form:publish:select:approvaluser:hint', 'eportfolioplugins_hub'), 'required', null,
                    'client');
        } else {
            redirect(new moodle_url('/local/eportfolio/index.php'),
                    get_string('form:publish:select:approvaluser:notfound', 'eportfolioplugins_hub'),
                    null, \core\output\notification::NOTIFY_ERROR);
        }

        $mform->addElement('html', '<div class="divider my-5"></div>');

        $mform->addElement('checkbox', 'confirm', get_string('form:publish:confirm', 'eportfolioplugins_hub'),
                get_string('form:publish:confirm:label', 'eportfolioplugins_hub'));
        $mform->addRule('confirm', get_string('form:checkbox:required', 'eportfolioplugins_hub'), 'required', '', 'client');

        $buttonarray = [];

        $buttonarray[] = $mform->createElement('submit', 'save', get_string('form:publish:save', 'eportfolioplugins_hub'));
        $buttonarray[] = $mform->createElement('cancel');

        $mform->addGroup($buttonarray, 'buttonar', '', [' '], false);
    }
}
