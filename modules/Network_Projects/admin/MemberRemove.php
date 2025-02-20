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

$pagetitle = _NETWORK_TITLE.' v'.$pj_config['version_number'].' - '._NETWORK_MEMBERS.': '._NETWORK_DELETEMEMBER;

include_once(NUKE_BASE_DIR.'header.php');

$member = pjmember_info($member_id);

pjadmin_menu(_NETWORK_MEMBERS.": "._NETWORK_DELETEMEMBER);

OpenTable();
echo "<table align='center' border='0' cellpadding='2' cellspacing='2'>\n";
echo "<form method='post' action='".$admin_file.".php'>\n";
echo "<input type='hidden' name='op' value='MemberDelete'>\n";
echo "<input type='hidden' name='member_id' value='$member_id'>\n";
echo "<tr><td align='center'><strong>"._NETWORK_SWAPMEMBER."</strong></td></tr>\n";
echo "<tr><td align='center'>".$member['member_name']." -> <select name='swap_member_id'>\n";
echo "<option value='0'>---------</option>\n";
$memberlist = $titanium_db2->sql_query("SELECT `member_id`, `member_name` FROM `".$network_prefix."_members` WHERE `member_id` != '$member_id' ORDER BY `member_name`");

while(list($s_member_id, $s_member_name) = $titanium_db2->sql_fetchrow($memberlist))
{
    echo "<option value='$s_member_id'>$s_member_name</option>\n";
}

echo "</select></td></tr>\n";
echo "<tr><td align='center'><input type='submit' value='"._NETWORK_DELETEMEMBER."'></td></tr>\n";
echo "</form>\n";
echo "</table>\n";

CloseTable();

pj_copy();

include_once(NUKE_BASE_DIR.'footer.php');
?>
