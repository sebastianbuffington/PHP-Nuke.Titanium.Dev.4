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
$pagetitle = _NETWORK_TITLE.' v'.$pj_config['version_number'].' - '._NETWORK_REQUESTS.': '._NETWORK_STATUSEDIT;
$status_id = intval($status_id);
if($status_id < 1) { header("Location: ".$admin_file.".php?op=RequestStatusList"); }
include_once(NUKE_BASE_DIR.'header.php');

$status = pjrequeststatus_info($status_id);
pjadmin_menu(_NETWORK_REQUESTS.': '._NETWORK_STATUSEDIT);
//echo "<br />";
OpenTable();
echo "<table align='center' border='0' cellpadding='2' cellspacing='2'>";
echo "<form method='post' action='".$admin_file.".php'>";
echo "<input type='hidden' name='op' value='RequestStatusUpdate'>";
echo "<input type='hidden' name='status_id' value='$status_id'>";
echo "<tr><td bgcolor='$bgcolor2'>"._NETWORK_STATUS.":</td>";
echo "<td><input type='text' name='status_name' size='30' value=\"".$status['status_name']."\"></td></tr>";
echo "<tr><td colspan='2' align='center'><input type='submit' value='"._NETWORK_STATUSUPDATE."'></td></tr>";
echo "</form>";
echo "</table>";
CloseTable();
pj_copy();
include_once(NUKE_BASE_DIR.'footer.php');

?>