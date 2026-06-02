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
 * Adhoc task for sending messages.
 *
 * @package eportfolioplugins_hub
 * @copyright   2026 weQon UG <support@weqon.net>
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace eportfolioplugins_hub\task;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/eportfolio/locallib.php');

/**
 * Adhoc task for sending messages.
 */
class send_messages extends \core\task\adhoc_task {

    // Use the logging trait.
    use \core\task\logging_trait;

    /**
     * Executes the send messages task.
     */
    public function execute() {
        global $DB;

        $data = $this->get_custom_data();

        if ($data->type === 'reviewer' || $data->type === 'revieweradvanced') {
            // View url for approval process ePortfolio.
            $contexturl = new \moodle_url('/local/eportfolio/hub/approval/review.php',
                    ['id' => $data->id]);
        } else {
            // View url for user - ePortfolio.
            $contexturl = new \moodle_url('/local/eportfolio/hub/approval/view.php',
                    ['id' => $data->id]);
        }

        // Holds values for the string for the email message.
        $a = new \stdClass;

        $userfromdata = $DB->get_record('user', ['id' => $data->userfrom]);
        $a->userfrom = fullname($userfromdata);

        $a->title = $data->title;
        $a->viewurl = (string) $contexturl;

        // Fetch message HTML and plain text formats.
        $messagehtml = get_string('message:emailmessage:' . $data->type, 'eportfolioplugins_hub', $a);
        $plaintext = format_text_email($messagehtml, FORMAT_HTML);

        $smallmessage = get_string('message:smallmessage:' . $data->type, 'eportfolioplugins_hub', $a);
        $smallmessage = format_text_email($smallmessage, FORMAT_HTML);

        // Subject.
        $a->status = get_string('publish:status:' . $data->status, 'eportfolioplugins_hub');
        $subject = get_string('message:subject', 'eportfolioplugins_hub', $a);

        $message = new \core\message\message();

        $message->component = 'eportfolioplugins_hub'; // Your plugin's name.
        $message->name = 'publishing'; // Your notification name from message.php.

        $message->userfrom = \core_user::get_noreply_user();

        $usertodata = $DB->get_record('user', ['id' => $data->userto]);
        $message->userto = $usertodata;

        $message->subject = $subject;
        $message->smallmessage = $smallmessage;
        $message->fullmessage = $plaintext;
        $message->fullmessageformat = FORMAT_PLAIN;
        $message->fullmessagehtml = $messagehtml;
        $message->notification = 1; // Because this is a notification generated from Moodle, not a user-to-user message.
        $message->contexturl = $contexturl->out(false);
        $message->contexturlname = get_string('message:contexturlname', 'eportfolioplugins_hub');

        // Finally send the message.
        $messageid = message_send($message);

        if ($messageid) {
            mtrace('Message sent to user ID: ' . $data->userto);
        } else {
            mtrace('Failed to send message to user ID: ' . $data->userto);
        }
    }
}
