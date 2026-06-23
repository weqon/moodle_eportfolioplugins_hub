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
 * Renderer for eportfolio Hub
 *
 * @package eportfolioplugins_hub
 * @copyright   2025 weQon UG <support@weqon.net>
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace eportfolioplugins_hub\local\overview;

defined('MOODLE_INTERNAL') || die();

#require_once('locallib.php');

/**
 * Output class for eportfolioplugins_hub.
 */
class overview {

    /**
     * Construct the base stuff.
     *
     * @param string $url
     * @param string $search
     * @param string $tsort
     * @param int $page
     */
    public function __construct($url, $search, $page, $tsort = null,) {
        $this->url = $url;
        $this->search = $search;
        $this->tsort = $tsort;
        $this->page = $page;
        $this->perpage = 50; // Default 20. Move to settings.php in the future or add select box to toolbar.

        $this->config = get_config('eportfolioplugins_hub');
    }

    /**
     * Return the view.
     *
     * @return void
     */
    public function display() {
        global $DB, $OUTPUT;

        $headingdata = new \stdClass();
        $headingdata->title = (!empty($this->config->navtitle)) ? $this->config->navtitle :
                get_string('hub:overview:header', 'eportfolioplugins_hub');

        echo $OUTPUT->render_from_template('eportfolioplugins_hub/hub_heading', $headingdata);

        $toolbar = new \stdClass();

        $placeholder = get_string('hub:overview:search:keyword', 'eportfolioplugins_hub');
        $toolbar->placeholder = $placeholder;

        // Dirty, but worky.
        $filteroptions[] = [
                'value' => 1,
                'selected' => ($this->tsort === 1) ? 'selected' : '',
                'label' => get_string('hub:overview:filter:newest_desc', 'eportfolioplugins_hub'),
        ];
        $filteroptions[] = [
                'value' => 2,
                'selected' => ($this->tsort === 2) ? 'selected' : '',
                'label' => get_string('hub:overview:filter:newest_asc', 'eportfolioplugins_hub'),
        ];
        $filteroptions[] = [
                'value' => 3,
                'selected' => ($this->tsort === 3) ? 'selected' : '',
                'label' => get_string('hub:overview:filter:title_asc', 'eportfolioplugins_hub'),
        ];
        $filteroptions[] = [
                'value' => 4,
                'selected' => ($this->tsort === 4) ? 'selected' : '',
                'label' => get_string('hub:overview:filter:title_desc', 'eportfolioplugins_hub'),
        ];

        $toolbar->filteroptions = $filteroptions;

        if (!empty($this->search) || !empty($this->tsort)) {
            $toolbar->reseturl = new \moodle_url('/local/eportfolio/hub/index.php', ['page' => $this->page]);
            $toolbar->searchvalue = (!empty($this->search)) ? s($this->search) : '';
        }

        echo $OUTPUT->render_from_template('eportfolioplugins_hub/hub_toolbar', $toolbar);

        $entries = self::get_eportfolios();

        if ($entries) {

            $data = new \stdClass();

            $eportentries = [];
            foreach ($entries as $ent) {
                $entry = new \stdClass();

                $detailurl = new \moodle_url('/local/eportfolio/hub/view.php', ['id' => $ent->id]);

                $entry->detailsurl = $detailurl->out(false);
                $entry->imgurlset = '';
                $entry->title = $ent->title;

                $availableicon = $OUTPUT->pix_icon('t/unlock',
                        get_string('hub:overview:internal', 'eportfolioplugins_hub'));

                if ($ent->accesstype == 2) {
                    $availableicon = $OUTPUT->pix_icon('i/siteevent',
                            get_string('hub:overview:external', 'eportfolioplugins_hub'));
                }

                $entry->availableicon = $availableicon;

                $entry->description = $ent->description;
                $entry->contenttype = '';

                $entry->releasedate = date('d.m.Y', $ent->approveddate);

                $userp = $DB->get_record('user', ['id' => $ent->publishedby]);
                $userpubslished = fullname($userp);
                $entry->usershared = $userpubslished;

                $eportentries[] = $entry;
                unset($entry);
            }

            $data->eportentries = $eportentries;

            echo $OUTPUT->render_from_template('eportfolioplugins_hub/hub_overview', $data);

            $entrycount = count($entries);

            // Also output the paging bar .
            echo $OUTPUT->paging_bar($entrycount, $this->page, $this->perpage, $this->url);

        } else {
            // No files found for selected section.
            $nofilesdata = new \stdClass();

            $nofilesdata->infostring = get_string('hub:overview:nofiles', 'eportfolioplugins_hub');

            echo $OUTPUT->render_from_template('eportfolioplugins_hub/hub_nofiles', $nofilesdata);
        }

        $footerdata = new \stdClass();
        $footerdata->buttons = self::footer_buttons();

        echo $OUTPUT->render_from_template('eportfolioplugins_hub/hub_footer', $footerdata);

    }

    /**
     * Get ePortfolios by access type and approval status.
     *
     * @return mixed
     */
    public function get_eportfolios() {
        global $DB;

        $sql = "SELECT * FROM {eportfolioplugins_hub} WHERE active = 1";

        $params = [];
        $whereclauses = [];

        if ($this->config->access === 'internal') {
            $params['accesstype'] = 1;
            $whereclauses[] = 'accesstype = :accesstype';
        } else if ($this->config->access === 'external' && !isloggedin()) {
            // In case, user is not logged in, only view external published ePortfolios.
            $params['accesstype'] = 2;
            $whereclauses[] = 'accesstype = :accesstype';
        }

        // In case someone is searching for somethig.
        if (!empty($this->search)) {
            $params['title'] = "%$this->search%";
            $whereclauses[] = "title LIKE :title";
        }

        if (!empty($whereclauses)) {
            $sql .= " AND ";
            $sql .= "(" . implode(" AND ", $whereclauses) . ")";
        }

        // Default approveddate ASC.
        $sortorder = " ORDER BY approveddate DESC";

        if (!empty($this->tsort)) {
            $orderby = self::get_sort_order($this->tsort);
            $sortorder = " ORDER BY " . $orderby;
        }

        $sql .= $sortorder;

        $limitfrom = $this->page * $this->perpage;
        $limitnum = $this->perpage;

        $sql .= " LIMIT " . $limitfrom . ', ' . $limitnum;

        return $DB->get_records_sql($sql, $params);
    }

    /**
     *  Output content based on set sort order.
     *
     * @param int $sortorder
     * @return int|void
     */
    private function get_sort_order($sortorder) {
        switch ($sortorder) {
            case '1':
                return 'approveddate DESC';
                break;
            case '2':
                return 'approveddate ASC';
                break;
            case '3':
                return 'title ASC';
                break;
            case '4':
                return 'title DESC';
                break;
        }
    }

    /**
     * Generate delete button.
     *
     * @param string $url
     * @param string $filename
     * @return mixed
     */
    public function action_button_delete($url, $filename) {
        global $OUTPUT;

        $data = new \stdClass();
        $data->deleteurl = $url->out(false);
        $data->title = $filename;

        return $OUTPUT->render_from_template('local_eportfolio/button_delete', $data);
    }

    /**
     * Generate footer buttons.
     *
     * @return mixed
     */
    public function footer_buttons() {
        global $OUTPUT;

        $context = \context_system::instance();

        $buttons = \html_writer::tag('div', '', ['class' => 'divider']);

        if (is_siteadmin() || has_capability('eportfolioplugins/hub:viewall', $context) ||
                has_capability('eportfolioplugins/hub:approvesimple', $context) ||
                has_capability('eportfolioplugins/hub:approveadvanced', $context) ||
                has_capability('eportfolioplugins/hub:publish', $context)) {

            // Button "Manage content" - approval/overview.php.
            $icon = $OUTPUT->pix_icon('i/siteevent', get_string('hub:overview:footer:managecontent', 'eportfolioplugins_hub'));
            $str = $icon . get_string('hub:overview:footer:managecontent', 'eportfolioplugins_hub');
            $url = new \moodle_url('/local/eportfolio/hub/approval/overview.php');
            $btncss = ' btn btn-primary';
            $buttons .= \html_writer::link($url, $str, ['class' => 'mr-2' . $btncss]);

            // Button "Publish your ePortfolios" - local/eportfolio/index.php - section my.
            $icon = $OUTPUT->pix_icon('i/files', get_string('hub:overview:footer:vieweport', 'eportfolioplugins_hub'));
            $str = $icon . get_string('hub:overview:footer:vieweport', 'eportfolioplugins_hub');
            $url = new \moodle_url('/local/eportfolio/index.php');
            $btncss = ' btn btn-success';
            $buttons .= \html_writer::link($url, $str, ['class' => 'mr-2' . $btncss]);

        }

        return $buttons;
    }
}
