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
 * Review form for published ePortfolio file.
 *
 * @package eportfolioplugins_hub
 * @copyright   2026 weQon UG <support@weqon.net>
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

/**
 * Display the ePortfolio review form.
 *
 * @package eportfolioplugins_hub
 * @copyright   2026 weQon UG <support@weqon.net>
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class review_form extends \moodleform {

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

        $approvalprocess = $customdata['approvalprocess'];

        $mform->addElement('hidden', 'approvalprocess', $customdata['approvalprocess']);
        $this->_form->setType('approvalprocess', PARAM_INT);

        $accesstype = (int) $customdata['accesstype'];
        $currentstep = (int) $customdata['step'];

        $selectvalues = [
                '1' => get_string('form:review:internal', 'eportfolioplugins_hub'),
        ];

        if ($accesstype == 2) {
            $selectvalues['2'] = get_string('form:review:external', 'eportfolioplugins_hub');
        }

        $mform->addElement('select', 'accesstype',
                get_string('form:review:accesstype', 'eportfolioplugins_hub'), $selectvalues);
        $mform->setType('accesstype', PARAM_TEXT);
        $mform->addHelpButton('accesstype', 'form:review:accesstype', 'eportfolioplugins_hub');
        $mform->addRule('accesstype', get_string('form:field:required', 'eportfolioplugins_hub'), 'required', '', 'client');

        $mform->addElement('text', 'title', get_string('form:review:title', 'eportfolioplugins_hub'), ['size' => '80']);
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('form:field:required', 'eportfolioplugins_hub'), 'required', '', 'client');

        $mform->addElement('textarea', 'description', get_string('form:review:description', 'eportfolioplugins_hub'));
        $mform->setType('description', PARAM_TEXT);
        $mform->addRule('description', get_string('form:field:required', 'eportfolioplugins_hub'), 'required', '', 'client');

        $mform->addElement('html', '<div class="divider my-5"></div>');

        $mform->addElement('textarea', 'notes', get_string('form:review:notes', 'eportfolioplugins_hub'));
        $mform->setType('notes', PARAM_TEXT);

        $mform->addElement('html', '<div class="divider my-5"></div>');

            // Select values:
            // 1 = Rejected.
            // 2 = Publish to hub.
            // 3 = Advanced review.

            $selectdata = [
                    '0' => get_string('form:review:select:pleaseselect', 'eportfolioplugins_hub'),
            ];

        if ($currentstep != 2) {

            // Option to reject ePortfolio.
            $selectdata['1'] = get_string('form:review:select:rejected', 'eportfolioplugins_hub');

            // Check, if approval process is set to simple or advanced.
            if ($approvalprocess === 1) {
                // Simple = Publish to hub
                $selectdata['2'] = get_string('form:review:select:simple', 'eportfolioplugins_hub');
            } else if ($approvalprocess === 2 && $currentstep === 0) {
                $selectdata['3'] = get_string('form:review:select:advanced', 'eportfolioplugins_hub');
            } else if ($approvalprocess === 2 && $currentstep === 1) {
                $selectdata['2'] = get_string('form:review:select:simple', 'eportfolioplugins_hub');
            }
        } else {
            $selectdata['2'] = get_string('form:review:select:simple', 'eportfolioplugins_hub');
            $selectdata['4'] = get_string('form:review:select:revoke', 'eportfolioplugins_hub');
        }
            $mform->addElement('select', 'approvalselect', get_string('form:review:select:approve', 'eportfolioplugins_hub'),
                    $selectdata);
            $mform->addRule('approvalselect', get_string('form:review:select:pleaseselect', 'eportfolioplugins_hub'),
                    'nonzero', null, 'client');

            $mform->addElement('textarea', 'usernotes', get_string('form:review:usernotes', 'eportfolioplugins_hub'));
            $mform->setType('usernotes', PARAM_TEXT);

            $mform->hideif('usernotes', 'approvalselect', 'eq', 0);
            $mform->hideif('usernotes', 'approvalselect', 'eq', 2);
            $mform->hideif('usernotes', 'approvalselect', 'eq', 3);


        $buttonarray = [];

        $buttonarray[] = $mform->createElement('submit', 'save', get_string('form:review:save', 'eportfolioplugins_hub'));
        $buttonarray[] = $mform->createElement('cancel');

        $mform->addGroup($buttonarray, 'buttonar', '', [' '], false);
    }
}
