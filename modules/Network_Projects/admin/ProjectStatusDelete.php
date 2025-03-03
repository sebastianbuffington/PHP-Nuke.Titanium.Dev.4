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
get_lang('Network_Projects');
if(!defined('NETWORK_SUPPORT_ADMIN')) { die("Illegal Access Detected!!!"); }
$status_id = intval($status_id);
if($status_id < 1) { header("Location: ".$admin_file.".php?op=ProjectStatusList"); }
$status = pjprojectstatus_info($status_id);
$titanium_db2->sql_query("DELETE FROM `".$network_prefix."_projects_status` WHERE `status_id`='$status_id'");
$titanium_db2->sql_query("UPDATE `".$network_prefix."_projects` SET `status_id`='$swap_status_id' WHERE `status_id`='$status_id'");
$statusresult = $titanium_db2->sql_query("SELECT `status_id`, `status_weight` FROM `".$network_prefix."_projects_status` WHERE `status_weight`>='".$status['status_weight']."'");
while(list($p_id, $weight) = $titanium_db2->sql_fetchrow($statusresult)) {
    $new_weight = $weight - 1;
    $titanium_db2->sql_query("UPDATE `".$network_prefix."_projects_status` SET `status_weight`='$new_weight' WHERE `status_id`='$p_id'");
}
$titanium_db2->sql_query("OPTIMIZE TABLE `".$network_prefix."_projects_status`");
$titanium_db2->sql_query("OPTIMIZE TABLE `".$network_prefix."_projects`");
header("Location: ".$admin_file.".php?op=ProjectStatusList");

?>