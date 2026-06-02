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
    public function __construct($url, $search, $tsort = null, $page = null) {
        $this->url = $url;
        $this->search = $search;
        $this->tsort = $tsort;
        $this->page = $page;

        $this->config = get_config('eportfolioplugins_hub');
    }

    /**
     * Return the view.
     *
     * @return void
     */
    public function display() {
        global $DB, $OUTPUT;

        $toolbar = new \stdClass();

        $toolbar->placeholder = get_string('hub:overview:search:keyword', 'eportfolioplugins_hub');

        if (!empty($this->search) || !empty($this->tsort)) {
            $toolbar->reseturl = new \moodle_url('/local/eportfolio/hub/index.php', ['page' => $this->page]);
            $toolbar->placeholder = s($this->search);
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
                #$entry->description = $ent->description;
                $entry->description =
                        'Jemand musste Josef K. verleumdet haben, denn ohne dass er etwas Böses getan hätte, wurde er eines Morgens verhaftet. »Wie ein Hund!« sagte er, es war, als sollte die Scham ihn überleben.';
                $entry->contenttype = '';

                // Temp!
                $userdatemodified = date('d.m.Y', $ent->timemodified);
                $date = new \DateTime($userdatemodified); // For today/now, don't pass an arg.
                $date->modify("-1 day");
                $entry->releasedate = $date->format("d.m.Y");

                $entry->releasedate = date('d.m.Y', $ent->approveddate);

                $userp = $DB->get_record('user', ['id' => $ent->publishedby]);
                $userpubslished = fullname($userp);
                $entry->usershared = $userpubslished;

                $eportentries[] = $entry;
                unset($entry);
            }

            $data->eportentries = $eportentries;

            echo $OUTPUT->render_from_template('eportfolioplugins_hub/hub_overview', $data);

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
        global $DB, $USER;

        $sql = "SELECT * FROM {local_eportfolio}";
        $params = [];

        /*
         * "External" geteilt ist automatisch auch "intern" verfügbar (wenn angemeldet).
         * Extern erreichbar = nur explizit für extern geteilte Inhalte
         * Intern erreichbar = nur explizit für intern geteilte Inhalte
         * Wenn Nutzer angemeldet und extern erreichbar = externe und interne Inhalte
         * Wenn Nutzer angemeldet und intern erreichbar = nur interne Inhalte
         * Wenn Nutzer nicht angemeldet und intern erreichbar = keine Inhalte
         * Wenn Nutzer nicht angemeldet und extern erreichbar = nur externe Inhalte
         *
         * Use this once we have a workflow
         *
         */

        $sql = "SELECT * FROM {eportfolioplugins_hub} WHERE active = 1";

        $whereclauses = [];

        if ($this->config->access === 'internal') {
            $params['accesstype'] = 1;
            $whereclauses[] = 'accesstype = :accesstype';
        } else if ($this->config->access === 'external') {
            // If we are not in my ePortfolios section.
            $params['accesstype'] = 2;
            $whereclauses[] = 'accesstype = :accesstype';
        } else {
            return false;
        }

        if (!empty($whereclauses)) {
            $sql .= " AND ";
            $sql .= "(" . implode(" AND ", $whereclauses) . ")";

        }

        // If tsort and tdir is set.
        $sortorder = '';

        if ($this->tsort) {

            $orderby = self::get_sort_order($this->tdir);

            if ($this->tsort === 'filename') {
                $orderbyfield = 'title';
            } else if ($this->tsort === 'filetimecreated') {
                $orderbyfield = 'timecreated';
            } else if ($this->tsort === 'filetimemodified') {
                $orderbyfield = 'timemodified';
            } else if ($this->tsort === 'sharestart') {
                $orderbyfield = 'timecreated';
            } else if ($this->tsort === 'shareend') {
                $orderbyfield = 'enddate';
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
     * @param int $sortorder
     * @return int|void
     */
    private function get_sort_order($sortorder) {
        switch ($sortorder) {
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
