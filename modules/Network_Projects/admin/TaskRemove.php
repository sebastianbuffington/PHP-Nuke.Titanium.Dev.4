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
$pagetitle = _NETWORK_TITLE.' v'.$pj_config['version_number'].' - '._NETWORK_TASKS.': '._NETWORK_DELETETASK;
include_once(NUKE_BASE_DIR.'header.php');

$task = pjtask_info($task_id);
pjadmin_menu(_NETWORK_TASKS.': '._NETWORK_DELETETASK);
//echo "<br />\n";
OpenTable();
echo "<table align='center' border='0' cellpadding='2' cellspacing='2'>\n";
echo "<form method='post' action='".$admin_file.".php'>\n";
echo "<input type='hidden' name='op' value='TaskDelete'>\n";
echo "<input type='hidden' name='task_id' value='$task_id'>\n";
echo "<tr><td align='center'><strong>"._NETWORK_TASKCONFIRMDELETE."</strong></td></tr>\n";
echo "<tr><td align='center'><strong><i>".$task['task_name'].":</i></strong></td></tr>\n";
echo "<tr><td align='center'><i>".$task['task_description']."</i></td></tr>\n";
echo "<tr><td align='center'><input type='submit' value='"._NETWORK_DELETETASK."'></td></tr>\n";
echo "</form>\n";
echo "</table>\n";
CloseTable();
pj_copy();
include_once(NUKE_BASE_DIR.'footer.php');

?>