<?php
/*=======================================================================
 PHP-Nuke Titanium: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/
/********************************************************/
/* NukeProject(tm)                                      */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://nukescripts.86it.us                           */
/* Copyright (c) 2000-2005 by NukeScripts Network       */
/********************************************************/
global $titanium_db2;
if(!defined('NETWORK_SUPPORT_ADMIN')) { die("Illegal Access Detected!!!"); }
$pagetitle = _NETWORK_TITLE.' v'.$pj_config['version_number'].' - '._NETWORK_REPORTS.': '._NETWORK_CONFIG;
include_once(NUKE_BASE_DIR.'header.php');

pjadmin_menu(_NETWORK_REPORTS.': '._NETWORK_CONFIG);
//echo "<br />\n";
OpenTable();
echo "<table align='center' border='0' cellpadding='2' cellspacing='2'>\n";
echo "<form method='post' action='".$admin_file.".php'>\n";
echo "<input type='hidden' name='op' value='ReportConfigUpdate'>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._NETWORK_ADMINEMAIL.":</strong></td>\n";
echo "<td><input type='text' name='admin_report_email' value=\"".$pj_config['admin_report_email']."\" size='30'></td></tr>\n";
if($pj_config['notify_report_admin'] == 1) { $notify_a = " selected"; $notify_b = ""; } else { $notify_a = ""; $notify_b = " selected"; }
echo "<tr><td bgcolor='$bgcolor2'><strong>"._NETWORK_NOTIFYADMIN.":</strong></td>\n";
echo "<td><select name='notify_report_admin'><option value='1'$notify_a>"._NETWORK_YES."</option>\n";
echo "<option value='0'$notify_b>"._NETWORK_NO."</option></select></td></tr>\n";
if($pj_config['notify_report_submitter'] == 1) { $notify_a = " selected"; $notify_b = ""; } else { $notify_a = ""; $notify_b = " selected"; }
echo "<tr><td bgcolor='$bgcolor2'><strong>"._NETWORK_NOTIFYSUBMITTER.":</strong></td>\n";
echo "<td><select name='notify_report_submitter'><option value='1'$notify_a>"._NETWORK_YES."</option>\n";
echo "<option value='0'$notify_b>"._NETWORK_NO."</option></select></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._NETWORK_NEWREPORTSTATUS.":</strong></td>\n";
echo "<td><select name='new_report_status'>\n";
$status = $titanium_db2->sql_query("SELECT `status_id`, `status_name` FROM `".$network_prefix."_reports_status` ORDER BY `status_weight`");
while(list($status_id, $status_name) = $titanium_db2->sql_fetchrow($status)) {
    if($pj_config['new_report_status'] == $status_id) { $sel = " selected"; } else { $sel = ""; }
    echo "<option value='$status_id' $sel>$status_name</option>\n";
}
echo "</select></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._NETWORK_NEWREPORTTYPE.":</strong></td>\n";
echo "<td><select name='new_report_type'>\n";
$type = $titanium_db2->sql_query("SELECT `type_id`, `type_name` FROM `".$network_prefix."_reports_types` ORDER BY `type_weight`");
while(list($type_id, $type_name) = $titanium_db2->sql_fetchrow($type)) {
    if($pj_config['new_report_type'] == $type_id) { $sel = " selected"; } else { $sel = ""; }
    echo "<option value='$type_id' $sel>$type_name</option>\n";
}
echo "</select></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2' valign='top'><strong>"._NETWORK_DATEFORMAT.":</strong></td>\n";
echo "<td><input type='text' name='report_date_format' value=\"".$pj_config['report_date_format']."\" size='30'><br />("._NETWORK_DATENOTE.")</td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._NETWORK_NEWREPORTPOSITION.":</strong></td>\n";
echo "<td><select name='new_report_position'>\n";
$position = $titanium_db2->sql_query("SELECT `position_id`, `position_name` FROM `".$network_prefix."_members_positions` ORDER BY `position_name`");
while(list($position_id, $position_name) = $titanium_db2->sql_fetchrow($position)) {
    if($pj_config['new_report_position'] == $position_id) { $sel = " selected"; } else { $sel = ""; }
    echo "<option value='$position_id' $sel>$position_name</option>\n";
}
echo "</select></td></tr>\n";
echo "<tr><td colspan='2' align='center'><input type='submit' value='"._NETWORK_CONFIGUPDATE."'></td></tr>\n";
echo "</form>\n";
echo "</table>\n";
CloseTable();
pj_copy();
include_once(NUKE_BASE_DIR.'footer.php');

?>