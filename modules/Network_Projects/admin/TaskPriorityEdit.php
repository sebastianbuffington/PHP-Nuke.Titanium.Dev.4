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
$pagetitle = _NETWORK_TITLE.' v'.$pj_config['version_number'].' - '._NETWORK_TASKS.': '._NETWORK_EDITPRIORITY;
$priority_id = intval($priority_id);
if($priority_id < 1) { header("Location: ".$admin_file.".php?op=TaskPriorityList"); }
include_once(NUKE_BASE_DIR.'header.php');

$priority = pjtaskpriority_info($priority_id);
pjadmin_menu(_NETWORK_TASKS.': '._NETWORK_EDITPRIORITY);
//echo "<br />\n";
OpenTable();
echo "<table align='center' border='0' cellpadding='2' cellspacing='2'>\n";
echo "<form method='post' action='".$admin_file.".php'>\n";
echo "<input type='hidden' name='op' value='TaskPriorityUpdate'>\n";
echo "<input type='hidden' name='priority_id' value='$priority_id'>\n";
echo "<tr><td bgcolor='$bgcolor2'>"._NETWORK_PRIORITYNAME.":</td>\n";
echo "<td><input type='text' name='priority_name' size='30' value=\"".$priority['priority_name']."\"></td></tr>\n";
echo "<tr><td colspan='2' align='center'><input type='submit' value='"._NETWORK_UPDATETASKPRIORITY."'></td></tr>\n";
echo "</form>";
echo "</table>";
CloseTable();
pj_copy();
include_once(NUKE_BASE_DIR.'footer.php');

?>