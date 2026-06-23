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
 * Plugin administration pages are defined here.
 *
 * @package     eportfolioplugins_hub
 * @copyright   2025 weQon UG
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    // Das Subplugin erstellt eine eigene SEITE
    $subpage = new admin_settingpage('eportfolioplugins_hub',
            get_string('pluginname', 'eportfolioplugins_hub'));

    // Enable ePortfolio Hub.
    $subpage->add(
            new admin_setting_configcheckbox(
                    'eportfolioplugins_hub/enablehub',
                    get_string('settings:hub:enable', 'eportfolioplugins_hub'),
                    get_string('settings:hub:enable:desc', 'eportfolioplugins_hub'),
                    false
            )
    );

    // Default title for ePortfolio navbar entry.
    $subpage->add(new admin_setting_configtext(
            'eportfolioplugins_hub/navtitle',
            get_string('settings:hub:navbartitle', 'eportfolioplugins_hub'),
            get_string('settings:hub:navbartitle:desc', 'eportfolioplugins_hub'),
            get_string('hub:navbar', 'eportfolioplugins_hub'),
    ));

    // Enable eportfolio Hub for internal access or external access.
    $choices = [
            'internal' => get_string('settings:hub:access:internal', 'eportfolioplugins_hub'),
            'external' => get_string('settings:hub:access:external', 'eportfolioplugins_hub')
    ];

    $subpage->add(
            new admin_setting_configselect(
                    'eportfolioplugins_hub/access',
                    get_string('settings:hub:access', 'eportfolioplugins_hub'),
                    get_string('settings:hub:access:desc', 'eportfolioplugins_hub'),
                    0,
                    $choices
            )
    );

    // Two-step approval process. Who is allowed to approve pubilshing of an eportfolio?
    $choicesapproval = [
            'simple' => get_string('settings:hub:approval:simple', 'eportfolioplugins_hub'),
            'advanced' => get_string('settings:hub:approval:advanced', 'eportfolioplugins_hub')
    ];

    $subpage->add(
            new admin_setting_configselect(
                    'eportfolioplugins_hub/approve',
                    get_string('settings:hub:approval', 'eportfolioplugins_hub'),
                    get_string('settings:hub:approval:desc', 'eportfolioplugins_hub'),
                    0,
                    $choicesapproval
            )
    );

    // Default role for simple approval.
    $subpage->add(new admin_setting_pickroles(
            'eportfolioplugins_hub/simpleapprovalrole',
            get_string('settings:hub:approval:simple:role', 'eportfolioplugins_hub'),
            get_string('settings:hub:approval:simple:role:desc', 'eportfolioplugins_hub'),
            ['editingteacher'],
    ));

    // Default role for advanced approval.
    $subpage->add(new admin_setting_pickroles(
            'eportfolioplugins_hub/advancedapprovalrole',
            get_string('settings:hub:approval:advanced:role', 'eportfolioplugins_hub'),
            get_string('settings:hub:approval:advanced:role:desc', 'eportfolioplugins_hub'),
            [],
    ));

    $settings->add('local_eportfolio_settings', $subpage);
}
