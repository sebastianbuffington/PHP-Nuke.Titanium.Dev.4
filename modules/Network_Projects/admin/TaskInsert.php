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
$date = time();
$task_name = htmlentities($task_name, ENT_QUOTES);
$task_description = htmlentities($task_description, ENT_QUOTES);
$priority_id = intval($priority_id);
$status_id = intval($status_id);
$task_percent = intval($task_percent);
$project_id = intval($project_id);
$phpbb2_start_date = "$task_start_year-$task_start_month-$task_start_day";
if($phpbb2_start_date == "0000-00-00") { $phpbb2_start_date = 0; } else { $phpbb2_start_date = strtotime($phpbb2_start_date); }
$finish_date = "$task_finish_year-$task_finish_month-$task_finish_day";
if($finish_date == "0000-00-00") { $finish_date = 0; } else { $finish_date = strtotime($finish_date); }
$titanium_db2->sql_query("INSERT INTO `".$network_prefix."_tasks` VALUES (NULL, '$project_id', '$task_name', '$task_description', '$priority_id', '$status_id', '$task_percent', '$date', '$phpbb2_start_date', '$finish_date')");
$taskresult = $titanium_db2->sql_query("SELECT `task_id` FROM `".$network_prefix."_tasks` WHERE `date_created`='$date'");
list($task_id) = $titanium_db2->sql_fetchrow($taskresult);
if(implode("", $member_ids) > "") {
  while(list($null, $member_id) = each($member_ids)) {
    $numrows = $titanium_db2->sql_numrows($titanium_db2->sql_query("SELECT * FROM `".$network_prefix."_tasks_members` WHERE `task_id`='$task_id' AND `member_id`='$member_id'"));
    if($numrows == 0) {
      $titanium_db2->sql_query("INSERT INTO `".$network_prefix."_tasks_members` VALUES ('$task_id', '$member_id', '".$pj_config['new_task_position']."')");        
    }
  }
}
header("Location: ".$admin_file.".php?op=ProjectTasks&project_id=$project_id");

?>