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

$pagetitle = _NETWORK_TITLE.' v'.$pj_config['version_number'].' - '._NETWORK_MEMBERS.': '._NETWORK_MEMBERADD;

include_once(NUKE_BASE_DIR.'header.php');

pjadmin_menu(_NETWORK_MEMBERS.": "._NETWORK_MEMBERADD);

OpenTable();
echo "<table align='center' border='0' cellpadding='2' cellspacing='2'>\n";
echo "<form method='post' action='".$admin_file.".php'>\n";
echo "<input type='hidden' name='op' value='MemberInsert'>\n";
echo "<tr><td bgcolor='$bgcolor2'>"._NETWORK_MEMBERNAME.":</td>\n";
echo "<td><input type='text' name='member_name' size='30'></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'>"._NETWORK_MEMBEREMAIL.":</td>\n";
echo "<td><input type='text' name='member_email' size='30'></td></tr>\n";
echo "<tr><td colspan='2' align='center'><input type='submit' value='"._NETWORK_MEMBERADD."'></td></tr>\n";
echo "</form>\n";
echo "</table>\n";
CloseTable();

pj_copy();

include_once(NUKE_BASE_DIR.'footer.php');
?>
