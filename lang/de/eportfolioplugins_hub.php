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
$string['hub:error:notenabled'] = 'Der ePortfolio Hub steht nicht zur Verfügung!';

// Overview table local_eportfolio.
$string['overview:table:actions:publish'] = 'ePortfolio im Hub veröffentlichen';
$string['overview:table:actions:viewhub'] = 'ePortfolio anzeigen';

// Overview.
$string['hub:overview:header'] = 'ePortfolio Hub';
$string['hub:overview:nofiles'] = 'Bisher wurden noch keine Inhalte im ePortfolio Hub veröffentlicht.';
$string['hub:overview:filenotfound'] = 'Das gesuchte ePortfolio konnte nicht gefunden werden!';
$string['hub:overview:internal'] = 'Intern verfügbar';
$string['hub:overview:external'] = 'Extern verfügbar';
$string['hub:overview:footer:managecontent'] = 'Veröffentlichte Inhalte';
$string['hub:overview:footer:vieweport'] = 'Meine ePortfolios';
$string['hub:overview:search:keyword'] = 'Suchen';
$string['hub:overview:search:reset'] = 'Zurücksetzen';
$string['hub:overview:filter:newest_desc'] = 'Neueste zuerst';
$string['hub:overview:filter:newest_asc'] = 'Älteste zuerst';
$string['hub:overview:filter:title_asc'] = 'Titel A-Z';
$string['hub:overview:filter:title_desc'] = 'Titel Z-A';
$string['hub:overview:title'] = 'Titel';
$string['hub:overview:publishedby'] = 'Eingereicht von';
$string['hub:overview:timecreated'] = 'Eingereicht am';
$string['hub:overview:timemodified'] = 'Aktualisiert';
$string['hub:overview:description'] = 'Beschreibung';
$string['hub:overview:access'] = 'Zugriff';

// Approval overview.
$string['approval:overview:header'] = 'ePortfolios veröffentlichen';
$string['approval:overview:nofiles'] = 'Bisher wurden noch keine Inhalte zum Veröffentlichen eingereicht.';
$string['approval:overview:table:id'] = 'ID';
$string['approval:overview:table:deleted'] = 'Gelöscht';
$string['approval:overview:table:step'] = 'Status';
$string['approval:overview:table:accesstype'] = 'Zugriff';
$string['approval:overview:table:title'] = 'Titel';
$string['approval:overview:table:description'] = 'Beschreibung';
$string['approval:overview:table:publishedby'] = 'Veröffentlicht von';
$string['approval:overview:table:approveddate'] = 'Freigegabe';
$string['approval:overview:table:actions'] = 'Aktionen';

$string['approval:overview:table:label:step:0'] = 'Neue Freigabe';
$string['approval:overview:table:label:step:1'] = '1. Prüfung erfolgt';
$string['approval:overview:table:label:step:2'] = 'Veröffentlicht';
$string['approval:overview:table:label:step:5'] = 'Abgelehnt';
$string['approval:overview:table:label:access:1'] = 'Intern';
$string['approval:overview:table:label:access:2'] = 'Extern';
$string['approval:overview:table:label:deleted:0'] = 'Verfügbar';
$string['approval:overview:table:label:deleted:1'] = 'Gelöscht';

$string['approval:overview:table:actions:view'] = 'ePortfolio anzeigen';
$string['approval:overview:table:actions:review'] = 'Freigabe prüfen';
$string['approval:overview:table:actions:delete'] = 'ePortfolio löschen';
$string['approval:overview:table:actions:restore'] = 'Löschen rückgängig machen';
$string['approval:delete:header'] = 'Freigegebenes ePortfolio löschen?';
$string['approval:delete:checkconfirm'] = 'Möchten Sie das ausgewählte ePortfolio wirklich löschen?';
$string['approval:delete:confirm'] = 'Löschen';

$string['approval:view:header'] = 'Zur Veröffentlichung eingereichtes ePortfolio';
$string['approval:view:filenotfound'] = 'Das gesuchte ePortfolio konnte nicht gefunden werden!';
$string['approval:view:button:back'] = 'Zurück zur Übersicht';

$string['approval:view:accesstype'] = 'Zugriff ePortfolio Hub';
$string['approval:view:status'] = 'Status';
$string['approval:view:access'] = 'Zugriff';
$string['approval:view:deleted'] = 'Verfügbar';
$string['approval:view:title'] = 'Titel';
$string['approval:view:publishedby'] = 'Eingereicht von';
$string['approval:view:timecreated'] = 'Eingereicht am';
$string['approval:view:timemodified'] = 'Aktualisiert';
$string['approval:view:description'] = 'Beschreibung';
$string['approval:view:usernotes'] = 'Hinweis für Nutzer/in';
$string['approval:view:notes'] = 'Interne Notizen';
$string['approval:view:approver'] = 'Freigabe prüfen durch';
$string['approval:view:approvedby'] = 'Freigegeben durch';
$string['approval:view:approveddate'] = 'Freigegeben am';

// Delete actions.
$string['delete:header'] = 'Ausgewähltes ePortfolio löschen';
$string['delete:filenotfound'] = 'Das gesuchte ePortfolio konnte nicht gefunden werden!';
$string['delete:success:user'] = 'Das ausgewählte ePortfolio wurde zum Löschen vorgemerkt und im Hub deaktiviert!';
$string['delete:success'] = 'Das ausgewählte ePortfolio wurde endgültig gelöscht!';
$string['delete:error'] = 'Beim Löschen des ePortfolios ist ein Fehler aufgetreten! Bitte versuchen Sie es erneut!';
$string['delete:restore:success'] = 'Das gelöschte ePortfolio wurde wiederhergestellt.';
$string['delete:restore:error'] = 'Beim Wiederherstellen des gelöschten ePortfolios ist ein Fehler aufgetreten! Bitte versuchen Sie es erneut!';

// Publish page & form.
$string['publish:header'] = 'Ausgewähltes ePortfolio veröffentlichen';
$string['publish:alreadyshared'] = 'Das von Ihnen gewählte ePortfolio <b>{$a->title}</b> wurde bereits im ePortfolio Hub veröffentlicht! 
Sofern Sie Ihr ePortfolio inhaltlich aktualisiert haben, stellen Sie bitte sicher, dass das bereits veröffentlichte ePortfolio 
deaktiviert wird, damit keine veralteten oder doppelten Inhalte im ePortfolio Hub auftauchen.
';
$string['publish:status'] = 'Status';
$string['publish:status:0'] = 'Neu';
$string['publish:status:1'] = 'Überprüfung erfolgt';
$string['publish:status:2'] = 'Veröffentlicht';
$string['publish:status:5'] = 'Abgelehnt';
$string['publish:title'] = 'Titel (Original)';
$string['publish:usermodified'] = 'Angelegt von';
$string['publish:timecreated'] = 'Angelegt am';
$string['publish:timemodified'] = 'Aktualisiert am';
$string['publish:sidetext'] = 'Vielen Dank für Ihre Bereitschaft, die von Ihnen erstellten ePortfolios mit anderen Nutzer:innen zu teilen.<br><br>
<b>Bitte beachten Sie:</b><br>
- Veröffentlichte Inhalte können im Nachgang nicht mehr bearbeitet werden.<br>
- Bitte stellen Sie sicher, dass Sie keine Inhalte/Medien verwenden, die gegen das geltende Urheberrecht verstoßen.
';
$string['publish:accesstype'] = 'Zugriff ePortfolio Hub';
$string['publish:accesstype:internal'] =
        'Der ePortfolio Hub wurde so konfiguriert, dass der Zugriff ausschließlich registrierten Nutzer:innen zur Verfügung gestellt wird (Login erforderlich).';
$string['publish:accesstype:external'] =
        'Der ePortfolio Hub wurde so konfiguriert, dass der Zugriff sowohl ausschließlich für registrierte Nutzer:innen verfügbar ist (Login erforderlich), als auch ein öffentlicher Zugriff möglich ist . D. h. auch Nutzer:innen ohne Registrierung können Ihre Inhalte einsehen (kein Login erforderlich).';

$string['form:publish:accesstype'] = 'Zugriff erlauben für';
$string['form:publish:accesstype_help'] = 'Bitte wählen Sie aus, wer das geteilte ePortfolio sehen darf.';
$string['form:publish:internal'] = 'Intern veröffentlichen';
$string['form:publish:external'] = 'Extern veröffentlichen';
$string['form:publish:title'] = 'Titel';
$string['form:publish:description'] = 'Beschreibung';
$string['form:publish:select:approvaluser:empty'] = 'Keine Auswahl';
$string['form:publish:select:approval'] = 'Bitte Nutzer/in auswählen';
$string['form:publish:select:approvaluser'] = 'Veröffentlichung prüfen lassen durch';
$string['form:publish:select:approvaluser_help'] = '
Bitte wählen Sie aus, wer Ihr eingereichtes ePortfolio für die Veröffentlichung prüfen soll.<br><br>
Ihnen werden nur Nutzer/innen angezeigt, mit denen Sie im selben Kurs eingeschrieben sind.
';
$string['form:publish:select:approvaluser:hint'] = 'Bitte Nutzer/in auswählen!';
$string['form:publish:select:approvaluser:notfound'] = 'Wir konnten keine Nutzer:in zur Prüfung Ihres eingereichten ePortfolios finden.<br>
Bitte stellen Sie sicher, dass Sie in einem Kurs eingeschrieben sind, der zur Teilung von ePortfolios freigeschaltet ist.';
$string['form:publish:notes'] = 'Interne Notizen';
$string['form:publish:confirm'] = 'Bitte zur Kenntnis nehmen';
$string['form:publish:confirm:label'] = 'Es gelten die aktuellen Datenschutzbestimmungen und Nutzungsbedingungen dieser Plattform.
Mit dem Veröffentlichen Ihren ePortfolios stimmen Sie zu, dass der Inhalt durch andere Nutzer:innen verwendet, bearbeitet und weitergenutzt werden kann.';
$string['form:publish:save'] = 'ePortfolio einreichen';
$string['form:field:required'] = 'Bitte dieses Feld ausfüllen!';
$string['form:checkbox:required'] = 'Bitte bestätigen!';

$string['form:publish:success'] = '
Vielen Dank! Wir haben Ihre Anfrage zur Prüfung des ePortfolios mit dem Titel <b>{$a->title}</b> erhalten.<br>
Sie werden automatisch informiert, sobald der Inhalt geprüft und für den ePortfolio Hub freigegeben wurde.';
$string['form:publish:error'] = 'Beim Ausführen der Aktion ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.';
$string['form:publish:cancelled'] = 'Die Aktion wurde abgebrochen.';

// Review page.
$string['review:header'] = 'ePortfolio zur Freigabe für den Hub prüfen';
$string['review:accesstype'] = 'Zugriff ePortfolio Hub';
$string['review:accesstype:internal'] =
        'Der ePortfolio Hub wurde so konfiguriert, dass der Zugriff ausschließlich registrierten Nutzer:innen zur Verfügung gestellt wird (Login erforderlich).';
$string['review:accesstype:external'] =
        'Der ePortfolio Hub wurde so konfiguriert, dass der Zugriff sowohl ausschließlich für registrierte Nutzer:innen verfügbar ist (Login erforderlich), als auch ein öffentlicher Zugriff möglich ist . D. h. auch Nutzer:innen ohne Registrierung können Ihre Inhalte einsehen (kein Login erforderlich).';
$string['review:approval'] = 'Freigabeprozess';
$string['review:approval:simple'] =
        'Einfache Freigabe. Nach Ihrer Überprüfung können Sie entscheiden, ob das ePortfolio im Hub veröffentlicht oder weitere Korrekturen erforderlich sind.';
$string['review:approval:advanced'] =
        'Erweiterter Freigabeprozess. Nach Ihrer Überprüfung können Sie entscheiden, ob das ePortfolio zur nächsten Instanz zur Prüfung übergeben wird oder weitere Korrekturen erforderlich sind.';
$string['review:status'] = 'Status';
$string['review:title'] = 'Titel (Original)';
$string['review:usermodified'] = 'Eingereicht von';
$string['review:timecreated'] = 'Eingereicht am';
$string['review:timemodified'] = 'Aktualisiert';

$string['form:review:accesstype'] = 'Zugriff erlauben für';
$string['form:review:accesstype_help'] = 'Bitte wählen Sie aus, wer das geteilte ePortfolio sehen darf.';
$string['form:review:internal'] = 'Intern veröffentlichen';
$string['form:review:external'] = 'Extern veröffentlichen';
$string['form:review:title'] = 'Titel';
$string['form:review:description'] = 'Beschreibung';
$string['form:review:notes'] = 'Interne Notizen';
$string['form:review:usernotes'] = 'Grund für Ablehnung des ePortfolios';

$string['form:review:select:approve'] = 'Freigabe ePortfolio';
$string['form:review:select:pleaseselect'] = 'Bitte wählen';
$string['form:review:select:rejected'] = 'ePortfolio abgelehnt';
$string['form:review:select:simple'] = 'Im Hub veröffentlichen';
$string['form:review:select:advanced'] = 'Übergabe an nächste Freigabeinstanz';
$string['form:review:select:revoke'] = 'Freigabe zurückziehen';
$string['form:review:save'] = 'Speichern';

$string['form:review:success'] = '
Vielen Dank! Wir haben Ihre Änderungen gespeichert. Das von Ihnen geprüfte ePortfolio "{$a->title}" wurde im Hub veröffentlicht und "{$a->publishedby}" wurde über den aktuellen Status informiert.';
$string['form:review:success:feedback'] = '
Vielen Dank! Wir haben Ihre Änderungen gespeichert und "{$a->publishedby}" informiert, dass das eingereichte ePortfolio abgelehnt wurde.';
$string['form:review:error'] = 'Beim Ausführen der Aktion ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.';
$string['form:publish:cancelled'] = 'Die Aktion wurde abgebrochen.';

// View H5P player.
$string['hub:view:header'] = 'Ansicht ePortfolio';
$string['hub:view:button:backtohub'] = 'Zurück zum Hub';

// Settings.
$string['settings:hub:enable'] = 'ePortfolio Hub aktivieren';
$string['settings:hub:enable:desc'] =
        'Mit Aktivierung des Hub wird in der Hauptnavigation ein Eintrag für den ePortfolio Hub angezeigt. Des Weiteren können Nutzer:innen nach Aktivierung eigene Inhalte im ePortfolio Hub teilen.';
$string['settings:hub:navbartitle'] = 'Bezeichnung für Eintrag in der Navigation';
$string['settings:hub:navbartitle:desc'] =
        'Sie können eine eigene Bezeichnung für den Eintrag in der Navigation vergeben.';
$string['settings:hub:access'] = 'Zugriff ePortfolio Hub';
$string['settings:hub:access:desc'] =
        'Legen Sie fest, ob die Inhalte im Hub nur von registrierten Nutzer:innen (Interner Zugriff) angeschaut werden können oder auch von Gästen (Externer Zugriff)';
$string['settings:hub:access:internal'] = 'Interner Zugriff (Login erforderlich)';
$string['settings:hub:access:external'] = 'Externer Zugriff (kein Login erforderlich)';

$string['settings:hub:approval'] = 'Freigabeprozess';
$string['settings:hub:approval:desc'] =
        'Wie soll die Freigabe der ePortfolios erfolgen? Sie können wählen zwischen einem einfachen und erweiterten Prozess.<br>
<b>Einfacher Freigabeprozess:</b> Die Freigabe erfolgt nur durch eine ausgewählte Person mit der Berechtigung "eportfolioplugins/hub:approvesingle" sowohl im System- als auch Kurs-Kontext.<br>
<b>Erweiterter Freigabeprozess:</b> Für die Freigabe ist eine zusätzliche Person erforderlich (zweistufig). Dafür muss im Vorwege eine zusätzliche Rolle angelegt werden mit der Berechtigung "eportfolioplugins/hub:approveadvanced" im System-Kontext.';
$string['settings:hub:approval:simple'] = 'Einfacher Freigabeprozess';
$string['settings:hub:approval:advanced'] = 'Erweiterter Freigabeprozess';

$string['settings:hub:approval:simple:role'] = 'Einfacher Freigabeprozess';
$string['settings:hub:approval:simple:role:desc'] =
        'Bitte wählen Sie eine Rolle für den einfachen Freigabeprozess aus. Nach dieser Rolle wird im Kurs geschaut, um Nutzer zu ermitteln, die die Freigabe durchführen dürfen.';

$string['settings:hub:approval:advanced:role'] = 'Erweiterter Freigabeprozess';
$string['settings:hub:approval:advanced:role:desc'] =
        'Bitte wählen Sie eine Rolle für den erweiterten Freigabeprozess aus. Es wird empfohlen, eine zusätzliche Rolle anzulegen.';

// Message provider.
$string['messageprovider:publishing'] = 'Mitteilung über ein eingereichtes ePortfolio für den Hub';
$string['message:subject'] = 'Mitteilung über ein eingereichtes ePortfolio für den Hub - Status: {$a->status}';
$string['message:contexturlname'] = 'Veröffentlichtes ePortfolio anzeigen';

// Message for user who published ePortfolio.
$string['message:emailmessage:user'] =
        '<p>Vielen Dank für die Veröffentlichung Ihres ePortfolios! Wir haben Ihre Anfrage erhalten und werden das ePortfolio nun prüfen.
<br><br>Sie werden automatisch benachrichtigt, sobald der Inhalt geprüft und für den ePortfolio Hub freigegeben wurde.<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:user'] =
        '<p>Vielen Dank für die Veröffentlichung Ihres ePortfolios! Wir haben Ihre Anfrage erhalten und werden das ePortfolio nun prüfen.
<br><br>Sie werden automatisch benachrichtigt, sobald der Inhalt geprüft und für den ePortfolio Hub freigegeben wurde.<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Message for reviewer.
$string['message:emailmessage:reviewer'] =
        '<p>Ein neues ePortfolio wurde zur Veröffentlichung eingereicht. Bitte überprüfen Sie die Informationen und bestätigen Sie das ePortfolio oder 
bitten Sie den Benutzer, notwendige Korrekturen vorzunehmen.
<br><br>Details:<br>
Veröffentlicht von: {$a->userfrom}<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:reviewer'] =
        '<p>Ein neues ePortfolio wurde zur Veröffentlichung eingereicht. Bitte überprüfen Sie die Informationen und bestätigen Sie das ePortfolio oder 
bitten Sie den Benutzer, notwendige Korrekturen vorzunehmen.
<br><br>Details:<br>
Veröffentlicht von: {$a->userfrom}<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Message for revieweradvanced.
$string['message:emailmessage:revieweradvanced'] =
        '<p>Ein neues ePortfolio wurde zur Veröffentlichung eingereicht. Bitte überprüfen Sie die Informationen und bestätigen Sie das ePortfolio oder 
bitten Sie den Benutzer, notwendige Korrekturen vorzunehmen.
<br><br>Details:<br>
Veröffentlicht von: {$a->userfrom}<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:revieweradvanced'] =
        '<p>Ein neues ePortfolio wurde zur Veröffentlichung eingereicht. Bitte überprüfen Sie die Informationen und bestätigen Sie das ePortfolio oder 
bitten Sie den Benutzer, notwendige Korrekturen vorzunehmen.
<br><br>Details:<br>
Veröffentlicht von: {$a->userfrom}<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Message for user about published eportfolio.
$string['message:emailmessage:userpublished'] =
        '<p>Vielen Dank für die Einreichung Ihres ePortfolios! Ihr ePortfolio wurde für den Hub freigegeben!<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:userpublished'] =
        '<p>Vielen Dank für die Einreichung Ihres ePortfolios! Ihr ePortfolio wurde für den Hub freigegeben!<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Message for user to review own ePortfolio.
$string['message:emailmessage:userfeedback'] =
        '<p>Vielen Dank für die Einreichung Ihres ePortfolios! Ihr ePortfolio wurde geprüft.<br>
Leider entsprach es nicht den Anforderungen und die Veröffentlichung wurde abgelehnt. Bitte prüfen Sie den hinterlegten Grund für die Ablehnung.<br><br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:userfeedback'] =
        '<p>Vielen Dank für die Einreichung Ihres ePortfolios! Ihr ePortfolio wurde geprüft.<br>
Leider entsprach es nicht den Anforderungen und die Veröffentlichung wurde abgelehnt. Bitte prüfen Sie den hinterlegten Grund für die Ablehnung.<br><br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Message for user to review own ePortfolio.
$string['message:emailmessage:userrevoked'] =
        '<p>Ihr freigegebenes ePortfolio wurde zurückgezogen.<br> Bitte prüfen Sie den hinterlegten Grund.<br><br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:userrevoked'] =
        '<p>Ihr freigegebenes ePortfolio wurde zurückgezogen.<br> Bitte prüfen Sie den hinterlegten Grund .<br><br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Message for user advanced review .
$string['message:emailmessage:useradvanced'] =
        '<p>Vielen Dank für die Einreichung Ihres ePortfolios! Ihr ePortfolio wurde geprüft und an die nächste Instanz zur finalen Prüfung übergeben.<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';
$string['message:smallmessage:useradvanced'] =
        '<p>Vielen Dank für die Einreichung Ihres ePortfolios! Ihr ePortfolio wurde geprüft und an die nächste Instanz zur finalen Prüfung übergeben.<br>
ePortfolio: {$a->title}<br>
URL: <a href="{$a->viewurl}">{$a->viewurl}</a></p>';

// Set db/access - permissions.
$string['hub:publish'] = 'Eigene ePortfolios veröffentlichen';
$string['hub:approvesimple'] = 'Einfacher Freigabeprozes';
$string['hub:approveadvanced'] = 'Erweiterter Freigabeprozess';
$string['hub:viewall'] = 'Zugriff auf alle ePortfolios erlauben';