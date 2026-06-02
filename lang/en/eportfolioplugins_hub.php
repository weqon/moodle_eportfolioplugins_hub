<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     eportfolioplugins_hub
 * @category    string
 * @copyright   2025 weQon UG <support@weqon.net>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'ePortfolio Hub';
$string['hub:navbar'] = 'ePortfolio Hub';
$string['hub:error:notenabled'] = 'The ePortfolio Hub is not available!';

// Overview table local_eportfolio.
$string['overview:table:actions:publish'] = 'Publish ePortfolio to Hub';
$string['overview:table:actions:viewhub'] = 'View ePortfolio';

// Overview.
$string['hub:overview:header'] = 'ePortfolio Hub';
$string['hub:overview:nofiles'] = 'No content has been published in the ePortfolio Hub yet.';
$string['hub:overview:footer:managecontent'] = 'Manage pusblished content';
$string['hub:overview:footer:vieweport'] = 'My ePortfolios';
$string['hub:overview:search:keyword'] = 'Search';
$string['hub:overview:search:reset'] = 'Reset';

// Approval overview.
$string['approval:overview:header'] = 'ePortfolios for approval';
$string['approval:overview:nofiles'] = 'No content has been submitted for publishing yet.';
$string['approval:overview:table:id'] = 'ID';
$string['approval:overview:table:deleted'] = 'Deleted';
$string['approval:overview:table:step'] = 'Step';
$string['approval:overview:table:accesstype'] = 'Access type';
$string['approval:overview:table:title'] = 'Title';
$string['approval:overview:table:description'] = 'Description';
$string['approval:overview:table:publishedby'] = 'Published by';
$string['approval:overview:table:approveddate'] = 'Approved';
$string['approval:overview:table:actions'] = 'Actions';

$string['approval:overview:table:label:step:0'] = 'New approval';
$string['approval:overview:table:label:step:1'] = '1. Review done';
$string['approval:overview:table:label:step:2'] = 'Published';
$string['approval:overview:table:label:step:5'] = 'Rejected';
$string['approval:overview:table:label:access:1'] = 'Internal';
$string['approval:overview:table:label:access:2'] = 'External';
$string['approval:overview:table:label:deleted:0'] = 'Available';
$string['approval:overview:table:label:deleted:1'] = 'Deleted';

$string['approval:overview:table:actions:view'] = 'View ePortfolio';
$string['approval:overview:table:actions:review'] = 'Review published ePortfolio';
$string['approval:overview:table:actions:delete'] = 'Delete ePortfolio';
$string['approval:delete:header'] = 'Delete published ePortfolio?';
$string['approval:delete:checkconfirm'] = 'Are you sure you want to delete the selected ePortfolio?';
$string['approval:delete:confirm'] = 'Delete';

$string['approval:view:header'] = 'ePortfolio submitted for publication';
$string['approval:view:filenotfound'] = 'The file you are looking for could not be found!';
$string['approval:view:button:back'] = 'Back to overview';

$string['approval:view:accesstype'] = 'Access type';
$string['approval:view:status'] = 'Current state';
$string['approval:view:access'] = 'Access';
$string['approval:view:deleted'] = 'Deleted';
$string['approval:view:title'] = 'Title';
$string['approval:view:publishedby'] = 'Published by';
$string['approval:view:timecreated'] = 'Published at';
$string['approval:view:timemodified'] = 'Updated at';
$string['approval:view:description'] = 'Description';
$string['approval:view:usernotes'] = 'Notes for users';
$string['approval:view:notes'] = 'Internal notes';
$string['approval:view:approver'] = 'Name approver';
$string['approval:view:approvedby'] = 'Approved by';
$string['approval:view:approveddate'] = 'Approved at';

// Delete actions.
$string['delete:header'] = 'Delete selected ePortfolio';
$string['delete:filenotfound'] = 'The file you are looking for could not be found!';
$string['delete:success:user'] = 'The selected ePortfolio has been marked for deletion and deactivated in the Hub!';
$string['delete:success'] = 'The selected ePortfolio has been permanently deleted!';
$string['delete:error'] = 'There was an error while deleting the file! Please try again!';

// Publish page & form.
$string['publish:header'] = 'Publish selected ePortfolio';
$string['publish:alreadyshared'] = 'The ePortfolio <b>{$a->title}</b> you selected has already been published in the ePortfolio Hub! 
If you have updated the content of your ePortfolio, please ensure that the previously published ePortfolio is deactivated so 
that no outdated or duplicate content appears in the ePortfolio Hub.
';
$string['publish:status'] = 'Status';
$string['publish:status:0'] = 'New';
$string['publish:status:1'] = 'Review done';
$string['publish:status:2'] = 'Published';
$string['publish:status:5'] = 'Rejected';
$string['publish:title'] = 'Title (Original)';
$string['publish:usermodified'] = 'Created by';
$string['publish:timecreated'] = 'Created';
$string['publish:timemodified'] = 'Updated';
$string['publish:sidetext'] = 'Thank you for your commitment to share the ePortfolios you have created with other users.<br><br>
<b>Please note:</b><br>
- Published content cannot be edited afterwards.<br>
- Please ensure that you do not use any content/media that violates applicable copyright law.

';
$string['publish:accesstype'] = 'Access ePortfolio Hub';
$string['publish:accesstype:internal'] =
        'The ePortfolio Hub has been configured so that access is available exclusively to registered users (login required).';
$string['publish:accesstype:external'] =
        'The ePortfolio Hub has been configured so that access is available exclusively to registered users (login required) and also publicly accessible. This means that users without registration can also view your content (no login required).';

$string['form:publish:accesstype'] = 'Access type';
$string['form:publish:accesstype_help'] = 'Please select who is allowed to view the shared ePortfolio.';
$string['form:publish:internal'] = 'Publish internally';
$string['form:publish:external'] = 'Publish externally';
$string['form:publish:title'] = 'Title';
$string['form:publish:description'] = 'Description';
$string['form:publish:select:approvaluser:empty'] = 'Nothing selected';
$string['form:publish:select:approval'] = 'Please select an approval user!';
$string['form:publish:select:approvaluser'] = 'Select a teacher for approving';
$string['form:publish:select:approvaluser_help'] = '
Please select who should review your submitted ePortfolio.<br><br>
You can only select users who are enrolled in the same course as you.
';
$string['form:publish:select:approvaluser:hint'] = 'Please select an approval user!';
$string['form:publish:confirm'] = 'Please note';
$string['form:publish:confirm:label'] = 'The current data protection regulations and terms of use for this platform apply.
By publishing your ePortfolio, you agree that the content may be used, edited, and reused by other users.';
$string['form:publish:save'] = 'Submit ePortfolio';
$string['form:field:required'] = 'Please fill in this field!';
$string['form:checkbox:required'] = 'Please confirm!';

$string['form:publish:success'] = '
Thank you! We have received your request to review the ePortfolio with the title <b>{$a->title}</b>.<br>
You will be automatically notified once the content has been reviewed and approved for the ePortfolio Hub.';
$string['form:publish:error'] = 'An error occurred while performing the action. Please try again.';
$string['form:publish:cancelled'] = 'The action was cancelled.';

// Review page.
$string['review:header'] = 'Review the ePortfolio for release to the Hub';
$string['review:accesstype'] = 'Access ePortfolio Hub';
$string['review:accesstype:internal'] =
        'The ePortfolio Hub has been configured so that access is available exclusively to registered users (login required).';
$string['review:accesstype:external'] =
        'The ePortfolio Hub has been configured so that access is available exclusively to registered users (login required) and also publicly accessible. This means that users without registration can also view your content (no login required).';
$string['review:approval'] = 'Approval process';
$string['review:approval:simple'] =
        'Simple approval process. Once you have reviewed it, you can decide whether to publish the ePortfolio on the Hub or whether further corrections are required.';
$string['review:approval:advanced'] =
        'Extended approval process. Once you have reviewed it, you can decide whether the ePortfolio should be forwarded to the next stage for review or whether further corrections are required.';
$string['review:status'] = 'Status';
$string['review:title'] = 'Title (Original)';
$string['review:usermodified'] = 'Created by';
$string['review:timecreated'] = 'Created';
$string['review:timemodified'] = 'Updated';

$string['form:review:accesstype'] = 'Access type';
$string['form:review:accesstype_help'] = 'Please select who is allowed to view the shared ePortfolio.';
$string['form:review:internal'] = 'Publish internally';
$string['form:review:external'] = 'Publish externally';
$string['form:review:title'] = 'Title';
$string['form:review:description'] = 'Description';
$string['form:review:notes'] = 'Internal notes';
$string['form:review:usernotes'] = 'Reason for rejection of the ePortfolio';

$string['form:review:select:approve'] = 'Approve reviewed ePortfolio';
$string['form:review:select:pleaseselect'] = 'Please select';
$string['form:review:select:rejected'] = 'ePortfolio rejected';
$string['form:review:select:simple'] = 'Publish to Hub';
$string['form:review:select:advanced'] = 'Forward to the next approving instance';
$string['form:review:select:revoke'] = 'Revoke published ePortfolio';
$string['form:review:save'] = 'Save';

$string['form:review:success'] = '
Thank you very much! We have saved your changes. The ePortfolio "{$a->title}" you reviewed has been published on the Hub and "{$a->publishedby}" has been notified of its current status.';
$string['form:review:success:feedback'] = '
Thank you very much! We have saved your changes and informed "{$a->publishedby}" that the submitted ePortfolio has been rejected.';
$string['form:review:error'] = 'An error occurred while performing the action. Please try again.';
$string['form:review:cancelled'] = 'The action was cancelled.';

// View H5P player.
$string['hub:view:header'] = 'View ePortfolio';
$string['hub:view:button:backtohub'] = 'Back to Hub';

// Settings.
$string['settings:hub:enable'] = 'Enable ePortfolio Hub';
$string['settings:hub:enable:desc'] =
        'When the Hub is activated, an entry for the ePortfolio Hub is displayed in the main navigation. Furthermore, users can share their own content in the ePortfolio Hub.';
$string['settings:hub:navbartitle'] = 'Name for entry in the navigation';
$string['settings:hub:navbartitle:desc'] =
        'You can assign a custom name for the entry in the navigation.';
$string['settings:hub:access'] = 'Access ePortfolio Hub';
$string['settings:hub:access:desc'] =
        'You can decide whether the content in the Hub can only be viewed by registered users (internal access) or also by guests (external access).';
$string['settings:hub:access:internal'] = 'Internal access (login required)';
$string['settings:hub:access:external'] = 'External access (no login required)';

$string['settings:hub:approval'] = 'Approval process';
$string['settings:hub:approval:desc'] =
        'How should ePortfolios be approved? You can choose between a simple and an advanced process.<br>
<b>Simple process:</b> Approval is done by a single person selected by the user publishing the ePortfolio.<br>
<b>Advanced process:</b> An additional person is required for approval (two-step process). To do this, an additional role must be created.';
$string['settings:hub:approval:simple'] = 'Simple approval process';
$string['settings:hub:approval:advanced'] = 'Advanced approval process';

$string['settings:hub:approval:simple:role'] = 'Simple approval process';
$string['settings:hub:approval:simple:role:desc'] =
        'Please select a role for the simple approval process. The system is lookign for this role within a course to identify users who are authorised to perform the approval process.';

$string['settings:hub:approval:advanced:role'] = 'Advanced approval process';
$string['settings:hub:approval:advanced:role:desc'] =
        'Please select a role for the extended approval process. It is recommended that you create an additional role.';

// Message provider.
$string['messageprovider:publishing'] = 'Message about a published ePortfolio for the Hub';
$string['message:subject'] = 'Message about a published ePortfolio for the Hub - Status: {$a->status}';
$string['message:contexturlname'] = 'View published ePortfolio';

// Message for user who published ePortfolio.
$string['message:emailmessage:user'] =
        '<p>Thank you for publishing your ePortfolio! We have received your request and will now review the ePortfolio.
<br><br>You will be automatically notified once the content has been reviewed and approved for the ePortfolio Hub.<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:user'] =
        '<p>Thank you for publishing your ePortfolio! We have received your request and will now review the ePortfolio.
<br><br>You will be automatically notified once the content has been reviewed and approved for the ePortfolio Hub.<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Message for reviewer.
$string['message:emailmessage:reviewer'] =
        '<p>A new ePortfolio has been submitted for publication. Please check the information and approve the ePortfolio or 
ask the user to make any necessary corrections.
<br><br>Details:<br>
Published by: {$a->userfrom}<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:reviewer'] =
        '<p>A new ePortfolio has been submitted for publication. Please check the information and approve the ePortfolio or 
ask the user to make any necessary corrections.
<br><br>Details:<br>
Published by: {$a->userfrom}<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Message for revieweradvanced.
$string['message:emailmessage:revieweradvanced'] =
        '<p>A new ePortfolio has been submitted for publication. Please check the information and approve the ePortfolio or 
ask the user to make any necessary corrections.
<br><br>Details:<br>
Published by: {$a->userfrom}<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:revieweradvanced'] =
        '<p>A new ePortfolio has been submitted for publication. Please check the information and approve the ePortfolio or 
ask the user to make any necessary corrections.
<br><br>Details:<br>
Published by: {$a->userfrom}<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Message for user about published eportfolio.
$string['message:emailmessage:userpublished'] =
        '<p>Thank you for publishing your ePortfolio! Your submitted ePortfolio has been approved for the Hub.<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:userpublished'] =
        '<p>Thank you for publishing your ePortfolio! Your submitted ePortfolio has been approved for the Hub.<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Message for user to review own ePortfolio.
$string['message:emailmessage:userfeedback'] =
        '<p>Thank you for publishing your ePortfolio! Your submitted ePortfolio has been reviewed.<br>
Unfortunately, it did not meet the requirements and publication has been rejected. Please check the reason for rejection provided.<br><br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:userfeedback'] =
        '<p>Thank you for publishing your ePortfolio! Your submitted ePortfolio has been reviewed.<br>
Unfortunately, it did not meet the requirements and publication has been rejected. Please check the reason for rejection provided.<br><br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Message for user to review own ePortfolio.
$string['message:emailmessage:userrevoked'] =
        '<p>Your published ePortfolio has been revoked.<br> Please check the reason provided.<br><br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:userrevoked'] =
        '<p>Your published ePortfolio has been revoked.<br> Please check the reason provided.<br><br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Message for user advanced review .
$string['message:emailmessage:useradvanced'] =
        '<p>Thank you for publishing your ePortfolio! Your submitted ePortfolio has been reviewed and forwarded to the next instance for final review.<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:useradvanced'] =
        '<p>Thank you for publishing your ePortfolio! Your submitted ePortfolio has been reviewed and forwarded to the next instance for final review.<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Set db/access - permissions.
$string['hub:publish'] = 'Publish ePortfolio';
$string['hub:approvesimple'] = 'Simple approval process';
$string['hub:approveadvanced'] = 'Advanced approval process';
$string['hub:viewall'] = 'Can access all ePortfolios';
